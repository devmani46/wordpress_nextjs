jQuery(document).ready(function($) {
    // Skills Repeater
    function addSkillField(value = '') {
        const skillField = `
            <div class="skill-field">
                <input type="text" name="team_skills[]" value="${value}" style="width:80%; margin-right:10px;" />
                <button type="button" class="remove-skill button">Remove</button>
            </div>
        `;
        $('#skills-container').append(skillField);
    }

    // Add skill button
    $('#add-skill').on('click', function() {
        addSkillField();
    });

    // Remove skill button
    $(document).on('click', '.remove-skill', function() {
        $(this).closest('.skill-field').remove();
    });

    // Badges Media Uploader
    function addBadgeField(imageUrl = '', imageId = '') {
        const badgeField = `
            <div class="badge-field">
                <input type="hidden" name="team_badges[]" value="${imageId}" />
                <img src="${imageUrl}" style="max-width:100px; max-height:100px; display:${imageUrl ? 'block' : 'none'};" class="badge-preview" />
                <input type="text" class="badge-url" value="${imageUrl}" readonly style="width:70%; margin-right:10px;" />
                <button type="button" class="upload-badge button">Upload</button>
                <button type="button" class="remove-badge button">Remove</button>
            </div>
        `;
        $('#badges-container').append(badgeField);
    }

    // Add badge button
    $('#add-badge').on('click', function() {
        addBadgeField();
    });

    // Remove badge button
    $(document).on('click', '.remove-badge', function() {
        $(this).closest('.badge-field').remove();
    });

    // Upload badge button
    $(document).on('click', '.upload-badge', function(e) {
        e.preventDefault();
        const button = $(this);
        const field = button.closest('.badge-field');
        const custom_uploader = wp.media({
            title: 'Select Badge Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            const attachment = custom_uploader.state().get('selection').first().toJSON();
            field.find('input[type="hidden"]').val(attachment.id);
            field.find('.badge-url').val(attachment.url);
            field.find('.badge-preview').attr('src', attachment.url).show();
        }).open();
    });

    // Initialize existing skills and badges
    const existingSkills = $('#skills-container').data('existing-skills') || [];
    existingSkills.forEach(skill => addSkillField(skill));

    const existingBadges = $('#badges-container').data('existing-badges') || [];
    existingBadges.forEach(badge => addBadgeField(badge.url, badge.id));
});
