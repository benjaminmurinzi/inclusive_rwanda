<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$page_title = 'Manage News';

$success = '';
$error = '';

// Get database connection
$conn = getDBConnection();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image filename before deleting
    $result = $conn->query("SELECT image FROM news WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['image'])) {
            $image_path = 'uploads/' . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    
    // Delete from database
    if ($conn->query("DELETE FROM news WHERE id = $id")) {
        $success = 'News article deleted successfully!';
    } else {
        $error = 'Error deleting news article.';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_news'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = sanitizeInput($_POST['title'] ?? '');
    $content = sanitizeInput($_POST['content'] ?? '');
    $author = sanitizeInput($_POST['author'] ?? 'Admin');
    $published = isset($_POST['published']) ? 1 : 0;
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
            $image = 'news_' . time() . '_' . uniqid() . '.' . $extension;
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
            $error = 'Please enter article title.';
        } elseif (empty($content)) {
            $error = 'Please enter article content.';
        } elseif (strlen($content) < 50) {
            $error = 'Article content must be at least 50 characters.';
        } else {
            if ($id > 0) {
                // Update existing news
                if (!empty($image)) {
                    // Delete old image
                    $result = $conn->query("SELECT image FROM news WHERE id = $id");
                    if ($result && $row = $result->fetch_assoc()) {
                        if (!empty($row['image'])) {
                            $old_image = 'uploads/' . $row['image'];
                            if (file_exists($old_image)) {
                                unlink($old_image);
                            }
                        }
                    }
                    $stmt = $conn->prepare("UPDATE news SET title=?, content=?, author=?, published=?, image=? WHERE id=?");
                    $stmt->bind_param("sssisi", $title, $content, $author, $published, $image, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE news SET title=?, content=?, author=?, published=? WHERE id=?");
                    $stmt->bind_param("sssii", $title, $content, $author, $published, $id);
                }
                
                if ($stmt->execute()) {
                    $success = 'News article updated successfully!';
                } else {
                    $error = 'Error updating news article.';
                }
            } else {
                // Add new news
                $stmt = $conn->prepare("INSERT INTO news (title, content, author, published, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $title, $content, $author, $published, $image);
                
                if ($stmt->execute()) {
                    $success = 'News article added successfully!';
                } else {
                    $error = 'Error adding news article.';
                }
            }
            $stmt->close();
        }
    }
}

// Get news for editing
$edit_news = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM news WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $edit_news = $result->fetch_assoc();
    }
}

// Fetch all news
$news = [];
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
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
    <h2><?php echo $edit_news ? 'Edit News Article' : 'Add New News Article'; ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="contact-form">
        <?php if ($edit_news): ?>
            <input type="hidden" name="id" value="<?php echo $edit_news['id']; ?>">
        <?php endif; ?>
        
        <div class="form-row">
            <div class="form-group">
                <label for="title">Article Title <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?php echo $edit_news ? htmlspecialchars($edit_news['title']) : ''; ?>"
                    required
                    placeholder="e.g., New Accessibility Guidelines Launched"
                >
            </div>
            
            <div class="form-group">
                <label for="author">Author Name</label>
                <input 
                    type="text" 
                    id="author" 
                    name="author" 
                    value="<?php echo $edit_news ? htmlspecialchars($edit_news['author']) : 'Admin'; ?>"
                    placeholder="Admin"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label for="content">Article Content <span class="required">*</span></label>
            <textarea 
                id="content" 
                name="content" 
                rows="10"
                required
                placeholder="Write your news article here... (minimum 50 characters)"
            ><?php echo $edit_news ? htmlspecialchars($edit_news['content']) : ''; ?></textarea>
            <small>Minimum 50 characters</small>
        </div>
        
        <div class="form-group">
            <label for="image">Featured Image (Optional)</label>
            <input 
                type="file" 
                id="image" 
                name="image" 
                accept="image/*"
                onchange="previewImage(this)"
            >
            <small>Max 5MB. Allowed: JPG, PNG, GIF, WEBP</small>
            
            <?php if ($edit_news && !empty($edit_news['image'])): ?>
                <div style="margin-top: 1rem;">
                    <strong>Current Image:</strong><br>
                    <img src="uploads/<?php echo htmlspecialchars($edit_news['image']); ?>" alt="Current" style="max-width: 300px; border-radius: 8px; margin-top: 0.5rem;">
                </div>
            <?php endif; ?>
            
            <img id="image-preview" style="display: none; max-width: 300px; margin-top: 1rem; border-radius: 8px;" alt="Preview">
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input 
                    type="checkbox" 
                    name="published" 
                    <?php echo (!$edit_news || $edit_news['published'] == 1) ? 'checked' : ''; ?>
                    style="width: auto;"
                >
                <span>Publish this article (visible on website)</span>
            </label>
        </div>
        
        <div class="btn-group">
            <button type="submit" name="submit_news" class="btn btn-primary">
                <?php echo $edit_news ? 'Update Article' : 'Add Article'; ?>
            </button>
            <?php if ($edit_news): ?>
                <a href="manage_news.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- News List -->
<div class="admin-card">
    <h2>All News Articles (<?php echo count($news); ?>)</h2>
    
    <?php if (count($news) > 0): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-color); text-align: left;">
                        <th style="padding: 1rem;">Title</th>
                        <th style="padding: 1rem; width: 120px;">Author</th>
                        <th style="padding: 1rem; width: 100px;">Status</th>
                        <th style="padding: 1rem; width: 120px;">Date</th>
                        <th style="padding: 1rem; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $article): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 1rem;">
                                <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                                <?php if (!empty($article['image'])): ?>
                                    <br><img src="uploads/<?php echo htmlspecialchars($article['image']); ?>" alt="" style="max-width: 80px; margin-top: 0.5rem; border-radius: 4px;">
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($article['author']); ?></td>
                            <td style="padding: 1rem;">
                                <?php if ($article['published'] == 1): ?>
                                    <span style="color: #43A047; font-weight: bold;">● Published</span>
                                <?php else: ?>
                                    <span style="color: #FF9800; font-weight: bold;">○ Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;"><?php echo date('M j, Y', strtotime($article['created_at'])); ?></td>
                            <td style="padding: 1rem;">
                                <a href="?edit=<?php echo $article['id']; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem; margin-right: 0.5rem;">Edit</a>
                                <a href="?delete=<?php echo $article['id']; ?>" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: #E53935; color: white;" onclick="return confirmDelete('<?php echo htmlspecialchars($article['title']); ?>');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No news articles found. Add your first article above!</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/admin_footer.php'; ?>