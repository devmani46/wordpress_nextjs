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
                imagePreview.src = attachment.url;
                imagePreview.classList.add('has-image');
            });

            mediaUploader.open();
        }

        if (e.target.classList.contains('add-item')) {
            e.preventDefault();
            const button = e.target;
            const repeater = button.getAttribute('data-repeater');
            const container = document.querySelector(`.repeater-container[data-repeater="${repeater}"]`);
            const itemCount = container.querySelectorAll('.repeater-item').length;

            const newItem = document.createElement('div');
            newItem.className = 'repeater-item';

            let fieldsHTML = '';

            if (repeater === 'discount_hero_stats') {
                fieldsHTML = `
                    <p><label>Stat Title:</label><br><input type="text" name="${repeater}[${itemCount}][title]" class="wide-input"></p>
                    <p><label>Stat Description:</label><br><textarea name="${repeater}[${itemCount}][description]" rows="3" class="wide-textarea"></textarea></p>
                `;
            } else if (repeater === 'discount_how_banner') {
                fieldsHTML = `
                    <p><label>Label:</label><br><input type="text" name="${repeater}[${itemCount}][label]" class="wide-input"></p>
                    <p><label>Title:</label><br><input type="text" name="${repeater}[${itemCount}][title]" class="wide-input"></p>
                    <p><label>Description:</label><br><textarea name="${repeater}[${itemCount}][description]" rows="3" class="wide-textarea"></textarea></p>
                `;
            } else if (repeater === 'discount_partners') {
                fieldsHTML = `
                    <p><label>Category:</label><br><input type="text" name="${repeater}[${itemCount}][category]" class="wide-input"></p>
                    <p><label>Name:</label><br><input type="text" name="${repeater}[${itemCount}][name]" class="wide-input"></p>
                    <p><label>Description:</label><br><textarea name="${repeater}[${itemCount}][description]" rows="3" class="wide-textarea"></textarea></p>
                    <p><label>Offer Text:</label><br><input type="text" name="${repeater}[${itemCount}][offer_text]" class="wide-input"></p>
                    <p><label>Partner Type:</label><br>
                    <select name="${repeater}[${itemCount}][partner_type]">
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select></p>
                    <p><label>Photo:</label><br>
                    <input type="hidden" name="${repeater}[${itemCount}][photo]" class="image-id">
                    <img src="" class="image-preview">
                    <button type="button" class="upload-image button">Upload Image</button></p>
                    <p><label>CTA Link:</label><br><input type="url" name="${repeater}[${itemCount}][cta_link]" class="wide-input"></p>
                    <p><label>CTA Title:</label><br><input type="text" name="${repeater}[${itemCount}][cta_title]" class="wide-input"></p>
                `;
            } else if (repeater === 'discount_join_points') {
                fieldsHTML = `
                    <p><label>Title:</label><br><input type="text" name="${repeater}[${itemCount}][title]" class="wide-input"></p>
                `;
            } else if (repeater === 'discount_join_stats') {
                fieldsHTML = `
                    <p><label>Stat Title:</label><br><input type="text" name="${repeater}[${itemCount}][title]" class="wide-input"></p>
                    <p><label>Stat Description:</label><br><textarea name="${repeater}[${itemCount}][description]" rows="3" class="wide-textarea"></textarea></p>
                `;
            }

            newItem.innerHTML = fieldsHTML + '<button type="button" class="remove-item button">Remove</button>';
            container.appendChild(newItem);
        }

        if (e.target.classList.contains('remove-item')) {
            e.preventDefault();
            const item = e.target.closest('.repeater-item');
            item.remove();
        }
    });
});
