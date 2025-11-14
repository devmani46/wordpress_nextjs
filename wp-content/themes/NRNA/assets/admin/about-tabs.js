document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    function showTab(tabKey) {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.style.display = 'none');

        const activeButton = document.querySelector(`.tab-button[data-tab="${tabKey}"]`);
        const activePane = document.getElementById(`tab-${tabKey}`);

        if (activeButton && activePane) {
            activeButton.classList.add('active');
            activePane.style.display = 'block';
        }
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabKey = this.getAttribute('data-tab');
            showTab(tabKey);
        });
    });

    if (tabButtons.length > 0) {
        showTab(tabButtons[0].getAttribute('data-tab'));
    }

    // Function to reindex repeater items
    function reindexRepeater(container) {
        const items = container.querySelectorAll('.repeater-item');
        const repeater = container.getAttribute('data-repeater');
        items.forEach((item, index) => {
            const titleInput = item.querySelector('input[name*="[title]"]');
            const descTextarea = item.querySelector('textarea[name*="[description]"]');
            const imageInput = item.querySelector('input[name*="[image]"]');

            if (titleInput) titleInput.name = `${repeater}[${index}][title]`;
            if (descTextarea) descTextarea.name = `${repeater}[${index}][description]`;
            if (imageInput) imageInput.name = `${repeater}[${index}][image]`;
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('upload-image')) {
            e.preventDefault();
            const button = e.target;
            const container = button.closest('p');
            const imageIdInput = container.querySelector('.image-id');
            const imagePreview = container.querySelector('.image-preview');

            const mediaUploader = wp.media({
                title: 'Choose Image',
                button: { text: 'Choose Image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                imageIdInput.value = attachment.id;
                imagePreview.src = attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                imagePreview.classList.add('has-image');
            });

            mediaUploader.open();
        }

        if (e.target.classList.contains('add-item')) {
            e.preventDefault();
            const button = e.target;
            const repeater = button.getAttribute('data-repeater');
            if (repeater !== 'about_slider_items' && repeater !== 'who_we_are_slider_items') return; // Handle about_slider_items and who_we_are_slider_items

            const container = document.querySelector(`.repeater-container[data-repeater="${repeater}"]`);
            const itemCount = container.querySelectorAll('.repeater-item').length;

            const newItem = document.createElement('div');
            newItem.className = 'repeater-item';

            newItem.innerHTML = `
                <p><label>Title:</label><br><input type="text" name="${repeater}[${itemCount}][title]" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="${repeater}[${itemCount}][description]" rows="3" class="wide-textarea"></textarea></p>
                <p><label>Image:</label><br>
                <input type="hidden" name="${repeater}[${itemCount}][image]" class="image-id">
                <img src="" class="image-preview">
                <button type="button" class="upload-image button">Upload Image</button></p>
                <button type="button" class="remove-item button">Remove</button>
            `;

            container.appendChild(newItem);
        }

        if (e.target.classList.contains('remove-item')) {
            e.preventDefault();
            const item = e.target.closest('.repeater-item');
            const container = item.closest('.repeater-container');
            item.remove();
            reindexRepeater(container);
        }
    });
});
