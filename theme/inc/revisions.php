<?php
/**
 * @package     gglnx/podcasterinnen
 * @link        https://github.com/gglnx/podcasterinnen
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     GPL-2.0
 */

function pci_add_meta_keys_to_revision( $keys ) {
	// CPT: location
	$keys[] = 'location_latitude';
	$keys[] = 'location_longitude';
	$keys[] = 'location';

	// CPT: podcast
	$keys[] = 'women';
	$keys[] = 'participants';
	$keys[] = 'active';
	$keys[] = 'active_since';
	$keys[] = 'feed_url';
	$keys[] = 'urls';

	return $keys;
}

add_filter( 'wp_post_revision_meta_keys', 'pci_add_meta_keys_to_revision' );
