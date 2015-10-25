<?php
/**
 * @package     gglnx/podcasterinnen
 * @link        https://github.com/gglnx/podcasterinnen
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     GPL-2.0
 */

function pci_setup_location_metabox() {
	// Locations
	$locations = new_cmb2_box( array(
		'id' => 'pci_locations_metabox',
		'title' => 'Informationen',
		'object_types' => array( 'location' ),
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true
	) );

	// Add position field to locations
	$locations->add_field( array(
		'id' => 'location',
		'desc' => 'Position des Ortes',
		'name' => 'Position',
		'type' => 'pw_map',
		'split_values' => true
	) );
}

function pci_setup_podcast_metaboxes() {
	// New box: Women
	$women = new_cmb2_box( array(
		'id' => 'pci_podcasts_women_metabox',
		'title' => 'Podcasterin(nen)*',
		'object_types' => array( 'podcast' ),
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true
	) );

	// Women group
	$women_group = $women->add_field( array(
		'id' => 'women',
		'type' => 'group',
		'options' => array(
			'group_title' => 'Podcasterin*',
			'add_button' => 'Hinzufügen',
			'remove_button' => 'Entfernen'
		)
	) );

	// Women: name
	$women->add_group_field( $women_group, array(
		'name' => 'Name',
		'id' => 'name',
		'type' => 'text'
	) );

	// New box: meta
	$meta = new_cmb2_box( array(
		'id' => 'pci_podcasts_meta_metabox',
		'title' => 'Meta-Informationen',
		'object_types' => array( 'podcast' ),
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true
	) );

	// Meta: participants
	$meta->add_field( array(
		'id' => 'participants',
		'name' => 'Anzahl der Teilnehmer/innen*',
		'type' => 'text_small'
	) );

	// Meta: active
	$meta->add_field( array(
		'id' => 'active',
		'name' => 'Status',
		'default' => '1',
		'type' => 'checkbox',
		'desc' => 'Ist der Podcast noch aktiv?'
	) );

	// Meta: active_since
	$meta->add_field( array(
		'id' => 'active_since',
		'name' => 'Aktiv seit',
		'type' => 'text_date'
	) );

	// New box: URLs
	$urls = new_cmb2_box( array(
		'id' => 'pci_podcasts_urls_metabox',
		'title' => 'URLs',
		'object_types' => array( 'podcast' ),
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true
	) );

	// URLs: Feed url
	$urls->add_field( array(
		'id' => 'feed_url',
		'name' => 'Feed-URL',
		'type' => 'text_url',
		'protocols' => array( 'http', 'https' )
	) );

	// URLs: URL group
	$urls_group = $urls->add_field( array(
		'id' => 'urls',
		'type' => 'group',
		'options' => array(
			'group_title' => 'Weitere URLs',
			'add_button' => 'Hinzufügen',
			'remove_button' => 'Entfernen'
		)
	) );

	// URL:
	$urls->add_group_field( $urls_group, array(
		'id' => 'url',
		'name' => 'URL',
		'type' => 'text_url',
		'protocols' => array( 'http', 'https' )
	) );
}

add_action( 'cmb2_init', 'pci_setup_location_metabox' );
add_action( 'cmb2_init', 'pci_setup_podcast_metaboxes' );
