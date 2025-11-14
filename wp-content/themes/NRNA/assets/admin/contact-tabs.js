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
        if (e.target.classList.contains('add-item')) {
            e.preventDefault();
            const button = e.target;
            const repeater = button.getAttribute('data-repeater');
            const container = document.querySelector(`.repeater-container[data-repeater="${repeater}"]`);
            const itemCount = container.querySelectorAll('.repeater-item').length;

            const newItem = document.createElement('div');
            newItem.className = 'repeater-item';

            let fieldsHTML = '';

            if (repeater === 'hero_phone_numbers') {
                fieldsHTML = `
                    <p><label>Phone Number:</label><br><input type="text" name="${repeater}[${itemCount}]" class="wide-input"></p>
                `;
            } else if (repeater === 'information_descriptions') {
                fieldsHTML = `
                    <p><label>Description:</label><br><textarea name="${repeater}[${itemCount}]" rows="4" class="wide-textarea"></textarea></p>
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
