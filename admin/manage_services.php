<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$page_title = 'Manage Services';

$success = '';
$error = '';

// Get database connection
$conn = getDBConnection();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image filename before deleting
    $result = $conn->query("SELECT image FROM services WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['image'])) {
            $image_path = 'uploads/' . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    
    // Delete from database
    if ($conn->query("DELETE FROM services WHERE id = $id")) {
        $success = 'Service deleted successfully!';
    } else {
        $error = 'Error deleting service.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_service'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $icon = sanitizeInput($_POST['icon'] ?? '📋');
    $image = '';
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = 'Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $error = 'Image size must be less than 5MB.';
        } else {
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'service_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = 'uploads/' . $image;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $error = 'Error uploading image.';
                $image = '';
            }
        }
    }
    
    // Validate input
    if (empty($error)) {
        if (empty($title)) {
            $error = 'Please enter a service title.';
        } elseif (empty($description)) {
            $error = 'Please enter a service description.';
        } else {
            if ($id > 0) {
                // Update existing service
                if (!empty($image)) {
                    // Delete old image
                    $result = $conn->query("SELECT image FROM services WHERE id = $id");
                    if ($result && $row = $result->fetch_assoc()) {
                        if (!empty($row['image'])) {
                            $old_image = 'uploads/' . $row['image'];
                            if (file_exists($old_image)) {
                                unlink($old_image);
                            }
                        }
                    }
                    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, icon=?, image=? WHERE id=?");
                    $stmt->bind_param("ssssi", $title, $description, $icon, $image, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, icon=? WHERE id=?");
                    $stmt->bind_param("sssi", $title, $description, $icon, $id);
                }
                
                if ($stmt->execute()) {
                    $success = 'Service updated successfully!';
                } else {
                    $error = 'Error updating service.';
                }
            } else {
                // Add new service
                $stmt = $conn->prepare("INSERT INTO services (title, description, icon, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $description, $icon, $image);
                
                if ($stmt->execute()) {
                    $success = 'Service added successfully!';
                } else {
                    $error = 'Error adding service.';
                }
            }
            $stmt->close();
        }
    }
}

// Get service for editing
$edit_service = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM services WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $edit_service = $result->fetch_assoc();
    }
}

// Fetch all services
$services = [];
$result = $conn->query("SELECT * FROM services ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();

require_once 'includes/admin_header.php';
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="admin-card">
    <h2><?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="contact-form">
        <?php if ($edit_service): ?>
            <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
        <?php endif; ?>
        
        <div class="form-row">
            <div class="form-group">
                <label for="title">Service Title <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?php echo $edit_service ? htmlspecialchars($edit_service['title']) : ''; ?>"
                    required
                    placeholder="e.g., Accessibility Audits"
                >
            </div>
            
            <div class="form-group">
                <label for="icon">Icon (Emoji)</label>
                <input 
                    type="text" 
                    id="icon" 
                    name="icon" 
                    value="<?php echo $edit_service ? htmlspecialchars($edit_service['icon']) : '📋'; ?>"
                    placeholder="e.g., ♿ 📚 💻"
                >
                <small>Use an emoji as icon</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description <span class="required">*</span></label>
            <textarea 
                id="description" 
                name="description" 
                rows="4"
                required
                placeholder="Describe this service..."
            ><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="image">Service Image (Optional)</label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                accept="image/*"
                onchange="previewImage(this)"
            >
            <small>Max 5MB. Allowed: JPG, PNG, GIF, WEBP</small>
            
            <?php if ($edit_service && !empty($edit_service['image'])): ?>
                <div style="margin-top: 1rem;">
                    <strong>Current Image:</strong><br>
                    <img src="uploads/<?php echo htmlspecialchars($edit_service['image']); ?>" alt="Current" style="max-width: 200px; border-radius: 8px; margin-top: 0.5rem;">
                </div>
            <?php endif; ?>
            
            <img id="image-preview" style="display: none; max-width: 200px; margin-top: 1rem; border-radius: 8px;" alt="Preview">
        </div>
        
        <div class="btn-group">
            <button type="submit" name="submit_service" class="btn btn-primary">
                <?php echo $edit_service ? 'Update Service' : 'Add Service'; ?>
            </button>
            <?php if ($edit_service): ?>
                <a href="manage_services.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Services List -->
<div class="admin-card">
    <h2>All Services (<?php echo count($services); ?>)</h2>
    
    <?php if (count($services) > 0): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-color); text-align: left;">
                        <th style="padding: 1rem; width: 60px;">Icon</th>
                        <th style="padding: 1rem;">Title</th>
                        <th style="padding: 1rem;">Description</th>
                        <th style="padding: 1rem; width: 100px;">Image</th>
                        <th style="padding: 1rem; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 1rem; font-size: 2rem;"><?php echo htmlspecialchars($service['icon']); ?></td>
                            <td style="padding: 1rem;"><strong><?php echo htmlspecialchars($service['title']); ?></strong></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></td>
                            <td style="padding: 1rem;">
                                <?php if (!empty($service['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="Service" style="max-width: 60px; border-radius: 4px;">
                                <?php else: ?>
                                    <span style="color: #999;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem; margin-right: 0.5rem;">Edit</a>
                                <a href="?delete=<?php echo $service['id']; ?>" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: #E53935; color: white;" onclick="return confirmDelete('<?php echo htmlspecialchars($service['title']); ?>');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No services found. Add your first service above!</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>