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
});
