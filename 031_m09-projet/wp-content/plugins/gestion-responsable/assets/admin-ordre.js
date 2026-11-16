jQuery(function ($) {
    $('.gr_responsables_list').sortable({
        axis: 'y',
        containment: 'parent',
    });

    var mediatheque;

    $('#gr_choisir_image').on('click', function () {
        if (!mediatheque) {
            mediatheque = wp.media({
                title: grOrdre.titreMediatheque,
                button: { text: grOrdre.boutonMediatheque },
                library: { type: 'image' },
                multiple: false,
            });

            mediatheque.on('select', function () {
                var image = mediatheque.state().get('selection').first().toJSON();
                var url = image.sizes && image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url;

                $('#gr_image_defaut').val(image.id);
                $('#gr_image_apercu').empty().append($('<img>').attr({ src: url, alt: '' }));
                $('#gr_retirer_image').prop('hidden', false);
            });
        }

        mediatheque.open();
    });

    $('#gr_retirer_image').on('click', function () {
        $('#gr_image_defaut').val(0);
        $('#gr_image_apercu').empty();
        $(this).prop('hidden', true);
    });
});
