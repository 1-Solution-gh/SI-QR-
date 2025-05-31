// Global variables
let currentCommunityId = null;

// Document ready
document.addEventListener('DOMContentLoaded', function() {
  // Load groups and threads
  loadCommunityGroups();
  
  // Set up event listeners
  document.getElementById('newGroupBtn').addEventListener('click', showNewGroupModal);
  document.getElementById('newThreadBtn').addEventListener('click', showThreadForm);
  document.getElementById('cancelThreadBtn').addEventListener('click', hideThreadForm);
  document.getElementById('threadForm').addEventListener('submit', submitThreadForm);
  
  // Filter buttons
  document.querySelectorAll('.filter').forEach(button => {
    button.addEventListener('click', function() {
      document.querySelectorAll('.filter').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      loadCommunityGroups(this.textContent.trim());
    });
  });
  
  // Media upload preview
  document.getElementById('mediaUpload').addEventListener('change', function(e) {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
      const file = this.files[0];
      const isVideo = file.type.includes('video');
      
      if (isVideo) {
        // Validate video duration (15s max)
        validateVideoDuration(file).then(valid => {
          if (valid) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.controls = true;
            video.style.maxWidth = '100%';
            preview.appendChild(video);
          } else {
            alert('Video must be 15 seconds or shorter');
            this.value = '';
          }
        });
      } else {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = '100%';
        preview.appendChild(img);
      }
    }
  });
});

// Load community groups from database
function loadCommunityGroups(filter = 'All') {
  fetch('api/get_groups.php?filter=' + encodeURIComponent(filter))
    .then(response => response.json())
    .then(groups => {
      const container = document.getElementById('groupsList');
      container.innerHTML = '';
      
      if (groups.length === 0) {
        container.innerHTML = '<p>No groups found</p>';
        return;
      }
      
      groups.forEach(group => {
        const groupCard = document.createElement('div');
        groupCard.className = 'group-card';
        groupCard.innerHTML = `
          <div class="group-avatar">${getGroupAvatar(group.category)}</div>
          <div class="group-info">
            <h3>${group.name}</h3>
            <p>👋 ${group.member_count} members • ${group.meeting_schedule || 'Regular meetups'}</p>
            <div class="group-tags">
              <span>${group.category}</span>
              ${group.tags ? JSON.parse(group.tags).map(tag => `<span>${tag}</span>`).join('') : ''}
            </div>
          </div>
          <button class="general-btn join-btn" data-group-id="${group.id}">
            ${group.is_member ? 'Joined' : 'Join'}
          </button>
        `;
        
        container.appendChild(groupCard);
        
        // Add event listener to join button
        const joinBtn = groupCard.querySelector('.join-btn');
        if (joinBtn) {
          joinBtn.addEventListener('click', function() {
            joinCommunityGroup(this.getAttribute('data-group-id'));
          });
        }
      });
      
      // If a community is selected, load its threads
      if (currentCommunityId) {
        loadCommunityThreads(currentCommunityId);
      }
    })
    .catch(error => {
      console.error('Error loading groups:', error);
    });
}

// Load threads for a specific community
function loadCommunityThreads(communityId) {
  fetch('api/get_threads.php?community_id=' + communityId)
    .then(response => response.json())
    .then(threads => {
      const container = document.getElementById('threadsList');
      container.innerHTML = '';
      
      if (threads.length === 0) {
        container.innerHTML = '<p>No threads yet. Start the conversation!</p>';
        return;
      }
      
      threads.forEach(thread => {
        const threadElement = document.createElement('div');
        threadElement.className = 'thread-item';
        threadElement.innerHTML = `
          <div class="thread-user">
            <img src="${thread.user_avatar || 'default_avatar.png'}" alt="${thread.user_name}">
            <span>${thread.user_name}</span>
            <small>${formatTimeAgo(thread.created_at)}</small>
          </div>
          <div class="thread-content">${thread.content}</div>
          ${thread.media_url ? `
            <div class="thread-media">
              ${thread.media_type === 'video' ? 
                `<video controls><source src="${thread.media_url}" type="video/mp4"></video>` : 
                `<img src="${thread.media_url}" alt="Thread media">`}
            </div>
          ` : ''}
        `;
        container.appendChild(threadElement);
      });
    })
    .catch(error => {
      console.error('Error loading threads:', error);
    });
}

// Show thread form
function showThreadForm() {
  if (!currentCommunityId) {
    alert('Please select a community first');
    return;
  }
  
  document.getElementById('threadFormContainer').style.display = 'block';
  document.getElementById('threadCommunityId').value = currentCommunityId;
  document.getElementById('newThreadBtn').style.display = 'none';
}

// Hide thread form
function hideThreadForm() {
  document.getElementById('threadFormContainer').style.display = 'none';
  document.getElementById('threadForm').reset();
  document.getElementById('mediaPreview').innerHTML = '';
  document.getElementById('newThreadBtn').style.display = 'block';
}

// Submit thread form
function submitThreadForm(e) {
  e.preventDefault();
  
  const form = e.target;
  const formData = new FormData(form);
  
  fetch('api/create_thread.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      hideThreadForm();
      loadCommunityThreads(currentCommunityId);
    } else {
      alert('Error: ' + (data.message || 'Failed to create thread'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to create thread');
  });
}

// Join a community group
function joinCommunityGroup(groupId) {
  fetch('api/join_group.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ group_id: groupId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Joined successfully!');
      loadCommunityGroups();
      currentCommunityId = groupId;
      loadCommunityThreads(groupId);
    } else {
      alert('Error: ' + (data.message || 'Failed to join group'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to join group');
  });
}

// Show new group modal
function showNewGroupModal() {
  // You can implement a modal similar to the thread form
  // This would show a form to create a new group
  // On submission, it would send to api/create_group.php
  // The backend would set status to 'pending' in community_group_approvals
  
  // Example implementation:
  const modalHtml = `
    <div class="modal-overlay" id="newGroupModal">
      <div class="modal-content">
        <h3>Create New Group</h3>
        <form id="newGroupForm">
          <div class="form-group">
            <label>Group Name</label>
            <input type="text" name="name" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" required></textarea>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category" required>
              <option value="family">Family</option>
              <option value="arts">Arts</option>
              <option value="sports">Sports</option>
              <option value="learning">Learning</option>
              <option value="social">Social</option>
              <option value="news">News</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label>Meeting Schedule</label>
            <input type="text" name="meeting_schedule" placeholder="e.g., Weekly on Fridays">
          </div>
          <div class="form-group">
            <label>Tags (comma separated)</label>
            <input type="text" name="tags" placeholder="e.g., parenting, support">
          </div>
          <div class="form-actions">
            <button type="submit" class="general-btn">Submit</button>
            <button type="button" class="general-btn cancel-btn" id="cancelGroupBtn">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHtml);
  
  document.getElementById('newGroupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('api/create_group.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Your group has been submitted for review. You will be notified when approved.');
        document.getElementById('newGroupModal').remove();
      } else {
        alert('Error: ' + (data.message || 'Failed to create group'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to create group');
    });
  });
  
  document.getElementById('cancelGroupBtn').addEventListener('click', function() {
    document.getElementById('newGroupModal').remove();
  });
}

// Helper function to validate video duration
function validateVideoDuration(file) {
  return new Promise((resolve) => {
    const video = document.createElement('video');
    video.preload = 'metadata';
    
    video.onloadedmetadata = function() {
      window.URL.revokeObjectURL(video.src);
      resolve(video.duration <= 15);
    };
    
    video.onerror = function() {
      resolve(false);
    };
    
    video.src = URL.createObjectURL(file);
  });
}

// Helper function to format time ago
function formatTimeAgo(timestamp) {
  const now = new Date();
  const date = new Date(timestamp);
  const seconds = Math.floor((now - date) / 1000);
  
  if (seconds < 60) return 'just now';
  if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
  if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
  return Math.floor(seconds / 86400) + 'd ago';
}

// Helper function to get group avatar based on category
function getGroupAvatar(category) {
  const avatars = {
    'family': '👨‍👩‍👧‍👦',
    'arts': '🎨',
    'sports': '⚽',
    'learning': '📚',
    'social': '🗣️',
    'news': '📰',
    'other': '👥'
  };
  return avatars[category] || '👥';
}