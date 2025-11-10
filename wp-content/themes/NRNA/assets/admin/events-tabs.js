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
});
