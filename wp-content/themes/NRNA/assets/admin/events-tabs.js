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

    // Add Date functionality
    $(document).on('click', '.add-date', function() {
        var container = $(this).prev('.repeater-container');
        var dateIndex = container.find('.date-item').length;
        var dateHtml = `
            <div class="date-item">
                <h4>Date ${dateIndex + 1}</h4>
                <p><label>Date:</label><br><input type="date" name="event_schedule_dates[${dateIndex}][date]" class="wide-input"></p>
                <div class="sessions-container" data-repeater="sessions">
                </div>
                <button type="button" class="add-session button" data-date-index="${dateIndex}">Add Session</button>
                <button type="button" class="remove-date button">Remove Date</button>
            </div>
        `;
        container.append(dateHtml);
    });

    // Remove Date functionality
    $(document).on('click', '.remove-date', function() {
        $(this).closest('.date-item').remove();
        // Re-index dates after removal
        $('.date-item').each(function(index) {
            $(this).find('h4').text('Date ' + (index + 1));
            $(this).find('input[name*="event_schedule_dates"]').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/event_schedule_dates\[\d+\]/, `event_schedule_dates[${index}]`);
                $(this).attr('name', name);
            });
            $(this).find('.add-session').attr('data-date-index', index);
        });
    });

    // Add Session functionality
    $(document).on('click', '.add-session', function() {
        var dateIndex = $(this).data('date-index');
        var sessionsContainer = $(this).prev('.sessions-container');
        var sessionIndex = sessionsContainer.find('.session-item').length;
        var sessionHtml = `
            <div class="session-item">
                <h5>Session ${sessionIndex + 1}</h5>
                <p><label>Start Time:</label><br><input type="time" name="event_schedule_dates[${dateIndex}][sessions][${sessionIndex}][start_time]" class="wide-input"></p>
                <p><label>End Time:</label><br><input type="time" name="event_schedule_dates[${dateIndex}][sessions][${sessionIndex}][end_time]" class="wide-input"></p>
                <p><label>Title:</label><br><input type="text" name="event_schedule_dates[${dateIndex}][sessions][${sessionIndex}][title]" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="event_schedule_dates[${dateIndex}][sessions][${sessionIndex}][description]" rows="3" class="wide-textarea"></textarea></p>
                <button type="button" class="remove-session button">Remove Session</button>
            </div>
        `;
        sessionsContainer.append(sessionHtml);
    });

    // Remove Session functionality
    $(document).on('click', '.remove-session', function() {
        var sessionsContainer = $(this).closest('.sessions-container');
        $(this).closest('.session-item').remove();
        // Re-index sessions after removal
        sessionsContainer.find('.session-item').each(function(index) {
            $(this).find('h5').text('Session ' + (index + 1));
            $(this).find('input, textarea').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/sessions\[\d+\]/, `sessions[${index}]`);
                $(this).attr('name', name);
            });
        });
    });
});
