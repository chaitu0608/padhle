document.addEventListener('DOMContentLoaded', function() {
    // Profile sidebar functionality
    const profileBtn = document.getElementById('profile-btn');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const profileSidebar = document.getElementById('profile-sidebar');
    const overlay = document.getElementById('overlay');
    const cancelBtn = document.querySelector('.cancel-btn');

    // Function to open sidebar
    function openSidebar() {
        profileSidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Function to close sidebar
    function closeSidebar() {
        profileSidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Enable scrolling
    }

    // Event listeners
    profileBtn.addEventListener('click', openSidebar);
    closeSidebarBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    cancelBtn.addEventListener('click', closeSidebar);

    // Mobile menu toggle (for future implementation)
    // This would be used to show/hide the navbar links on mobile
    
    // Form validation (basic example)
    const saveBtn = document.querySelector('.save-btn');
    
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        // Basic validation
        if (newPassword && newPassword !== confirmPassword) {
            alert('New passwords do not match!');
            return;
        }
        
        // Here you would typically send the data to a server using AJAX/fetch
        // For this example, we'll just show a success message
        if (newPassword) {
            if (!currentPassword) {
                alert('Please enter your current password');
                return;
            }
            
            // Simulate password change success
            alert('Profile updated successfully!');
            closeSidebar();
        } else {
            // Just updating profile info
            alert('Profile information updated!');
            closeSidebar();
        }
    });
});