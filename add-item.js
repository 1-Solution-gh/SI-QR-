// Add this to your existing script
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic form switching
    document.querySelectorAll('.add-option').forEach(option => {
        option.addEventListener('click', function() {
            const formType = this.getAttribute('data-type');
            
            // Hide all forms
            document.querySelectorAll('.add-form').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show selected form
            document.getElementById(`${formType}-form`).style.display = 'block';
            
            // Load dynamic data if needed
            if (formType === 'resident' || formType === 'qr') {
                loadHousingUnitsForSelect();
            }
            if (formType === 'notification') {
                loadHousingUnitsForCheckboxes();
            }
        });
    });

    // Recipients selector toggle
    document.getElementById('notification-recipients').addEventListener('change', function() {
        const specificUnitsGroup = document.getElementById('specific-units-group');
        specificUnitsGroup.style.display = this.value === 'specific' ? 'block' : 'none';
    });

    // Form submission handlers
    document.getElementById('housing-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            block: document.getElementById('housing-block').value,
            unit_number: document.getElementById('housing-unit').value,
            wing: document.getElementById('housing-wing').value,
            floor: document.getElementById('housing-floor').value,
            bedrooms: document.getElementById('housing-bedrooms').value,
            bathrooms: document.getElementById('housing-bathrooms').value,
            area_sq_m: document.getElementById('housing-area').value,
            status: document.getElementById('housing-status').value,
            image_path: document.getElementById('housing-image').files[0]?.name || null
        };
        
        submitFormData('/api/housing', formData, 'Housing unit added successfully!');
    });

    document.getElementById('resident-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            full_name: document.getElementById('resident-name').value,
            email: document.getElementById('resident-email').value,
            phone: document.getElementById('resident-phone').value,
            housing_unit_id: document.getElementById('resident-housing').value,
            move_in_date: document.getElementById('resident-movein').value,
            status: document.getElementById('resident-status').value,
            avatar_path: document.getElementById('resident-avatar').files[0]?.name || null
        };
        
        submitFormData('/api/residents', formData, 'Resident added successfully!');
    });

    // Similar handlers for other forms (event, qr, notification)
    // ...

    function submitFormData(url, data, successMessage) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            alert(successMessage);
            // Optionally redirect or reset form
            document.querySelector('form').reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving data. Please try again.');
        });
    }

    function loadHousingUnitsForSelect() {
        fetch('/api/housing')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('resident-housing');
                select.innerHTML = '';
                data.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = `${unit.block}, Unit ${unit.unit_number}`;
                    select.appendChild(option);
                });
                
                // Also populate QR target if needed
                const qrTarget = document.getElementById('qr-target');
                if (qrTarget) {
                    qrTarget.innerHTML = '<option value="">None</option>';
                    data.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = `${unit.block}, Unit ${unit.unit_number}`;
                        qrTarget.appendChild(option);
                    });
                }
            });
    }

    function loadHousingUnitsForCheckboxes() {
        fetch('/api/housing')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('housing-units-checkboxes');
                container.innerHTML = '';
                data.forEach(unit => {
                    const div = document.createElement('div');
                    div.className = 'checkbox-item';
                    div.innerHTML = `
                        <input type="checkbox" id="unit-${unit.id}" value="${unit.id}">
                        <label for="unit-${unit.id}">${unit.block}, Unit ${unit.unit_number}</label>
                    `;
                    container.appendChild(div);
                });
            });
    }
});