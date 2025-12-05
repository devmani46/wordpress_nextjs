jQuery(document).ready(function($) {
    // Tab switching for Regional Meetings meta box
    $('.regional-meetings-meta-tabs .tab-button').on('click', function() {
        var tab = $(this).data('tab');
        $('.regional-meetings-meta-tabs .tab-button').removeClass('active');
        $(this).addClass('active');
        $('.regional-meetings-meta-tabs .tab-pane').hide();
        $('#tab-' + tab).show();
    });

    // Set default active tab
    $('.regional-meetings-meta-tabs .tab-button:first').addClass('active');
    $('.regional-meetings-meta-tabs .tab-pane:first').show();

    // Add sponsorship row
    $(document).on('click', '.add-sponsorship', function() {
        var rowCount = $('.sponsorship-row').length;
        var newRow = '<tr class="sponsorship-row">' +
            '<td><input type="text" name="rm_sponsorships[' + rowCount + '][category]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_sponsorships[' + rowCount + '][amount]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-sponsorship button">Remove</button></td>' +
            '</tr>';
        $('.sponsorship-table tbody').append(newRow);
    });

    // Remove sponsorship row
    $(document).on('click', '.remove-sponsorship', function() {
        $(this).closest('.sponsorship-row').remove();
        // Re-index the remaining rows
        $('.sponsorship-row').each(function(index) {
            $(this).find('input[name*="rm_sponsorships"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add committee member
    $(document).on('click', '.add-committee', function() {
        var memberCount = $('.committee-row').length;
        var newRow = '<tr class="committee-row">' +
            '<td>' +
                '<div class="image-preview-container">' +
                    '<input type="hidden" name="rm_organizing_committee[' + memberCount + '][photo]" class="committee-photo-url">' +
                    '<img src="" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
            '<td><input type="text" name="rm_organizing_committee[' + memberCount + '][name]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_organizing_committee[' + memberCount + '][role]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_organizing_committee[' + memberCount + '][service]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_organizing_committee[' + memberCount + '][country]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-committee button">Remove</button></td>' +
            '</tr>';
        $('.committee-table tbody').append(newRow);
    });

    // Remove committee member
    $(document).on('click', '.remove-committee', function() {
        $(this).closest('.committee-row').remove();
        // Re-index the remaining rows
        $('.committee-row').each(function(index) {
            $(this).find('input[name*="rm_organizing_committee"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add sponsor
    $(document).on('click', '.add-sponsor', function() {
        var sponsorCount = $('.sponsor-row').length;
        var newRow = '<tr class="sponsor-row">' +
            '<td>' +
                '<div class="image-preview-container">' +
                    '<input type="hidden" name="rm_sponsors[' + sponsorCount + '][photo]" class="committee-photo-url">' +
                    '<img src="" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
            '<td><input type="text" name="rm_sponsors[' + sponsorCount + '][name]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_sponsors[' + sponsorCount + '][role]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_sponsors[' + sponsorCount + '][service]" class="wide-input"></td>' +
            '<td><input type="text" name="rm_sponsors[' + sponsorCount + '][country]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-sponsor button">Remove</button></td>' +
            '</tr>';
        $('.sponsors-table tbody').append(newRow);
    });

    // Remove sponsor
    $(document).on('click', '.remove-sponsor', function() {
        $(this).closest('.sponsor-row').remove();
        // Re-index the remaining rows
        $('.sponsor-row').each(function(index) {
            $(this).find('input[name*="rm_sponsors"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add partner
    $(document).on('click', '.add-partner', function() {
        var category = $(this).data('category');
        var partnerCount = $('.partner-row').length;
        var newRow = '<tr class="partner-row">' +
            '<td>' +
                '<div class="image-preview-container">' +
                    '<input type="hidden" name="rm_partners[' + partnerCount + '][logo]" class="committee-photo-url">' +
                    '<img src="" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
            '<td><input type="text" name="rm_partners[' + partnerCount + '][name]" class="wide-input"></td>' +
            '<td>' +
                '<input type="hidden" name="rm_partners[' + partnerCount + '][category]" value="' + category + '">' +
                '<button type="button" class="remove-partner button">Remove</button>' +
            '</td>' +
            '</tr>';
        $('.partners-section[data-category="' + category + '"] tbody').append(newRow);
    });

    // Remove partner
    $(document).on('click', '.remove-partner', function() {
        $(this).closest('.partner-row').remove();
        // Re-index the remaining partners
        $('.partner-row').each(function(index) {
            $(this).find('input[name*="rm_partners"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
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
                var newItem = '<div class="gallery-item">' +
                    '<input type="hidden" name="rm_image_gallery[]" value="' + attachment.url + '">' +
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

    // Add Video Link
    $(document).on('click', '.add-video-link', function() {
        var container = $(this).siblings('.video-items');
        var newItem = '<div class="video-item">' +
            '<p><label>YouTube URL:</label><br>' +
            '<input type="url" name="rm_video_gallery[]" class="wide-input">' +
            '<button type="button" class="remove-video-link button">Remove</button>' +
            '</p>' +
            '</div>';
        container.append(newItem);
    });

    // Remove Video Link
    $(document).on('click', '.remove-video-link', function() {
        $(this).closest('.video-item').remove();
    });

    // Image selection for committee members/sponsors/partners
    $(document).on('click', '.select-image', function(e) {
        e.preventDefault();
        var button = $(this);
        var container = button.closest('.image-preview-container');
        var input = container.find('.committee-photo-url');
        var preview = container.find('.committee-photo-preview');
        var custom_uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.attr('src', attachment.url).show();
            button.text('Change Image');
        }).open();
    });
});
