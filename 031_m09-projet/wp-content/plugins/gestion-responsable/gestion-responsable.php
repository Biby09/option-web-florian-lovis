<?php
/**
 * Plugin Name: Gestionnaire des responsables
 * Description: Améliore la gestion des responsables dans la page admin de wordpress.
 * Version:     1.0.0
 * Author:      Florian Lovis
 * Text Domain: gestion-responsable
 */

// Protection : si le fichier est ouvert directement (hors de WordPress), on arrête tout.
if (!defined('ABSPATH')) {
	exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/metabox.php';
require_once plugin_dir_path(__FILE__) . 'includes/ordre.php';