jQuery(document).ready(function($) {
    // Tab switching for Events meta box
    $('.events-meta-tabs .tab-button').on('click', function() {
        var tab = $(this).data('tab');
        $('.events-meta-tabs .tab-button').removeClass('active');
        $(this).addClass('active');
        $('.events-meta-tabs .tab-pane').hide();
        $('#tab-' + tab).show();
    });

    // Set default active tab
    $('.events-meta-tabs .tab-button:first').addClass('active');
    $('.events-meta-tabs .tab-pane:first').show();

    // Add sponsorship row
    $(document).on('click', '.add-sponsorship', function() {
        var rowCount = $('.sponsorship-row').length;
        var newRow = '<tr class="sponsorship-row">' +
            '<td><input type="text" name="event_sponsorships[' + rowCount + '][category]" class="wide-input"></td>' +
            '<td><input type="text" name="event_sponsorships[' + rowCount + '][amount]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-sponsorship button">Remove</button></td>' +
            '</tr>';
        $('.sponsorship-table tbody').append(newRow);
    });

    // Remove sponsorship row
    $(document).on('click', '.remove-sponsorship', function() {
        $(this).closest('.sponsorship-row').remove();
        // Re-index the remaining rows
        $('.sponsorship-row').each(function(index) {
            $(this).find('input[name*="event_sponsorships"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add venue detail
    $(document).on('click', '.add-venue-detail', function() {
        var detailCount = $('.venue-detail-item').length;
        var newDetail = '<div class="venue-detail-item">' +
            '<p><label>Title:</label><br><input type="text" name="event_venue_details[' + detailCount + '][title]" class="wide-input"></p>' +
            '<p><label>Description:</label><br><textarea name="event_venue_details[' + detailCount + '][description]" rows="5" class="wide-textarea"></textarea></p>' +
            '<button type="button" class="remove-venue-detail button">Remove Detail</button>' +
            '</div>';
        $('.venue-details-list').append(newDetail);
    });

    // Remove venue detail
    $(document).on('click', '.remove-venue-detail', function() {
        $(this).closest('.venue-detail-item').remove();
        // Re-index the remaining details
        $('.venue-detail-item').each(function(index) {
            $(this).find('input[name*="event_venue_details"], textarea[name*="event_venue_details"]').each(function() {
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
            '<td><input type="text" name="event_organizing_committee[' + memberCount + '][photo]" placeholder="Image URL" class="wide-input"></td>' +
            '<td><input type="text" name="event_organizing_committee[' + memberCount + '][name]" class="wide-input"></td>' +
            '<td><input type="text" name="event_organizing_committee[' + memberCount + '][role]" class="wide-input"></td>' +
            '<td><input type="text" name="event_organizing_committee[' + memberCount + '][service]" class="wide-input"></td>' +
            '<td><input type="text" name="event_organizing_committee[' + memberCount + '][country]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-committee button">Remove</button></td>' +
            '</tr>';
        $('.committee-table tbody').append(newRow);
    });

    // Remove committee member
    $(document).on('click', '.remove-committee', function() {
        $(this).closest('.committee-row').remove();
        // Re-index the remaining rows
        $('.committee-row').each(function(index) {
            $(this).find('input[name*="event_organizing_committee"]').each(function() {
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
            '<td><input type="text" name="event_sponsors[' + sponsorCount + '][photo]" placeholder="Image URL" class="wide-input"></td>' +
            '<td><input type="text" name="event_sponsors[' + sponsorCount + '][name]" class="wide-input"></td>' +
            '<td><input type="text" name="event_sponsors[' + sponsorCount + '][role]" class="wide-input"></td>' +
            '<td><input type="text" name="event_sponsors[' + sponsorCount + '][service]" class="wide-input"></td>' +
            '<td><input type="text" name="event_sponsors[' + sponsorCount + '][country]" class="wide-input"></td>' +
            '<td><button type="button" class="remove-sponsor button">Remove</button></td>' +
            '</tr>';
        $('.sponsors-table tbody').append(newRow);
    });

    // Remove sponsor
    $(document).on('click', '.remove-sponsor', function() {
        $(this).closest('.sponsor-row').remove();
        // Re-index the remaining rows
        $('.sponsor-row').each(function(index) {
            $(this).find('input[name*="event_sponsors"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Add partner
    $(document).on('click', '.add-partner', function() {
        var category = $(this).data('category');
        var partnerCount = $('.partner-item').length;
        var categoryLabel = category.charAt(0).toUpperCase() + category.slice(1);
        var newPartner = '<div class="partner-item">' +
            '<p><label>Logo URL:</label><br><input type="text" name="event_partners[' + partnerCount + '][logo]" class="wide-input"></p>' +
            '<p><label>Name:</label><br><input type="text" name="event_partners[' + partnerCount + '][name]" class="wide-input"></p>' +
            '<input type="hidden" name="event_partners[' + partnerCount + '][category]" value="' + category + '">' +
            '<button type="button" class="remove-partner button">Remove ' + categoryLabel + ' Partner</button>' +
            '</div>';
        $('.partners-section[data-category="' + category + '"]').append(newPartner);
    });

    // Remove partner
    $(document).on('click', '.remove-partner', function() {
        $(this).closest('.partner-item').remove();
        // Re-index the remaining partners
        $('.partner-item').each(function(index) {
            $(this).find('input[name*="event_partners"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });
});
