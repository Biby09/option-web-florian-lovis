<?php

if (!defined('ABSPATH')) {
    exit;
}


function gr_ajouter_page_ordre()
{
    add_submenu_page(
        'edit.php?post_type=responsable', // slug du menu parent
        __('Gestion des responsables', 'gestion-responsable'), // titre de la page
        __('Responsables', 'gestion-responsable'), // titre du menu
        'manage_options', // capacité requise pour accéder à la page
        'gr_ordre_responsables', // slug de la page
        'gr_render_page_ordre', // fonction qui affiche le contenu de la page
        0
    );
}
add_action('admin_menu', 'gr_ajouter_page_ordre');

function gr_render_page_ordre()
{
    $unites = get_terms(['taxonomy' => 'unite', 'hide_empty' => false]);

    echo
        '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">
	    <input type="hidden" name="action" value="gr_sauver_ordre">

        <div class="wrap">
        <h1>' . esc_html__('Gestion des responsables', 'gestion-responsable') . '</h1>
        <a href="' . esc_url(admin_url('post-new.php?post_type=responsable')) . '" class="page-title-action">' . esc_html__('Ajouter un responsable', 'gestion-responsable') . '</a>
        <p> ' . esc_html__('Gérez l’ordre d’affichage des responsables pour chaque unité.', 'gestion-responsable') . ' </p>
        <div id="gr_ordre_responsables_container">';


    foreach ($unites as $unite) {

        $ids = gr_get_ids_ordonnes($unite->term_id);

        echo '<div class="gr_unite_section">';
        echo '<h2>' . esc_html($unite->name) . '</h2>';

        if (empty($ids)) {
            echo '<p>' . esc_html__('Aucun responsable pour cette unité.', 'gestion-responsable') . '</p>';
        } else {

            echo '<ul class="gr_responsables_list" data-unite-id="' . esc_attr($unite->term_id) . '">';
            foreach ($ids as $id) {
                echo
                    '<li class="gr_responsable_item" data-id="' . esc_attr($id) . '">
                <p>' . esc_html(get_the_title($id)) . '</p>
                <input type="hidden" name="ordres[' . esc_attr($unite->term_id) . '][]" value="' . esc_attr($id) . '">
                </li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }

    echo '</div>';
    wp_nonce_field('gr_sauver_ordre', 'gr_ordre_nonce');
    submit_button(__('Enregistrer', 'gestion-responsable'));
    echo '</div></form>';
}

function gr_sauver_ordre()
{

    if (!isset($_POST['gr_ordre_nonce']) || !wp_verify_nonce($_POST['gr_ordre_nonce'], 'gr_sauver_ordre')) {
        wp_die(__('Nonce invalide. Veuillez réessayer.', 'gestion-responsable'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(__('Vous n’avez pas la permission de modifier l’ordre des responsables.', 'gestion-responsable'));
    }

    if (isset($_POST['ordres']) && is_array($_POST['ordres'])) {
        foreach ($_POST['ordres'] as $unite_id => $ids) {
            $unite_id = absint($unite_id);
            if (!is_array($ids)) {
                continue; // si ce n’est pas un tableau, on ignore
            }
            if (!term_exists($unite_id, 'unite')) {
                continue; // si l’unité n’existe pas, on ignore
            }
            $ids = array_map('absint', $ids);
            update_term_meta($unite_id, 'gr_ordre', $ids);
        }
    }

    wp_safe_redirect(admin_url('edit.php?post_type=responsable&page=gr_ordre_responsables&gr_message=ok'));
    exit;
}
add_action('admin_post_gr_sauver_ordre', 'gr_sauver_ordre');

function gr_get_ids_ordonnes( $unite_id ) {
	// 1. Tous les responsables de l'unité → get_posts( [ …, 'fields' => 'ids' ] )
    $tous = get_posts([
        'post_type' => 'responsable',
        'tax_query' => [
            [
                'taxonomy' => 'unite',
                'field' => 'term_id',
                'terms' => $unite_id,
            ],
        ],
        'order' => 'ASC',
        'numberposts' => -1,
        'fields' => 'ids', // Récupérer uniquement les IDs
    ]);
	// 2. L'ordre enregistré → get_term_meta( $unite_id, 'gr_ordre', true ), ou [] si vide
	$ordre = get_term_meta($unite_id, 'gr_ordre', true);
	if (!is_array($ordre)) {
		$ordre = [];
	}
	// 3. Garder de l'ordre seulement ceux encore dans l'unité  → array_intersect( $ordre, $tous )
	$ordonnes = array_intersect($ordre, $tous);
	// 4. Ajouter à la fin les nouveaux, absents de l'ordre    → array_diff( $tous, $ordre )
	$nouveaux = array_diff($tous, $ordre);
	// 5. return array_merge( résultat 3, résultat 4 );
	return array_merge($ordonnes, $nouveaux);
}


function gr_scripts_ordre($hook)
{
    if ($hook !== 'responsable_page_gr_ordre_responsables') {
        return; // pas notre page → on ne charge rien
    }
    wp_enqueue_script(
        'gr-admin-ordre',                                        // identifiant unique
        plugin_dir_url(__DIR__) . 'assets/admin-ordre.js',     // URL du fichier
        ['jquery-ui-sortable'],
        '1.0.0',
        true                                                     // charger en bas de page
    );

    wp_localize_script('gr-admin-ordre', 'grOrdre', [
        'nonce' => wp_create_nonce('gr_sauver_ordre'),
    ]);


    wp_enqueue_style(
        'gr-admin-ordre',
        plugin_dir_url(__DIR__) . 'assets/admin-ordre.css',
        [],
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'gr_scripts_ordre');