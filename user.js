document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    try {
        const formData = new FormData(this);
        const response = await fetch('user_check.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Information updated successfully!');
            // Redirect or do something after successful update
            window.location.href = 'confirmation.php'; // Change this
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Confirm';
    }
});