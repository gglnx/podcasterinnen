<?php
/**
 * @package     gglnx/podcasterinnen
 * @link        https://github.com/gglnx/podcasterinnen
 * @author      Dennis Morhardt <info@dennismorhardt.de>
 * @copyright   Copyright 2015, Dennis Morhardt
 * @licence     GPL-2.0
 */

// Load registration of custom post types
require 'inc/cpts-and-taxonomies.php';

// Load registration of admin ui
require 'inc/admin-ui.php';

// Load registration of revision handling
require 'inc/revisions.php';

// Load import function
if ( defined( 'WP_LOAD_IMPORTERS' ) )
	require 'inc/import.php';
