<?php
/**
 * Plugin Name: Gestionnaire des responsables
 * Description: Améliore la gestion des responsables dans la page admin de wordpress.
 * Version:     1.0.0
 * Author:      Florian Lovis
 * Text Domain: gestion-responsable
 * Requires PHP: 8.4
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/metabox.php';
require_once plugin_dir_path(__FILE__) . 'includes/ordre.php';
require_once plugin_dir_path(__FILE__) . 'includes/elementor.php';

function gr_activation() {

	gr_register_post_type();

	$unites = [ 'Groupe', 'Meute', 'Troupe', 'Poste', 'Route' ];

	foreach ( $unites as $nom ) {
		if ( ! term_exists( $nom, 'unite' ) ) {
			wp_insert_term( $nom, 'unite' );
		}
	}
}
register_activation_hook( __FILE__, 'gr_activation' );
