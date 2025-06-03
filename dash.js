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
            const featuredHousing = data.filter(item => item.is_available).slice(0, 3);
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
            currentTable = this.getAttribute('data-screen');
            if (currentTable !== 'dashboard' && currentTable !== 'settings') {
                loadTableData(currentTable);
            }
        });
    });
}

// Function to load table data
function loadTableData(table) {
    const tableBody = document.getElementById(`${table}-table`);
    const errorElement = document.getElementById(`${table}-error`);
    
    if (tableBody) tableBody.innerHTML = '<tr><td colspan="10" class="loading">Loading...</td></tr>';
    if (errorElement) errorElement.style.display = 'none';
    
    fetch(`api.php?table=${table}&action=get`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
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
            
            // Generic table rendering for all tables
            if (data.length > 0) {
                const columns = Object.keys(data[0]);
                html = data.map(row => `
                    <tr>
                        ${columns.map(col => `<td>${formatCellValue(row[col], col)}</td>`).join('')}
                        <td>
                            <button class="action-btn edit-btn" data-id="${row.id}" data-table="${table}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" data-id="${row.id}" data-table="${table}">
                                <i class="fas fa-trash" style="color: red;"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
            
            tableBody.innerHTML = html;
            setupEditDeleteButtons();
        })
        .catch(error => {
            console.error(`Error loading ${table}:`, error);
            if (tableBody) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="error">Error loading ${table.replace('_', ' ')}: ${error.message}</td>
                    </tr>
                `;
            }
            if (errorElement) {
                errorElement.textContent = `Error loading ${table.replace('_', ' ')}: ${error.message}`;
                errorElement.style.display = 'block';
            }
        });
}

// Helper function to format cell values
function formatCellValue(value, columnName) {
    if (value === null || value === undefined) return 'N/A';
    
    // Format dates
    if (columnName.includes('date') || columnName.includes('created_at') || columnName.includes('posted_date')) {
        return formatDate(value);
    }
    
    // Format boolean values
    if (typeof value === 'boolean' || (typeof value === 'number' && (value === 0 || value === 1))) {
        const boolValue = typeof value === 'number' ? Boolean(value) : value;
        return boolValue ? 
            '<span class="status status-active">Yes</span>' : 
            '<span class="status status-inactive">No</span>';
    }
    
    // Format JSON objects
    if (typeof value === 'object') {
        try {
            return JSON.stringify(value);
        } catch (e) {
            return value;
        }
    }
    
    // Truncate long text
    if (typeof value === 'string' && value.length > 50) {
        return truncateText(value, 50);
    }
    
    return value;
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
    // Add form submission handlers for all modal forms
    document.querySelectorAll('.modal-content form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formId = this.id;
            const table = formId.replace('-form', '');
            
            // Collect form data
            const formData = {};
            const inputs = this.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    formData[input.name] = input.checked;
                } else if (input.type === 'file') {
                    // Handle file uploads if needed
                    if (input.files.length > 0) {
                        formData[input.id.replace(`${table}-`, '')] = input.files[0].name;
                    }
                } else if (input.type !== 'button' && input.type !== 'submit') {
                    formData[input.id.replace(`${table}-`, '')] = input.value;
                }
            });
            
            // If editing, add the ID
            if (currentEditId) {
                formData.id = currentEditId;
                submitFormData(`api.php?table=${table}&action=update`, formData, `${table.replace('_', ' ')} updated successfully!`);
            } else {
                submitFormData(`api.php?table=${table}&action=add`, formData, `${table.replace('_', ' ')} added successfully!`);
            }
        });
    });
}

// Function to submit form data
function submitFormData(url, data, successMessage) {
    const submitBtn = document.querySelector(`#${data.id ? 'edit' : 'add'}-btn`);
    const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
    
    // Show loading state if submit button exists
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;
    }
    
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
        if (submitBtn) {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
}

// Function to set up add buttons
function setupAddButtons() {
    document.querySelectorAll('[data-type]').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            currentEditId = null;
            
            // Reset and show the appropriate modal
            const modal = document.getElementById(`${type}-modal`);
            if (modal) {
                // Reset form
                const form = modal.querySelector('form');
                if (form) form.reset();
                
                // Update title
                const title = modal.querySelector('h3');
                if (title) title.textContent = `Add New ${type.replace('_', ' ')}`;
                
                // Show modal
                modal.style.display = 'flex';
                
                // Load any dynamic data needed for this form
                if (type === 'notifications') {
                    loadUsersForCheckboxes();
                }
            }
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
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const modal = document.getElementById(`${table}-modal`);
                        if (modal) {
                            // Update title
                            const title = modal.querySelector('h3');
                            if (title) title.textContent = `Edit ${table.replace('_', ' ')}`;
                            
                            // Fill form with data
                            const form = modal.querySelector('form');
                            for (const key in data) {
                                const input = form.querySelector(`#${table}-${key}`);
                                if (input) {
                                    if (input.type === 'checkbox') {
                                        input.checked = Boolean(data[key]);
                                    } else {
                                        input.value = data[key];
                                    }
                                }
                            }
                            
                            // Show modal
                            modal.style.display = 'flex';
                            
                            // Handle special cases
                            if (table === 'events' && data.is_recurring) {
                                document.getElementById('events-recurrence-group').style.display = 'block';
                            }
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item deleted successfully!');
                        loadTableData(currentTable);
                        
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
    document.getElementById('events-is_recurring')?.addEventListener('change', function() {
        document.getElementById('events-recurrence-group').style.display = this.value === '1' ? 'block' : 'none';
    });
    
    // Notification recipients toggle
    document.getElementById('notifications-recipients')?.addEventListener('change', function() {
        document.getElementById('specific-users-group').style.display = this.value === 'specific' ? 'block' : 'none';
    });
}

// Function to load users for checkboxes in notifications form
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