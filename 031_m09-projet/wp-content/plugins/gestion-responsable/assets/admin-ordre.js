jQuery(function ($) {
    $('.gr_responsables_list').sortable({
        axis: 'y',               // déplacement vertical seulement
        containment: 'parent',    // limité au parent
    });
});