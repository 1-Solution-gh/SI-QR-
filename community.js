document.addEventListener('DOMContentLoaded', function() {
  // Global variables
  let currentUser = { id: 1, name: "You" }; // In a real app, you'd get this from session
  let currentCommunityId = null;
  let selectedMedia = null;
  
  // Initialize the page
  initCommunityPage();
  
  // Load threads from database on page load
  loadThreads();

  function initCommunityPage() {
    setupEventListeners();
    setupTabs();
    setupCommunityModal();
  }

  function setupEventListeners() {
    // Thread input functionality
    const threadInput = document.getElementById('thread-input');
    const sendThreadBtn = document.getElementById('send-thread-btn');
    const mediaUpload = document.getElementById('media-upload');
    const removeMediaBtn = document.querySelector('.remove-media-btn');
    
    // Auto-expand textarea
    threadInput.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Send thread on button click
    sendThreadBtn.addEventListener('click', sendThread);
    
    // Send thread on Enter key (but allow Shift+Enter for new line)
    threadInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendThread();
      }
    });
    
    // Handle media upload
    mediaUpload.addEventListener('change', handleMediaUpload);
    
    // Remove selected media
    if (removeMediaBtn) {
      removeMediaBtn.addEventListener('click', removeSelectedMedia);
    }
  }

  function loadThreads() {
    fetch('api_community.php?action=get_threads')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          renderThreads(data.threads);
        } else {
          console.error('Error loading threads:', data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function renderThreads(threads) {
    const threadsList = document.querySelector('.threads-list');
    threadsList.innerHTML = '';
    
    if (threads.length === 0) {
      threadsList.innerHTML = '<p class="no-threads">No threads yet. Start the conversation!</p>';
      return;
    }
    
    // Sort threads by date (newest first)
    threads.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    
    threads.forEach(thread => {
      const threadElement = document.createElement('div');
      threadElement.className = 'thread-item';
      
      let mediaHtml = '';
      if (thread.media_url) {
        if (thread.media_type === 'video') {
          mediaHtml = `
            <div class="thread-media-container">
              <video class="thread-media video" controls>
                <source src="${thread.media_url}" type="video/mp4">
                Your browser does not support the video tag.
              </video>
              ${thread.media_caption ? `<div class="thread-caption">${thread.media_caption}</div>` : ''}
            </div>
          `;
        } else {
          mediaHtml = `
            <div class="thread-media-container">
              <img class="thread-media" src="${thread.media_url}" alt="Thread media">
              ${thread.media_caption ? `<div class="thread-caption">${thread.media_caption}</div>` : ''}
            </div>
          `;
        }
      }
      
      threadElement.innerHTML = `
        <div class="thread-author">
          <div class="thread-author-avatar">
            ${thread.author_name.charAt(0).toUpperCase()}
          </div>
          <span>${thread.author_name}</span>
        </div>
        <div class="thread-content">${thread.content}</div>
        ${mediaHtml}
        <div class="thread-time">${formatTimeAgo(thread.created_at)}</div>
      `;
      
      threadsList.appendChild(threadElement);
    });
  }

  function sendThread() {
    const threadInput = document.getElementById('thread-input');
    const message = threadInput.value.trim();
    const caption = document.getElementById('media-caption')?.value.trim() || '';
    
    if (!message && !selectedMedia) {
      alert('Please enter a message or attach media');
      return;
    }
    
    const formData = new FormData();
    formData.append('user_id', currentUser.id);
    formData.append('content', message);
    formData.append('media_caption', caption);
    
    if (selectedMedia) {
      formData.append('media', selectedMedia.file);
    }
    
    fetch('api_community.php?action=create_thread', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reset input
        threadInput.value = '';
        threadInput.style.height = 'auto';
        removeSelectedMedia();
        
        // Reload threads
        loadThreads();
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to send thread');
    });
  }

  function handleMediaUpload(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const preview = document.getElementById('media-preview');
    const previewContent = preview.querySelector('.preview-content');
    previewContent.innerHTML = '';
    
    const isVideo = file.type.includes('video');
    
    // Validate file
    if (isVideo && file.size > 15 * 1024 * 1024) { // 15MB limit
      alert('Video must be 15 seconds or shorter');
      e.target.value = '';
      return;
    }
    
    // Create preview
    const reader = new FileReader();
    reader.onload = function(event) {
      if (isVideo) {
        validateVideoDuration(file).then(valid => {
          if (valid) {
            createMediaPreview(file, event.target.result, isVideo);
          } else {
            alert('Video must be 15 seconds or shorter');
            e.target.value = '';
          }
        });
      } else {
        createMediaPreview(file, event.target.result, isVideo);
      }
    };
    reader.readAsDataURL(file);
  }

  function createMediaPreview(file, dataUrl, isVideo) {
    const preview = document.getElementById('media-preview');
    const previewContent = preview.querySelector('.preview-content');
    
    selectedMedia = {
      url: dataUrl,
      type: isVideo ? 'video' : 'image',
      file: file
    };
    
    if (isVideo) {
      previewContent.innerHTML = `
        <video controls style="max-width: 100%;">
          <source src="${dataUrl}" type="${file.type}">
          Your browser does not support the video tag.
        </video>
      `;
    } else {
      previewContent.innerHTML = `<img src="${dataUrl}" alt="Upload preview" style="max-width: 100%;">`;
    }
    
    preview.style.display = 'block';
  }

  function removeSelectedMedia() {
    const preview = document.getElementById('media-preview');
    preview.style.display = 'none';
    preview.querySelector('.preview-content').innerHTML = '';
    document.getElementById('media-upload').value = '';
    document.getElementById('media-caption').value = '';
    selectedMedia = null;
  }

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

  function formatTimeAgo(timestamp) {
    const now = new Date();
    const date = new Date(timestamp);
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
    return Math.floor(seconds / 86400) + 'd ago';
  }

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

  function setupTabs() {
      document.querySelectorAll('.tab-btn').forEach(btn => {
          btn.addEventListener('click', () => {
              // Update active tab button
              document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
              btn.classList.add('active');
              
              // Show corresponding tab content
              const tabId = btn.dataset.tab;
              document.querySelectorAll('.tab-content').forEach(content => {
                  content.classList.remove('active');
              });
              document.getElementById(`${tabId}-tab`).classList.add('active');
              
              // Load appropriate data
              if (tabId === 'threads') {
                  loadThreads();
              } else {
                  loadCommunityGroups();
              }
          });
      });
  }

  function setupCommunityModal() {
      const modal = document.getElementById('create-community-modal');
      const createBtn = document.getElementById('create-community-btn');
      const closeBtn = document.querySelector('.close-modal');
      
      if (!modal || !createBtn || !closeBtn) return;
      
      createBtn.addEventListener('click', () => {
          modal.style.display = 'flex';
      });
      
      closeBtn.addEventListener('click', () => {
          modal.style.display = 'none';
      });
      
      window.addEventListener('click', (e) => {
          if (e.target === modal) {
              modal.style.display = 'none';
          }
      });
      
      document.getElementById('community-form').addEventListener('submit', (e) => {
          e.preventDefault();
          
          const formData = {
              name: document.querySelector('#community-form input[type="text"]').value,
              description: document.querySelector('#community-form textarea').value,
              category: document.querySelector('#community-form select').value,
              meeting_schedule: '',
              tags: '',
              user_id: currentUser.id
          };
          
          fetch('api_community.php?action=create_group', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify(formData)
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Your community has been submitted for approval');
                  modal.style.display = 'none';
                  document.getElementById('community-form').reset();
                  
                  // Reload groups
                  loadCommunityGroups();
              } else {
                  alert('Error: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Failed to create community');
          });
      });
  }
});