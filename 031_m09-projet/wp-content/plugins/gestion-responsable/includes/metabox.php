<?php
if (!defined('ABSPATH')) {
    exit;
}

function gr_add_metabox()
{
    add_meta_box(
        'gr_identite',
        __('Identité', 'gestion-responsable'),
        'gr_render_metabox',
        'responsable',
    );

    remove_meta_box('pageparentdiv', 'responsable', 'side');

}
add_action('add_meta_boxes', 'gr_add_metabox');

function gr_render_metabox($post)
{

    $fonctions = $post->post_excerpt === '' ? [] : explode("\n", $post->post_excerpt);

    echo '<p class="description">' . esc_html__('Le prénom et le nom sont obligatoires. Le totem est optionnel.', 'gestion-responsable') . '</p>';
    echo '<table class="form-table" role="presentation">';

    echo '<tr>';
    echo '<th scope="row"><label for="gr_prenom">' . esc_html__('Prénom', 'gestion-responsable') . '</label></th>';
    echo '<td><input type="text" id="gr_prenom" name="gr_prenom" class="regular-text" value="' . esc_attr(get_post_meta($post->ID, 'gr_prenom', true)) . '" required /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th scope="row"><label for="gr_nom">' . esc_html__('Nom', 'gestion-responsable') . '</label></th>';
    echo '<td><input type="text" id="gr_nom" name="gr_nom" class="regular-text" value="' . esc_attr(get_post_meta($post->ID, 'gr_nom', true)) . '" required /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th scope="row"><label for="gr_totem">' . esc_html__('Totem', 'gestion-responsable') . '</label></th>';
    echo '<td><input type="text" id="gr_totem" name="gr_totem" class="regular-text" value="' . esc_attr(get_post_meta($post->ID, 'gr_totem', true)) . '" /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th scope="row"><h3>' . esc_html__('Fonctions', 'gestion-responsable') . '</h3></th>';
    echo '</tr>';
    echo '<tbody id="gr_fonctions_container">';
    if (empty($fonctions)) {
        echo '<tr>';
        echo '<th scope="row"><label for="gr_fonction_1">' . esc_html__('Fonction 1', 'gestion-responsable') . '</label></th>';
        echo '<td><input type="text" id="gr_fonction_1" name="gr_fonction[]" class="regular-text" value="" required/></td>';
        echo '</tr>';
    } else {
        foreach ($fonctions as $index => $fonction) {
            $index++;
            echo '<tr>';
            echo '<th scope="row"><label for="gr_fonction_' . esc_attr($index) . '">' . esc_html__('Fonction', 'gestion-responsable') . ' ' . esc_html($index) . '</label></th>';
            echo '<td><input type="text" id="gr_fonction_' . esc_attr($index) . '" name="gr_fonction[]" class="regular-text" value="' . esc_attr($fonction) . '" /></td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';

    echo '<tr>';
    echo '<th scope="row"></th>';
    echo '<td><button type="button" id="gr_add_fonction" class="button">' . esc_html__('Ajouter une fonction', 'gestion-responsable') . '</button></td>';
    echo '</tr>';

    echo '</table>';

    wp_nonce_field('gr_save_metabox', 'gr_metabox_nonce');
}

function gr_sanitize_name($string)
{
    return mb_ucfirst(sanitize_text_field($string));
}
function gr_save_metabox($post_id)
{

    if (!isset($_POST['gr_metabox_nonce']) || !wp_verify_nonce($_POST['gr_metabox_nonce'], 'gr_save_metabox')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['gr_prenom'])) {
        update_post_meta($post_id, 'gr_prenom', gr_sanitize_name($_POST['gr_prenom']));
    }
    if (isset($_POST['gr_nom'])) {
        update_post_meta($post_id, 'gr_nom', gr_sanitize_name($_POST['gr_nom']));
    }
    if (isset($_POST['gr_totem'])) {
        update_post_meta($post_id, 'gr_totem', gr_sanitize_name($_POST['gr_totem']));
    }

}

add_action('save_post_responsable', 'gr_save_metabox');

function gr_generer_titre($data)
{

    if ($data['post_type'] !== 'responsable') {
        return $data;
    }

    if (!isset($_POST['gr_metabox_nonce']) || !wp_verify_nonce($_POST['gr_metabox_nonce'], 'gr_save_metabox')) {
        return $data;
    }

    $unites = [];
    if (isset($_POST['tax_input']['unite']) && is_array($_POST['tax_input']['unite'])) {
        $unites = array_map('absint', $_POST['tax_input']['unite']);
        $unites = array_filter($unites);
    }

    if (empty($unites) && $data['post_status'] === 'publish') {
        $data['post_status'] = 'draft';
    }

    $prenom = isset($_POST['gr_prenom']) ? gr_sanitize_name($_POST['gr_prenom']) : '';
    $nom = isset($_POST['gr_nom']) ? gr_sanitize_name($_POST['gr_nom']) : '';
    $totem = isset($_POST['gr_totem']) ? gr_sanitize_name($_POST['gr_totem']) : '';

    if (empty($prenom) || empty($nom)) {
        return $data;
    }

    $data['post_title'] = $prenom . ' ' . $nom;

    if (!empty($totem)) {
        $data['post_title'] .= ', ' . $totem;
    }

    if (isset($_POST['gr_fonction']) && is_array($_POST['gr_fonction'])) {
        $fonctions = array_map('gr_sanitize_name', $_POST['gr_fonction']);
        $fonctions = array_filter($fonctions);
        $data['post_excerpt'] = implode("\n", $fonctions);
    }

    return $data;
}
add_filter('wp_insert_post_data', 'gr_generer_titre');

function gr_scripts_fiche( $hook ) {

	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) {
		return;
	}

	if ( get_current_screen()->post_type !== 'responsable' ) {
		return;
	}

	wp_enqueue_script(
		'gr-admin-fiche',
        plugin_dir_url( __DIR__ ) . 'assets/admin-fiche.js',
		[ 'jquery' ],
		'1.0.0',
		true
	);

	wp_localize_script( 'gr-admin-fiche', 'grFiche', [
		'messageUnite' => __( 'Cochez au moins une unité.', 'gestion-responsable' ),
        'libelleFonction' => __( 'Fonction', 'gestion-responsable' ),
	] );
}

add_action( 'admin_enqueue_scripts', 'gr_scripts_fiche' );