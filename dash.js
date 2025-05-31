// Global variables
let currentTable = '';
let currentEditId = null;

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardData();
    
    // Set up screen switching
    setupScreenSwitching();
    
    // Set up modal close buttons
    setupModalCloseButtons();
    
    // Set up form submissions
    setupFormSubmissions();
    
    // Set up add buttons
    setupAddButtons();
    
    // Set up search functionality
    setupSearchFunctionality();
    
    // Set up event listeners for dynamic elements
    setupDynamicEventListeners();
    
    // Set up add screen functionality
    setupAddScreen();
});

// Function to load dashboard data
function loadDashboardData() {
    // Users data
    fetch('api.php?table=users&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('total-users').textContent = data.length;
            
            // Show recent users (last 5)
            const recentUsers = data.slice(-5).reverse();
            const recentUsersHtml = recentUsers.map(user => `
                <tr>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td>${user.nationality}</td>
                    <td>
                        <button class="action-btn edit-btn" data-id="${user.id}" data-table="users">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" data-id="${user.id}" data-table="users">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
            document.getElementById('recent-users').innerHTML = recentUsersHtml;
        })
        .catch(error => {
            console.error('Error loading users:', error);
            document.getElementById('total-users').textContent = '0';
            document.getElementById('recent-users').innerHTML = '<tr><td colspan="5">Error loading users</td></tr>';
        });
    
    // Housing data
    fetch('api.php?table=housing_options&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('total-housing').textContent = data.length;
            
            // Show featured housing
            const featuredHousing = data.filter(item => item.is_featured).slice(0, 3);
            if (featuredHousing.length > 0) {
                const housingHtml = featuredHousing.map(item => `
                    <div class="property-item">
                        <img src="${item.image_url || 'default-housing.jpg'}" alt="${item.title}" class="property-image">
                        <div class="property-info">
                            <h4>${item.title}</h4>
                            <p>${item.type} - ${item.price} ${item.price_period}</p>
                            <div class="property-rating">
                                <i class="fas fa-bed"></i>
                                <span>${item.beds} beds</span>
                                <i class="fas fa-bath"></i>
                                <span>${item.baths} baths</span>
                            </div>
                        </div>
                    </div>
                `).join('');
                document.getElementById('featured-housing').innerHTML = housingHtml;
            }
        })
        .catch(error => {
            console.error('Error loading housing:', error);
            document.getElementById('total-housing').textContent = '0';
        });
    
    // Events data
    fetch('api.php?table=events&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('total-events').textContent = data.length;
            
            // Show upcoming events
            const upcomingEvents = data.filter(event => {
                return new Date(event.event_date) >= new Date();
            }).sort((a, b) => new Date(a.event_date) - new Date(b.event_date)).slice(0, 3);
            
            const upcomingEventsHtml = upcomingEvents.map(event => `
                <div class="property-item">
                    <img src="${event.image_url || 'default-event.jpg'}" alt="Event" class="property-image">
                    <div class="property-info">
                        <h4>${event.title}</h4>
                        <p>${new Date(event.event_date).toLocaleDateString()} at ${event.event_time || 'TBD'}</p>
                        <div class="property-rating">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${event.location}</span>
                        </div>
                    </div>
                </div>
            `).join('');
            document.getElementById('upcoming-events').innerHTML = upcomingEventsHtml || '<p>No upcoming events</p>';
        })
        .catch(error => {
            console.error('Error loading events:', error);
            document.getElementById('total-events').textContent = '0';
            document.getElementById('upcoming-events').innerHTML = '<p>Error loading events</p>';
        });
    
    // Groups data
    fetch('api.php?table=community_groups&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('total-groups').textContent = data.length;
            
            // Show active groups
            const activeGroups = data.slice(0, 3);
            const groupsHtml = activeGroups.map(group => `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-content">
                        <h4>${group.name}</h4>
                        <p>${group.description || 'No description available'}</p>
                        <div class="activity-time">${group.member_count || 0} members</div>
                    </div>
                </div>
            `).join('');
            document.getElementById('active-groups').innerHTML = groupsHtml || '<p>No active groups</p>';
        })
        .catch(error => {
            console.error('Error loading groups:', error);
            document.getElementById('total-groups').textContent = '0';
            document.getElementById('active-groups').innerHTML = '<p>Error loading groups</p>';
        });
}

// Function to set up screen switching
function setupScreenSwitching() {
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all menu items
            document.querySelectorAll('.menu-item').forEach(i => {
                i.classList.remove('active');
            });
            
            // Add active class to clicked menu item
            this.classList.add('active');
            
            // Hide all screens
            document.querySelectorAll('.screen').forEach(screen => {
                screen.classList.remove('active');
            });
            
            // Show the selected screen
            const screenId = this.getAttribute('data-screen') + '-screen';
            document.getElementById(screenId).classList.add('active');
            
            // Load data for the selected screen
            currentTable = screenId.replace('-screen', '');
            if (currentTable !== 'dashboard' && currentTable !== 'settings' && currentTable !== 'add') {
                loadTableData(currentTable);
            }
        });
    });
}

// Function to load table data
function loadTableData(table) {
    const loadingIndicator = document.getElementById(`${table}-loading`);
    const tableBody = document.getElementById(`${table}-table`);
    const errorElement = document.getElementById(`${table}-error`);
    
    if (loadingIndicator) loadingIndicator.style.display = 'block';
    if (tableBody) tableBody.innerHTML = '';
    if (errorElement) errorElement.style.display = 'none';
    
    fetch(`api.php?table=${table}&action=get`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            
            if (!tableBody) return;
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="no-data">No ${table.replace('_', ' ')} found</td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            
            switch(table) {
                case 'users':
                    html = data.map(user => `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.full_name}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.nationality}</td>
                            <td>${user.gender}</td>
                            <td>${new Date(user.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="action-btn edit-btn" data-id="${user.id}" data-table="users">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" data-id="${user.id}" data-table="users">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    break;
                    
                case 'housing_options':
                    html = data.map(housing => `
                        <tr>
                            <td>${housing.id}</td>
                            <td>${housing.title}</td>
                            <td>${housing.type}</td>
                            <td>${housing.price} ${housing.price_period}</td>
                            <td>${housing.beds}</td>
                            <td>${housing.baths}</td>
                            <td>${housing.area_sqft || 'N/A'} sqft</td>
                            <td>
                                <span class="status ${housing.is_available ? 'status-active' : 'status-inactive'}">
                                    ${housing.is_available ? 'Available' : 'Taken'}
                                </span>
                            </td>
                            <td>
                                <button class="action-btn edit-btn" data-id="${housing.id}" data-table="housing_options">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" data-id="${housing.id}" data-table="housing_options">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    break;
                    
                case 'events':
                    html = data.map(event => `
                        <tr>
                            <td>${event.id}</td>
                            <td>${event.title}</td>
                            <td>${event.description || 'No description'}</td>
                            <td>${new Date(event.event_date).toLocaleDateString()}</td>
                            <td>${event.event_time || 'TBD'}</td>
                            <td>${event.location}</td>
                            <td>${event.category}</td>
                            <td>
                                <span class="status ${new Date(event.event_date) >= new Date() ? 'status-active' : 'status-inactive'}">
                                    ${new Date(event.event_date) >= new Date() ? 'Upcoming' : 'Past'}
                                </span>
                            </td>
                            <td>
                                <button class="action-btn edit-btn" data-id="${event.id}" data-table="events">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" data-id="${event.id}" data-table="events">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    break;
                    
                case 'community_groups':
                    html = data.map(group => `
                        <tr>
                            <td>${group.id}</td>
                            <td>${group.name}</td>
                            <td>${group.description || 'No description'}</td>
                            <td>${group.category || 'General'}</td>
                            <td>${group.member_count || 0}</td>
                            <td>${new Date(group.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="action-btn edit-btn" data-id="${group.id}" data-table="community_groups">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" data-id="${group.id}" data-table="community_groups">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    break;
                    
                default:
                    // Default table rendering for other tables
                    if (data.length > 0) {
                        const columns = Object.keys(data[0]);
                        html = data.map(row => `
                            <tr>
                                ${columns.map(col => `<td>${row[col]}</td>`).join('')}
                                <td>
                                    <button class="action-btn edit-btn" data-id="${row.id}" data-table="${table}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="${row.id}" data-table="${table}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    }
            }
            
            tableBody.innerHTML = html;
            setupEditDeleteButtons();
        })
        .catch(error => {
            console.error(`Error loading ${table}:`, error);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (errorElement) {
                errorElement.textContent = `Error loading ${table.replace('_', ' ')}: ${error.message}`;
                errorElement.style.display = 'block';
            }
        });
}

// Function to set up modal close buttons
function setupModalCloseButtons() {
    document.querySelectorAll('.close-btn, .close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
            currentEditId = null;
        });
    });
}

// Function to set up form submissions
function setupFormSubmissions() {
    // User form
    document.getElementById('user-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            full_name: document.getElementById('user-full-name').value,
            email: document.getElementById('user-email').value,
            phone: document.getElementById('user-phone').value,
            gender: document.getElementById('user-gender').value,
            dob: document.getElementById('user-dob').value,
            passport_number: document.getElementById('user-passport').value,
            nationality: document.getElementById('user-nationality').value,
            address: document.getElementById('user-address').value,
            preference: document.getElementById('user-preference').value,
            pin_code: document.getElementById('user-pin').value,
            lat: document.getElementById('user-lat').value,
            lng: document.getElementById('user-lng').value,
            selfie_path: document.getElementById('user-selfie').files[0]?.name || ''
        };
        
        if (currentEditId) {
            formData.id = currentEditId;
            submitFormData('api.php?table=users&action=update', formData, 'User updated successfully!');
        } else {
            submitFormData('api.php?table=users&action=add', formData, 'User added successfully!');
        }
    });
    
    // Housing form
    document.getElementById('housing-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            title: document.getElementById('housing-title').value,
            type: document.getElementById('housing-type').value,
            price: document.getElementById('housing-price').value,
            price_period: document.getElementById('housing-price-period').value,
            beds: document.getElementById('housing-beds').value,
            baths: document.getElementById('housing-baths').value,
            area_sqft: document.getElementById('housing-area').value,
            features: document.getElementById('housing-features').value,
            contact_info: document.getElementById('housing-contact').value,
            image_url: document.getElementById('housing-image').value,
            is_available: document.getElementById('housing-available').value
        };
        
        if (currentEditId) {
            formData.id = currentEditId;
            submitFormData('api.php?table=housing_options&action=update', formData, 'Housing updated successfully!');
        } else {
            submitFormData('api.php?table=housing_options&action=add', formData, 'Housing added successfully!');
        }
    });
    
    // Event form
    document.getElementById('event-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            title: document.getElementById('event-title').value,
            description: document.getElementById('event-description').value,
            event_date: document.getElementById('event-date').value,
            event_time: document.getElementById('event-time').value,
            location: document.getElementById('event-location').value,
            category: document.getElementById('event-category').value,
            image_url: document.getElementById('event-image').value,
            is_recurring: document.getElementById('event-recurring').value,
            recurrence_pattern: document.getElementById('event-recurring').value === '1' ? document.getElementById('event-recurrence-pattern').value : null
        };
        
        if (currentEditId) {
            formData.id = currentEditId;
            submitFormData('api.php?table=events&action=update', formData, 'Event updated successfully!');
        } else {
            submitFormData('api.php?table=events&action=add', formData, 'Event added successfully!');
        }
    });
}

// Function to submit form data
function submitFormData(url, data, successMessage) {
    const submitBtn = document.querySelector(`#${data.id ? 'edit' : 'add'}-btn`);
    const originalBtnText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        alert(successMessage);
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
        currentEditId = null;
        
        // Reload the current table
        if (currentTable) {
            loadTableData(currentTable);
        }
        
        // Reload dashboard if on dashboard
        if (document.getElementById('dashboard-screen').classList.contains('active')) {
            loadDashboardData();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`Error saving data: ${error.message}`);
    })
    .finally(() => {
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    });
}

// Function to set up add buttons
function setupAddButtons() {
    // Handle all add buttons with data-type attribute
    document.querySelectorAll('[data-type]').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            
            // Switch to add screen
            document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
            document.querySelector('.menu-item[data-screen="add"]').classList.add('active');
            
            document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            document.getElementById('add-screen').classList.add('active');
            
            // Hide all forms
            document.querySelectorAll('.add-form').forEach(form => form.style.display = 'none');
            
            // Show the selected form
            document.getElementById(`${type}-form`).style.display = 'block';
            
            // Load any dynamic data needed for this form
            loadDynamicFormData(type);
        });
    });
}

// Function to set up edit/delete buttons
function setupEditDeleteButtons() {
    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const table = this.getAttribute('data-table');
            currentEditId = id;
            
            fetch(`api.php?table=${table}&action=get&id=${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.length > 0) {
                        const item = data[0];
                        
                        switch(table) {
                            case 'users':
                                document.getElementById('user-modal-title').textContent = 'Edit User';
                                document.getElementById('user-full-name').value = item.full_name;
                                document.getElementById('user-email').value = item.email;
                                document.getElementById('user-phone').value = item.phone;
                                document.getElementById('user-gender').value = item.gender;
                                document.getElementById('user-dob').value = item.dob;
                                document.getElementById('user-passport').value = item.passport_number;
                                document.getElementById('user-nationality').value = item.nationality;
                                document.getElementById('user-address').value = item.address;
                                document.getElementById('user-preference').value = item.preference;
                                document.getElementById('user-pin').value = item.pin_code;
                                document.getElementById('user-lat').value = item.lat;
                                document.getElementById('user-lng').value = item.lng;
                                document.getElementById('user-modal').style.display = 'flex';
                                break;
                                
                            case 'housing_options':
                                document.getElementById('housing-modal-title').textContent = 'Edit Housing';
                                document.getElementById('housing-title').value = item.title;
                                document.getElementById('housing-type').value = item.type;
                                document.getElementById('housing-price').value = item.price;
                                document.getElementById('housing-price-period').value = item.price_period;
                                document.getElementById('housing-beds').value = item.beds;
                                document.getElementById('housing-baths').value = item.baths;
                                document.getElementById('housing-area').value = item.area_sqft;
                                document.getElementById('housing-features').value = item.features;
                                document.getElementById('housing-contact').value = item.contact_info;
                                document.getElementById('housing-image').value = item.image_url;
                                document.getElementById('housing-available').value = item.is_available;
                                document.getElementById('housing-modal').style.display = 'flex';
                                break;
                                
                            case 'events':
                                document.getElementById('event-modal-title').textContent = 'Edit Event';
                                document.getElementById('event-title').value = item.title;
                                document.getElementById('event-description').value = item.description;
                                document.getElementById('event-date').value = item.event_date;
                                document.getElementById('event-time').value = item.event_time;
                                document.getElementById('event-location').value = item.location;
                                document.getElementById('event-category').value = item.category;
                                document.getElementById('event-image').value = item.image_url;
                                document.getElementById('event-recurring').value = item.is_recurring;
                                if (item.is_recurring) {
                                    document.getElementById('event-recurrence-group').style.display = 'block';
                                    document.getElementById('event-recurrence-pattern').value = item.recurrence_pattern;
                                }
                                document.getElementById('event-modal').style.display = 'flex';
                                break;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading item:', error);
                    alert('Error loading item data. Please try again.');
                });
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this item?')) {
                const id = this.getAttribute('data-id');
                const table = this.getAttribute('data-table');
                
                fetch(`api.php?table=${table}&action=delete&id=${id}`, {
                    method: 'POST'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Item deleted successfully!');
                        loadTableData(currentTable);
                        
                        // Reload dashboard if on dashboard
                        if (document.getElementById('dashboard-screen').classList.contains('active')) {
                            loadDashboardData();
                        }
                    } else {
                        throw new Error(data.error || 'Error deleting item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting item: ' + error.message);
                });
            }
        });
    });
}

// Function to set up search functionality
function setupSearchFunctionality() {
    document.querySelectorAll('.search-bar input').forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableId = this.id.replace('-search', '-table');
            const rows = document.querySelectorAll(`#${tableId} tr`);
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
}

// Function to set up dynamic event listeners
function setupDynamicEventListeners() {
    // Event recurring toggle
    document.getElementById('event-recurring').addEventListener('change', function() {
        document.getElementById('event-recurrence-group').style.display = this.value === '1' ? 'block' : 'none';
    });
}

// Function to set up add screen
function setupAddScreen() {
    // Handle add option clicks
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
            if (formType === 'user') {
                loadHousingUnitsForSelect();
            }
            if (formType === 'notification') {
                loadUsersForCheckboxes();
            }
        });
    });

    // Recipients selector toggle
    document.getElementById('notification-recipients').addEventListener('change', function() {
        const specificUsersGroup = document.getElementById('specific-users-group');
        specificUsersGroup.style.display = this.value === 'specific' ? 'block' : 'none';
    });

    // Form submission handlers
    document.getElementById('housing-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            title: document.getElementById('housing-title').value,
            type: document.getElementById('housing-type').value,
            price: document.getElementById('housing-price').value,
            price_period: document.getElementById('housing-price-period').value,
            beds: document.getElementById('housing-beds').value,
            baths: document.getElementById('housing-baths').value,
            area_sqft: document.getElementById('housing-area').value,
            features: document.getElementById('housing-features').value,
            contact_info: document.getElementById('housing-contact').value,
            image_url: document.getElementById('housing-image').value,
            is_available: document.getElementById('housing-available').value
        };
        
        submitFormData('api.php?table=housing_options&action=add', formData, 'Housing unit added successfully!');
    });

    document.getElementById('user-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            full_name: document.getElementById('user-full-name').value,
            email: document.getElementById('user-email').value,
            phone: document.getElementById('user-phone').value,
            gender: document.getElementById('user-gender').value,
            dob: document.getElementById('user-dob').value,
            passport_number: document.getElementById('user-passport').value,
            nationality: document.getElementById('user-nationality').value,
            address: document.getElementById('user-address').value,
            preference: document.getElementById('user-preference').value,
            pin_code: document.getElementById('user-pin').value,
            lat: document.getElementById('user-lat').value,
            lng: document.getElementById('user-lng').value,
            selfie_path: document.getElementById('user-selfie').files[0]?.name || ''
        };
        
        submitFormData('api.php?table=users&action=add', formData, 'User added successfully!');
    });

    document.getElementById('event-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            title: document.getElementById('event-title').value,
            description: document.getElementById('event-description').value,
            event_date: document.getElementById('event-date').value,
            event_time: document.getElementById('event-time').value,
            location: document.getElementById('event-location').value,
            category: document.getElementById('event-category').value,
            image_url: document.getElementById('event-image').value,
            is_recurring: document.getElementById('event-recurring').value,
            recurrence_pattern: document.getElementById('event-recurring').value === '1' ? 
                document.getElementById('event-recurrence-pattern').value : null
        };
        
        submitFormData('api.php?table=events&action=add', formData, 'Event added successfully!');
    });

    document.getElementById('notification-form-data').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            title: document.getElementById('notification-title').value,
            message: document.getElementById('notification-message').value,
            notification_type: document.getElementById('notification-type').value,
            status: 'draft'
        };
        
        if (document.getElementById('notification-recipients').value === 'specific') {
            const selectedUsers = [];
            document.querySelectorAll('#users-checkboxes input[type="checkbox"]:checked').forEach(checkbox => {
                selectedUsers.push(checkbox.value);
            });
            formData.recipients = selectedUsers.join(',');
        }
        
        submitFormData('api.php?table=notifications&action=add', formData, 'Notification created successfully!');
    });
}

// Function to load housing units for select
function loadHousingUnitsForSelect() {
    fetch('api.php?table=housing_options&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const select = document.getElementById('user-housing');
            if (!select) return;
            
            select.innerHTML = '<option value="">Select Housing Unit</option>';
            data.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit.id;
                option.textContent = `${unit.title} (${unit.type})`;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading housing units:', error);
        });
}

// Function to load users for checkboxes
function loadUsersForCheckboxes() {
    fetch('api.php?table=users&action=get')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('users-checkboxes');
            if (!container) return;
            
            container.innerHTML = '';
            data.forEach(user => {
                const div = document.createElement('div');
                div.className = 'checkbox-item';
                div.innerHTML = `
                    <input type="checkbox" id="user-${user.id}" value="${user.id}">
                    <label for="user-${user.id}">${user.full_name} (${user.email})</label>
                `;
                container.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Error loading users:', error);
        });
}

// Function to load dynamic form data
function loadDynamicFormData(type) {
    switch(type) {
        case 'user':
            loadHousingUnitsForSelect();
            break;
        case 'notification':
            loadUsersForCheckboxes();
            break;
    }
}

// Helper function to format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    return date.toLocaleDateString();
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Helper function to format price
function formatPrice(amount, period = '') {
    if (!amount) return 'N/A';
    return `$${parseFloat(amount).toFixed(2)}${period ? '/' + period : ''}`;
}

// Helper function to truncate text
function truncateText(text, maxLength = 50) {
    if (!text) return '';
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}