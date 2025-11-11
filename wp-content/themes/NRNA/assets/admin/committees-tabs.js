jQuery(document).ready(function($) {
    // Tab switching for Committees meta box
    $('.committees-meta-tabs .tab-button').on('click', function() {
        var tab = $(this).data('tab');
        $('.committees-meta-tabs .tab-button').removeClass('active');
        $(this).addClass('active');
        $('.committees-meta-tabs .tab-pane').hide();
        $('#tab-' + tab).show();
    });

    // Set default active tab
    $('.committees-meta-tabs .tab-button:first').addClass('active');
    $('.committees-meta-tabs .tab-pane:first').show();

    // Add hero image
    $(document).on('click', '.add-item[data-repeater="committees_hero_images"]', function() {
        var container = $(this).prev('.repeater-container');
        var itemCount = container.find('.repeater-item').length;
        var newItem = '<div class="repeater-item">' +
            '<p><label>Image:</label><br>' +
            '<input type="hidden" name="committees_hero_images[' + itemCount + '][image]" class="image-id">' +
            '<img src="" class="image-preview" style="max-width: 100px; max-height: 100px; display: none;">' +
            '<button type="button" class="upload-image button">Upload Image</button></p>' +
            '<button type="button" class="remove-item button">Remove</button>' +
            '</div>';
        container.append(newItem);
    });

    // Remove hero image
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.repeater-item').remove();
        // Re-index the remaining items
        $('.repeater-container[data-repeater="committees_hero_images"] .repeater-item').each(function(index) {
            $(this).find('input[name*="committees_hero_images"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add banner1 stat
    $(document).on('click', '.add-item[data-repeater="committees_banner1_stats"]', function() {
        var container = $(this).prev('.repeater-container');
        var itemCount = container.find('.repeater-item').length;
        var newItem = '<div class="repeater-item">' +
            '<p><label>Title:</label><br><input type="text" name="committees_banner1_stats[' + itemCount + '][title]" class="wide-input"></p>' +
            '<p><label>Description:</label><br><textarea name="committees_banner1_stats[' + itemCount + '][description]" rows="5" class="wide-textarea"></textarea></p>' +
            '<button type="button" class="remove-item button">Remove</button>' +
            '</div>';
        container.append(newItem);
    });

    // Remove banner1 stat
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.repeater-item').remove();
        // Re-index the remaining items
        $('.repeater-container[data-repeater="committees_banner1_stats"] .repeater-item').each(function(index) {
            $(this).find('input[name*="committees_banner1_stats"], textarea[name*="committees_banner1_stats"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add team member
    $(document).on('click', '.add-teams', function() {
        var tbody = $(this).prev('.teams-table').find('tbody');
        var rowCount = tbody.find('.teams-row').length;
        var newRow = '<tr class="teams-row">' +
            '<td><input type="text" name="committees_teams_members[' + rowCount + '][project]" class="wide-input"></td>' +
            '<td><input type="text" name="committees_teams_members[' + rowCount + '][name]" class="wide-input"></td>' +
            '<td><input type="email" name="committees_teams_members[' + rowCount + '][email]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-teams button">Remove</button></td>' +
            '</tr>';
        tbody.append(newRow);
    });

    // Remove team member
    $(document).on('click', '.remove-teams', function() {
        $(this).closest('.teams-row').remove();
        // Re-index the remaining rows
        $('.teams-row').each(function(index) {
            $(this).find('input[name*="committees_teams_members"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Image upload for hero images and single images
    $(document).on('click', '.upload-image', function(e) {
        e.preventDefault();
        var button = $(this);
        var container = button.closest('p');
        var input = container.find('.image-id');
        var preview = container.find('.image-preview');
        var custom_uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            input.val(attachment.id);
            preview.attr('src', attachment.url).show();
            button.text('Change Image');
        }).open();
    });
});
