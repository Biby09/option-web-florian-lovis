<?php
if (!defined('ABSPATH')) {
	exit;
}

function gr_register_post_type()
{
	register_post_type('responsable', [
		'labels' => [
			'name' => __('Responsables', 'gestion-responsable'),
			'singular_name' => __('Responsable', 'gestion-responsable'),
            'add_new_item' => __('Ajouter un responsable', 'gestion-responsable'),
            'edit_item' => __('Modifier le responsable', 'gestion-responsable'),
            'set_featured_image' => __('Définir l’image du responsable', 'gestion-responsable'),
            'remove_featured_image' => __('Supprimer l’image du responsable', 'gestion-responsable'),
            'use_featured_image' => __('Utiliser comme image du responsable', 'gestion-responsable'),
			'all_items' => __('Tous les responsables', 'gestion-responsable'),
		],
		'public' => true,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'rewrite' => false,
		'menu_icon' => 'dashicons-id',
		'supports' => ['thumbnail'],
	]);

	register_taxonomy('unite', 'responsable', [
		'labels' => [
			'name' => __('Unités', 'gestion-responsable'),
			'singular_name' => __('Unité', 'gestion-responsable'),
			'edit_item' => __('Modifier l’unité', 'gestion-responsable'),
			'add_new_item' => __('Ajouter une unité', 'gestion-responsable'),
		],
		'public' => true,
		'publicly_queryable' => false,
		'rewrite' => false,
		'hierarchical' => true,
		'show_admin_column' => true,
	]);
}

add_action('init', 'gr_register_post_type');

function gr_colonnes_unites( $colonnes ) {
	unset( $colonnes['description'] );
	unset ( $colonnes['slug'] );
	return $colonnes;
}
add_filter( 'manage_edit-unite_columns', 'gr_colonnes_unites' );

function gr_simplifier_unites() {
	$ecran = get_current_screen();          // quel écran admin est affiché ?
	if ( $ecran->taxonomy !== 'unite' ) {
		return;
	}
	echo
    '<style>
        .form-field.term-description-wrap,
        .form-field.term-slug-wrap,
        .form-field.term-parent-wrap {
            display: none;
        }
    </style>';
}
add_action( 'admin_head', 'gr_simplifier_unites' );