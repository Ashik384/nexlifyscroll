jQuery(document).ready(function($) {
    $('.nexlifyscroll-color-picker').wpColorPicker();
    $('.nexlifyscroll-upload-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var input = button.siblings('.nexlifyscroll-media-id');
        var preview = button.siblings('.nexlifyscroll-media-preview');
        var removeButton = button.siblings('.nexlifyscroll-remove-button');

        var frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use Image' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.id);
            preview.attr('src', attachment.url).show();
            removeButton.show();
        });

        frame.open();
    });

    $('.nexlifyscroll-remove-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var input = button.siblings('.nexlifyscroll-media-id');
        var preview = button.siblings('.nexlifyscroll-media-preview');
        input.val('');
        preview.hide();
        button.hide();
    });
});