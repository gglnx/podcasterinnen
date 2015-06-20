<?php
/**
 * @package     gglnx/podcasterinnen
 * @link        https://github.com/gglnx/podcasterinnen
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     GPL-2.0
 */

if ( !defined('WP_LOAD_IMPORTERS') )
	return;

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( !class_exists( 'WP_Importer' ) ):
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require_once $class_wp_importer;
endif;

class Pci_Import_From_Json extends WP_Importer {
	public function header() {
		echo '<div class="wrap">';
		echo '<h2>'. __('Import from JSON', 'pic') . '</h2>';
	}

	public function footer() {
		echo '</div>';
	}

	public function greet() {
		echo '<div class="narrow">';
		echo '<p>' . __('Howdy! This importer allows you to import podcasts from a JSON file.', 'pci') . '</p>';
		wp_import_upload_form( "admin.php?import=pci-json&amp;step=1" );
		echo '</div>';
	}

	public function import() {
		// Handle upload
		$file = wp_import_handle_upload();
		if ( isset( $file['error'] ) ):
			echo $file['error'];
			return;
		endif;

		// Load podcasts
		$podcasts = json_decode( file_get_contents( $file['file'] ) );

		// Add podcast
		foreach ( $podcasts as $podcast ):
			// Check if podcast already exists
			$query = new WP_Query( array(
				'post_type' => 'podcast',
				'meta_query' => array(
					array(
						'compare' => '=',
						'key' => 'feed_url',
						'value' => $podcast->mp3feed
					)
				)
			) );

			// Don't add podcast if it exists
			if ( $query->have_posts() )
				continue;

			// Prepare post array
			$post = array(
				'post_title' => $podcast->podcasttitel,
				'post_status' => 'publish',
				'post_type' => 'podcast',
				'tax_input' => array(
					'topic' => $podcast->themengebiete
				)
			);

			// Create post
			$post_id = wp_insert_post( $post );

			// Women
			$women = array();
			foreach ( explode( ',', $podcast->podcasterinnen ) as $woman ):
				$women[] = array( 'name' => trim( $woman ) );
			endforeach;
			add_post_meta( $post_id, 'women', $women );

			// Meta informationen
			add_post_meta( $post_id, 'participants', $podcast->hostsW + $podcast->hostsM );
			add_post_meta( $post_id, 'active', ( $podcast->aktiv == 'yes' ) ? 'on' : 'off' );
			add_post_meta( $post_id, 'feed_url', trim( $podcast->mp3feed ) );

			// Active since
			$active_since = explode( '-', $podcast->aktivSeitB );
			add_post_meta( $post_id, 'active_since', '01/' . $active_since[1] . '/' . $active_since[0] );

			// URLs
			$urls = array( array( 'url' => trim( $podcast->url ) ) );
			if ( !empty( trim( $podcast->kontakttwitteradn ) ) )
				$urls[] = array( 'url' => trim( $podcast->kontakttwitteradn ) );
			add_post_meta( $post_id, 'urls', $urls );

			// Check for locations
			if ( empty( trim( $podcast->orte ) ) )
				continue;

			// Locations
			foreach ( explode( ',', $podcast->orte ) as $index => $location ):
				$location_name = trim( $location );
				$location_slug = sanitize_title( $location_name );

				// Check if location already exists
				$query = new WP_Query( array(
					'post_type' => 'location',
					'name' => $location_slug
				) );

				// Add location if it exists
				if ( $query->have_posts() ):
					while ( $query->have_posts() ):
						$location = $query->next_post()->ID;
					endwhile;
				else:
					$location_data = array(
						'post_title' => $location_name,
						'post_name' => $location_slug,
						'post_status' => 'publish',
						'post_type' => 'location'
					);

					$location = wp_insert_post( $location_data );

					$latLngName = "ort" . ( $index + 1 ) . "Coordinates";
					if ( $podcast->$latLngName ):
						$latLng = explode( ',', str_replace( '°', '', $podcast->$latLngName ) );
						add_post_meta( $location, 'location_latitude', floatval( trim( $latLng[0] ) ) );
						add_post_meta( $location, 'location_longitude', floatval( trim( $latLng[1] ) ) );
						add_post_meta( $location, 'location', array(
							'latitude' => floatval( trim( $latLng[0] ) ),
							'longitude' => floatval( trim( $latLng[1] ) )
						) );
					endif;
				endif;

				p2p_create_connection( 'locations_to_podcasts', array(
					'from' => $location,
					'to' => $post_id
				) );
			endforeach;
		endforeach;

		wp_import_cleanup( $file['id'] );
		do_action( 'import_done', 'pci-json' );

		echo '<h3>';
		printf( __( 'All done. <a href="%s">Have fun!</a>', 'pci'), get_option( 'home' ) );
		echo '</h3>';
	}

	public function dispatch() {
		if ( empty ( $_GET['step'] ) ):
			$step = 0;
		else:
			$step = (int) $_GET['step'];
		endif;

		$this->header();

		switch ($step):
			case 0 :
				$this->greet();
				break;
			case 1 :
				check_admin_referer( 'import-upload' );
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		endswitch;

		$this->footer();
	}
}

$pci_json_import = new Pci_Import_From_Json();

register_importer(
	'pci-json',
	__( 'Podcasts', 'pci' ),
	__( 'Import podcasts from a json file.', 'pci' ),
	array( $pci_json_import, 'dispatch' )
);
