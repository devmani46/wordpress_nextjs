jQuery(document).ready(function($) {
    // Tab switching for Projects meta box
    $('.projects-meta-tabs .tab-button').on('click', function() {
        var tab = $(this).data('tab');
        $('.projects-meta-tabs .tab-button').removeClass('active');
        $(this).addClass('active');
        $('.projects-meta-tabs .tab-pane').hide();
        $('#tab-' + tab).show();
    });

    // Set default active tab
    $('.projects-meta-tabs .tab-button:first').addClass('active');
    $('.projects-meta-tabs .tab-pane:first').show();

    // Add Location
    $(document).on('click', '.add-location', function() {
        var locationCount = $('.location-item').length;
        var newLocation = '<div class="location-item">' +
            '<p><label>Place:</label><br><input type="text" name="project_locations[' + locationCount + '][place]" class="wide-input"></p>' +
            '<p><label>Date:</label><br><input type="text" name="project_locations[' + locationCount + '][date]" class="wide-input"></p>' +
            '<p><label>Description:</label><br><textarea name="project_locations[' + locationCount + '][description]" rows="5" class="wide-textarea"></textarea></p>' +
            '<p><label>CTA Link:</label><br><input type="url" name="project_locations[' + locationCount + '][cta_link]" class="wide-input"></p>' +
            '<p><label>CTA Title:</label><br><input type="text" name="project_locations[' + locationCount + '][cta_title]" class="wide-input"></p>' +
            '<button type="button" class="remove-location button">Remove Location</button>' +
            '</div>';
        $('.locations-container').append(newLocation);
    });

    // Remove Location
    $(document).on('click', '.remove-location', function() {
        $(this).closest('.location-item').remove();
        // Re-index
        $('.location-item').each(function(index) {
            $(this).find('input, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    name = name.replace(/project_locations\[\d+\]/, 'project_locations[' + index + ']');
                    $(this).attr('name', name);
                }
            });
        });
    });

    // Add Download
    $(document).on('click', '.add-download', function() {
        var downloadCount = $('.download-row').length;
        var newRow = '<tr class="download-row">' +
            '<td><input type="text" name="project_downloads[' + downloadCount + '][title]" class="wide-input"></td>' +
            '<td>' +
                '<div class="file-preview-container">' +
                    '<input type="hidden" name="project_downloads[' + downloadCount + '][file]" class="download-file-id">' +
                    '<button type="button" class="select-download-file button">Select File</button>' +
                '</div>' +
            '</td>' +
            '<td><button type="button" class="remove-download button">Remove</button></td>' +
            '</tr>';
        $('.downloads-table tbody').append(newRow);
    });

    // Remove Download
    $(document).on('click', '.remove-download', function() {
        $(this).closest('.download-row').remove();
        // Re-index
        $('.download-row').each(function(index) {
            $(this).find('input[name*="project_downloads"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Select Download File
    $(document).on('click', '.select-download-file', function(e) {
        e.preventDefault();
        var button = $(this);
        var container = button.closest('.file-preview-container');
        var input = container.find('.download-file-id');
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Use this file'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            input.val(attachment.id);
            var link = container.find('.file-link');
            if (link.length === 0) {
                container.prepend('<a href="' + attachment.url + '" target="_blank" class="file-link">' + attachment.filename + '</a> ');
            } else {
                link.attr('href', attachment.url).text(attachment.filename);
            }
            button.text('Change File');
        }).open();
    });

    // Add Gallery Image
    $(document).on('click', '.add-gallery-image', function(e) {
        e.preventDefault();
        var button = $(this);
        var container = button.siblings('.gallery-items');
        var custom_uploader = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add to Gallery'
            },
            multiple: true
        }).on('select', function() {
            var selection = custom_uploader.state().get('selection');
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                // Projects gallery stores Attachment IDs array, but let's check what Events does.
                // Events JS: value="' + attachment.url + '"
                // User Requirement: "store attachment IDs as an array".
                // So I must use attachment.id
                var newItem = '<div class="gallery-item">' +
                    '<input type="hidden" name="project_image_gallery[]" value="' + attachment.url + '">' +
                    '<img src="' + attachment.url + '" alt="Gallery Image" style="max-width: 100px; max-height: 100px;">' +
                    '<button type="button" class="remove-gallery-image button">Remove</button>' +
                    '</div>';
                container.append(newItem);
            });
        }).open();
    });

    // Remove Gallery Image
    $(document).on('click', '.remove-gallery-image', function() {
        $(this).closest('.gallery-item').remove();
    });

    // Sortable for Gallery
    if ($('.gallery-items').length) {
        $('.gallery-items').sortable({
            items: '.gallery-item',
            cursor: 'move',
            containment: 'parent',
            placeholder: 'sortable-placeholder'
        });
    }
});
