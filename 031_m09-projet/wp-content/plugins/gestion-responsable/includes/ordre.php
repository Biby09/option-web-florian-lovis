<?php

if (!defined('ABSPATH')) {
    exit;
}

function gr_ajouter_page_ordre()
{
    add_submenu_page(
        'edit.php?post_type=responsable',
        __('Gestion des responsables', 'gestion-responsable'),
        __('Responsables', 'gestion-responsable'),
        'manage_options',
        'gr_ordre_responsables',
        'gr_render_page_ordre',
        0
    );
}
add_action('admin_menu', 'gr_ajouter_page_ordre');

function gr_render_page_ordre()
{
    $unites = get_terms(['taxonomy' => 'unite', 'hide_empty' => false, 'orderby' => 'term_id']);

    echo
        '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">
	    <input type="hidden" name="action" value="gr_sauver_ordre">

        <div class="wrap">
        <h1 class="wp-heading-inline">' . esc_html__('Gestion des responsables', 'gestion-responsable') . '</h1>
        <a href="' . esc_url(admin_url('post-new.php?post_type=responsable')) . '" class="page-title-action">' . esc_html__('Ajouter un responsable', 'gestion-responsable') . '</a>
        <hr class="wp-header-end">';

        if (isset($_GET['gr_message']) && $_GET['gr_message'] === 'ok') {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Ordre des responsables enregistré avec succès.', 'gestion-responsable') . '</p></div>';
        }

    echo
        '<p> ' . esc_html__('Gérez l’ordre d’affichage des responsables pour chaque unité.', 'gestion-responsable') . ' </p>
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

    $image_id = (int) get_option('gr_image_defaut', 0);

    echo
        '<h2>' . esc_html__('Image par défaut', 'gestion-responsable') . '</h2>
        <p class="description">' . esc_html__('Utilisée pour les responsables sans photo.', 'gestion-responsable') . '</p>
        <input type="hidden" id="gr_image_defaut" name="gr_image_defaut" value="' . esc_attr($image_id) . '">
        <div id="gr_image_apercu">' . ($image_id ? wp_get_attachment_image($image_id, 'thumbnail') : '') . '</div>
        <p>
            <button type="button" class="button" id="gr_choisir_image">' . esc_html__('Choisir une image', 'gestion-responsable') . '</button>
            <button type="button" class="button-link button-link-delete" id="gr_retirer_image"' . ($image_id ? '' : ' hidden') . '>' . esc_html__('Retirer l’image', 'gestion-responsable') . '</button>
        </p>';

    wp_nonce_field('gr_sauver_ordre', 'gr_ordre_nonce');
    submit_button(__('Enregistrer', 'gestion-responsable'));
    echo '</div></form>';
}

function gr_sauver_ordre()
{

    if (!isset($_POST['gr_ordre_nonce']) || !wp_verify_nonce($_POST['gr_ordre_nonce'], 'gr_sauver_ordre')) {
        wp_die(esc_html__('Nonce invalide. Veuillez réessayer.', 'gestion-responsable'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Vous n’avez pas la permission de modifier l’ordre des responsables.', 'gestion-responsable'));
    }

    if (isset($_POST['ordres']) && is_array($_POST['ordres'])) {
        foreach ($_POST['ordres'] as $unite_id => $ids) {
            $unite_id = absint($unite_id);
            if (!is_array($ids)) {
                continue;
            }
            if (!term_exists($unite_id, 'unite')) {
                continue;
            }
            $ids = array_map('absint', $ids);
            update_term_meta($unite_id, 'gr_ordre', $ids);
        }
    }

    if (isset($_POST['gr_image_defaut'])) {
        $image_id = absint($_POST['gr_image_defaut']);
        if ($image_id && !wp_attachment_is_image($image_id)) {
            $image_id = 0;
        }
        update_option('gr_image_defaut', $image_id);
    }

    wp_safe_redirect(admin_url('edit.php?post_type=responsable&page=gr_ordre_responsables&gr_message=ok'));
    exit;
}
add_action('admin_post_gr_sauver_ordre', 'gr_sauver_ordre');

function gr_get_ids_ordonnes( $unite_id ) {
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
        'fields' => 'ids',
    ]);
	$ordre = get_term_meta($unite_id, 'gr_ordre', true);
	if (!is_array($ordre)) {
		$ordre = [];
	}
	$ordonnes = array_intersect($ordre, $tous);
	$nouveaux = array_diff($tous, $ordre);
	return array_merge($ordonnes, $nouveaux);
}

function gr_scripts_ordre($hook)
{
    if ($hook !== 'responsable_page_gr_ordre_responsables') {
        return;
    }
    wp_enqueue_media();

    wp_enqueue_script(
        'gr-admin-ordre',
        plugin_dir_url(__DIR__) . 'assets/admin-ordre.js',
        ['jquery-ui-sortable'],
        '1.1.0',
        true
    );

    wp_localize_script('gr-admin-ordre', 'grOrdre', [
        'titreMediatheque' => __('Image par défaut des responsables', 'gestion-responsable'),
        'boutonMediatheque' => __('Utiliser cette image', 'gestion-responsable'),
    ]);

    wp_enqueue_style(
        'gr-admin-ordre',
        plugin_dir_url(__DIR__) . 'assets/admin-ordre.css',
        [],
        '1.1.0'
    );
}
add_action('admin_enqueue_scripts', 'gr_scripts_ordre');