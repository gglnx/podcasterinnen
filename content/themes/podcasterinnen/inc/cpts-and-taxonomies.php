<?php
/**
 * @package     gglnx/podcasterinnen
 * @link        https://github.com/gglnx/podcasterinnen
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     GPL-2.0
 */

function pci_register_cpts_and_taxonomies() {
	// Register cpt: podcasts
	register_post_type( 'podcast', array(
		'labels' => array(
			'name' => __( 'Podcasts', 'pci' ),
			'singular_name' => __( 'Podcast', 'pci' )
		),
		'public' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-microphone',
		'supports' => ['title', 'revisions'],
		'has_archive' => true,
		'rewrite' => array(
			'slug' => _x( 'podcast', 'podcast slug', 'pci' ),
			'with_front' => false
		),
	) );

	// Register cpt: locations
	register_post_type( 'location', array(
		'labels' => array(
			'name' => __( 'Locations', 'pci' ),
			'singular_name' => __( 'Location', 'pci' )
		),
		'public' => true,
		'menu_position' => 6,
		'menu_icon' => 'dashicons-location-alt',
		'supports' => ['title'],
		'has_archive' => true,
		'rewrite' => array(
			'slug' => _x( 'location', 'location slug', 'pci' ),
			'with_front' => false
		),
	) );

	// Register topic taxonomy
	register_taxonomy( 'topic', 'podcast', array(
		'labels' => array(
			'name' => __( 'Topics', 'pci' ),
			'singular_name' => __( 'Topic', 'pci' )
		),
		'public' => true,
		'rewrite' => array(
			'slug' => _x( 'topic', 'topic slug', 'pci' ),
			'with_front' => false
		)
	) );
}

function pci_connect_podcasts_with_locations() {
	p2p_register_connection_type( array(
		'name' => 'locations_to_podcasts',
		'cardinality' => 'many-to-one',
		'from' => 'location',
		'to' => 'podcast',
		'sortable' => 'from',
		'admin_box' => array(
			'show' => 'to',
			'context' => 'side'
		)
	) );
}

add_action( 'after_setup_theme', 'pci_register_cpts_and_taxonomies' );
add_action( 'p2p_init', 'pci_connect_podcasts_with_locations' );
