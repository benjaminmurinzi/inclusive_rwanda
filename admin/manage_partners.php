<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$page_title = 'Manage Partners';

$success = '';
$error = '';

// Get database connection
$conn = getDBConnection();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get logo filename before deleting
    $result = $conn->query("SELECT logo FROM partners WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['logo'])) {
            $logo_path = 'uploads/' . $row['logo'];
            if (file_exists($logo_path)) {
                unlink($logo_path);
            }
        }
    }
    
    // Delete from database
    if ($conn->query("DELETE FROM partners WHERE id = $id")) {
        $success = 'Partner deleted successfully!';
    } else {
        $error = 'Error deleting partner.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_partner'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $website = sanitizeInput($_POST['website'] ?? '');
    $logo = '';
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['logo']['type'], $allowed_types)) {
            $error = 'Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.';
        } elseif ($_FILES['logo']['size'] > $max_size) {
            $error = 'Image size must be less than 5MB.';
        } else {
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $logo = 'partner_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = 'uploads/' . $logo;
            
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                $error = 'Error uploading logo.';
                $logo = '';
            }
        }
    }
    
    // Validate input
    if (empty($error)) {
        if (empty($name)) {
            $error = 'Please enter partner name.';
        } elseif (empty($description)) {
            $error = 'Please enter partner description.';
        } else {
            if ($id > 0) {
                // Update existing partner
                if (!empty($logo)) {
                    // Delete old logo
                    $result = $conn->query("SELECT logo FROM partners WHERE id = $id");
                    if ($result && $row = $result->fetch_assoc()) {
                        if (!empty($row['logo'])) {
                            $old_logo = 'uploads/' . $row['logo'];
                            if (file_exists($old_logo)) {
                                unlink($old_logo);
                            }
                        }
                    }
                    $stmt = $conn->prepare("UPDATE partners SET name=?, description=?, website=?, logo=? WHERE id=?");
                    $stmt->bind_param("ssssi", $name, $description, $website, $logo, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE partners SET name=?, description=?, website=? WHERE id=?");
                    $stmt->bind_param("sssi", $name, $description, $website, $id);
                }
                
                if ($stmt->execute()) {
                    $success = 'Partner updated successfully!';
                } else {
                    $error = 'Error updating partner.';
                }
            } else {
                // Add new partner
                $stmt = $conn->prepare("INSERT INTO partners (name, description, website, logo) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $description, $website, $logo);
                
                if ($stmt->execute()) {
                    $success = 'Partner added successfully!';
                } else {
                    $error = 'Error adding partner.';
                }
            }
            $stmt->close();
        }
    }
}

// Get partner for editing
$edit_partner = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM partners WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $edit_partner = $result->fetch_assoc();
    }
}

// Fetch all partners
$partners = [];
$result = $conn->query("SELECT * FROM partners ORDER BY name ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $partners[] = $row;
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
    <h2><?php echo $edit_partner ? 'Edit Partner' : 'Add New Partner'; ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="contact-form">
        <?php if ($edit_partner): ?>
            <input type="hidden" name="id" value="<?php echo $edit_partner['id']; ?>">
        <?php endif; ?>
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Partner Name <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo $edit_partner ? htmlspecialchars($edit_partner['name']) : ''; ?>"
                    required
                    placeholder="e.g., Rwanda National Union of the Deaf"
                >
            </div>
            
            <div class="form-group">
                <label for="website">Website URL</label>
                <input 
                    type="url" 
                    id="website" 
                    name="website" 
                    value="<?php echo $edit_partner ? htmlspecialchars($edit_partner['website']) : ''; ?>"
                    placeholder="https://example.com"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description <span class="required">*</span></label>
            <textarea 
                id="description" 
                name="description" 
                rows="4"
                required
                placeholder="Describe this partnership..."
            ><?php echo $edit_partner ? htmlspecialchars($edit_partner['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="logo">Partner Logo (Optional)</label>
            <input 
                type="file" 
                id="logo" 
                name="logo" 
                accept="image/*"
                onchange="previewImage(this)"
            >
            <small>Max 5MB. Allowed: JPG, PNG, GIF, WEBP</small>
            
            <?php if ($edit_partner && !empty($edit_partner['logo'])): ?>
                <div style="margin-top: 1rem;">
                    <strong>Current Logo:</strong><br>
                    <img src="uploads/<?php echo htmlspecialchars($edit_partner['logo']); ?>" alt="Current" style="max-width: 200px; border-radius: 8px; margin-top: 0.5rem;">
                </div>
            <?php endif; ?>
            
            <img id="image-preview" style="display: none; max-width: 200px; margin-top: 1rem; border-radius: 8px;" alt="Preview">
        </div>
        
        <div class="btn-group">
            <button type="submit" name="submit_partner" class="btn btn-primary">
                <?php echo $edit_partner ? 'Update Partner' : 'Add Partner'; ?>
            </button>
            <?php if ($edit_partner): ?>
                <a href="manage_partners.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Partners List -->
<div class="admin-card">
    <h2>All Partners (<?php echo count($partners); ?>)</h2>
    
    <?php if (count($partners) > 0): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-color); text-align: left;">
                        <th style="padding: 1rem; width: 100px;">Logo</th>
                        <th style="padding: 1rem;">Name</th>
                        <th style="padding: 1rem;">Description</th>
                        <th style="padding: 1rem; width: 150px;">Website</th>
                        <th style="padding: 1rem; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $partner): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 1rem;">
                                <?php if (!empty($partner['logo'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($partner['logo']); ?>" alt="Logo" style="max-width: 60px; border-radius: 4px;">
                                <?php else: ?>
                                    <span style="color: #999;">No logo</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;"><strong><?php echo htmlspecialchars($partner['name']); ?></strong></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars(substr($partner['description'], 0, 80)) . '...'; ?></td>
                            <td style="padding: 1rem;">
                                <?php if (!empty($partner['website'])): ?>
                                    <a href="<?php echo htmlspecialchars($partner['website']); ?>" target="_blank" style="color: var(--primary-color);">Visit →</a>
                                <?php else: ?>
                                    <span style="color: #999;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="?edit=<?php echo $partner['id']; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem; margin-right: 0.5rem;">Edit</a>
                                <a href="?delete=<?php echo $partner['id']; ?>" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: #E53935; color: white;" onclick="return confirmDelete('<?php echo htmlspecialchars($partner['name']); ?>');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No partners found. Add your first partner above!</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>