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
            '<td>' +
                '<input type="hidden" name="event_organizing_committee[' + memberCount + '][photo]" class="committee-photo-url">' +
                '<div class="image-preview-container">' +
                    '<img src="" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
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
            '<td>' +
                '<input type="hidden" name="event_sponsors[' + sponsorCount + '][photo]" class="committee-photo-url">' +
                '<div class="image-preview-container">' +
                    '<img src="" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
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
        var partnerCount = $('.partner-row').length;
        var categoryLabel = category.charAt(0).toUpperCase() + category.slice(1);
        var newRow = '<tr class="partner-row">' +
            '<td>' +
                '<input type="hidden" name="event_partners[' + partnerCount + '][logo]" class="committee-photo-url">' +
                '<div class="image-preview-container">' +
                    '<img src="" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; display: none;">' +
                    '<button type="button" class="select-image button">Select Image</button>' +
                '</div>' +
            '</td>' +
            '<td><input type="text" name="event_partners[' + partnerCount + '][name]" class="wide-input"></td>' +
            '<td>' +
                '<input type="hidden" name="event_partners[' + partnerCount + '][category]" value="' + category + '">' +
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
            $(this).find('input[name*="event_partners"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });

    // Image selection for committee members
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

    // Add Date
    $(document).on('click', '.add-date', function() {
        var dateCount = $('.date-item').length;
        var newDate = '<div class="date-item">' +
            '<h4>Date ' + (dateCount + 1) + '</h4>' +
            '<p><label>Date:</label><br><input type="date" name="event_schedule_dates[' + dateCount + '][date]" class="wide-input"></p>' +
            '<div class="sessions-container" data-repeater="sessions"></div>' +
            '<button type="button" class="add-session button" data-date-index="' + dateCount + '">Add Session</button>' +
            '<button type="button" class="remove-date button">Remove Date</button>' +
            '</div>';
        $('.repeater-container[data-repeater="event_schedule_dates"]').append(newDate);
    });

    // Remove Date
    $(document).on('click', '.remove-date', function() {
        $(this).closest('.date-item').remove();
        // Re-index dates
        $('.date-item').each(function(index) {
            $(this).find('h4').first().text('Date ' + (index + 1));
            $(this).find('.add-session').attr('data-date-index', index);
            
            // Update all inputs
            $(this).find('input, textarea, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    // Update array-based names: event_schedule_dates[OLD]...
                    if (name.match(/event_schedule_dates\[\d+\]/)) {
                        name = name.replace(/event_schedule_dates\[\d+\]/, 'event_schedule_dates[' + index + ']');
                        $(this).attr('name', name);
                    }
                    // Update wp_editor names: event_schedule_dates_OLD_...
                    if (name.match(/^event_schedule_dates_\d+_/)) {
                        name = name.replace(/^event_schedule_dates_\d+_/, 'event_schedule_dates_' + index + '_');
                        $(this).attr('name', name);
                    }
                }
            });
        });
    });

    // Add Session
    $(document).on('click', '.add-session', function() {
        var dateIndex = $(this).closest('.date-item').index();
        var container = $(this).siblings('.sessions-container');
        var sessionCount = container.find('.session-item').length;
        
        var newSession = '<div class="session-item">' +
            '<h5>Session ' + (sessionCount + 1) + '</h5>' +
            '<p><label>Start Time:</label><br><input type="time" name="event_schedule_dates[' + dateIndex + '][sessions][' + sessionCount + '][start_time]" class="wide-input"></p>' +
            '<p><label>End Time:</label><br><input type="time" name="event_schedule_dates[' + dateIndex + '][sessions][' + sessionCount + '][end_time]" class="wide-input"></p>' +
            '<p><label>Title:</label><br><input type="text" name="event_schedule_dates[' + dateIndex + '][sessions][' + sessionCount + '][title]" class="wide-input"></p>' +
            '<p><label>Description:</label><br><textarea name="event_schedule_dates[' + dateIndex + '][sessions][' + sessionCount + '][description]" rows="5" class="wide-textarea"></textarea></p>' +
            '<button type="button" class="remove-session button">Remove Session</button>' +
            '</div>';
        container.append(newSession);
    });

    // Remove Session
    $(document).on('click', '.remove-session', function() {
        var container = $(this).closest('.sessions-container');
        $(this).closest('.session-item').remove();
        
        // Re-index sessions within this date
        container.find('.session-item').each(function(index) {
            $(this).find('h5').text('Session ' + (index + 1));
            $(this).find('input, textarea, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    // Update array-based names: ...[sessions][OLD]...
                    if (name.match(/\[sessions\]\[\d+\]/)) {
                        name = name.replace(/\[sessions\]\[\d+\]/, '[sessions][' + index + ']');
                        $(this).attr('name', name);
                    }
                    // Update wp_editor names: ..._sessions_OLD_description
                    if (name.match(/_sessions_\d+_description$/)) {
                        name = name.replace(/_sessions_\d+_description$/, '_sessions_' + index + '_description');
                        $(this).attr('name', name);
                    }
                }
            });
        });
    });
});
