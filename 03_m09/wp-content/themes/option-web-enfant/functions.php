<?php
/**
 * Fonctions du thème enfant Option web.
 *
 * Ce fichier est chargé automatiquement par WordPress, juste AVANT
 * le functions.php du thème parent.
 */

add_action( 'wp_enqueue_scripts', 'ow_charger_styles' );

/**
 * Charge la feuille de style du thème enfant, après celle du parent.
 */
function ow_charger_styles(): void {
	wp_enqueue_style(
		'ow-enfant',                              // 1. un nom unique (préfixé)
		get_stylesheet_uri(),                     // 2. l'URL du style.css de l'ENFANT
		array( 'twenty-twenty-one-style' ),       // 3. dépendances : charger APRÈS le parent
		wp_get_theme()->get( 'Version' )          // 4. version (évite le cache du navigateur)
	);
}
