</main>
            
            <footer style="background: white; padding: 1.5rem 2rem; margin-top: 2rem; text-align: center; border-top: 1px solid #e0e0e0;">
                <p style="color: #757575; margin: 0;">
                    &copy; <?php echo date('Y'); ?> Inclusive Rwanda - Admin Panel | 
                    <a href="<?php echo BASE_URL; ?>index.php" target="_blank" style="color: var(--primary-color);">View Website</a>
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        // Auto-hide success/error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
        
        // Confirm delete actions
        function confirmDelete(itemName) {
            return confirm('Are you sure you want to delete "' + itemName + '"?\n\nThis action cannot be undone.');
        }
        
        // Image preview function for file uploads
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>