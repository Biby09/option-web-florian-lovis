<?php

if (!defined('ABSPATH')) {
    exit;
}

function gr_enregistrer_hooks_elementor()
{
    $unites = get_terms(['taxonomy' => 'unite', 'hide_empty' => false]);
    foreach ($unites as $unite) {
        add_action('elementor/query/responsables_' . $unite->slug, 'gr_filtrer_query_elementor');
    }
}
add_action('init', 'gr_enregistrer_hooks_elementor', 20);

function gr_filtrer_query_elementor($query)
{
    static $en_cours = false;
    if ($en_cours) {
        return;
    }
    $en_cours = true;
    $hook = current_action();
    $slug = str_replace('elementor/query/responsables_', '', $hook);

    $unite = get_term_by('slug', $slug, 'unite');
    if (!$unite) {
        $en_cours = false;
        return;
    }

    $ids = gr_get_ids_ordonnes($unite->term_id);

    $en_cours = false;

    if (empty($ids)) {

        $query->set('post_type', 'responsable');
        $query->set('post__in', [0]);
    } else {
        $query->set('post_type', 'responsable');
        $query->set('post__in', $ids);
        $query->set('orderby', 'post__in');
        $query->set('posts_per_page', -1);
    }
}

function gr_retours_ligne_fonctions($contenu, $widget)
{
    if ($widget->get_name() !== 'theme-post-excerpt' || get_post_type() !== 'responsable') {
        return $contenu;
    }
    return nl2br($contenu);
}
add_filter('elementor/widget/render_content', 'gr_retours_ligne_fonctions', 10, 2);
