<?php

// Check if WordPress is installed
if ( is_dir( 'wordpress' ) ) {
	include 'wordpress/index.php';
} else {
	exit('Please install WordPress first (run e.g. composer install).');
}
