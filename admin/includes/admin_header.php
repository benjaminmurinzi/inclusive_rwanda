<?php
// Make sure user is logged in
requireLogin();

// Get current admin info
$current_admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel - Inclusive Rwanda</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-sidebar-header {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-sidebar-header h2 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .admin-user-info {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }
        
        .admin-nav {
            padding: 2rem 0;
        }
        
        .admin-nav ul {
            list-style: none;
        }
        
        .admin-nav li {
            margin-bottom: 0.5rem;
        }
        
        .admin-nav a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .admin-nav a:hover,
        .admin-nav a.active {
            background: var(--primary-color);
            color: white;
        }
        
        .admin-nav a.logout {
            background: #E53935;
            margin: 1rem 1.5rem 0;
            border-radius: var(--border-radius);
            text-align: center;
        }
        
        .admin-nav a.logout:hover {
            background: #C62828;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 250px;
            background: var(--light-color);
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-header h1 {
            color: var(--dark-color);
            margin: 0;
        }
        
        .admin-main {
            padding: 2rem;
        }
        
        .admin-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-color);
            font-size: 0.9rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h2>Admin Panel</h2>
                <div class="admin-user-info">
                    👤 <?php echo htmlspecialchars($current_admin['username']); ?>
                </div>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">📊 Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_services.php' ? 'active' : ''; ?>">🛠️ Manage Services</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_partners.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_partners.php' ? 'active' : ''; ?>">🤝 Manage Partners</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/manage_news.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_news.php' ? 'active' : ''; ?>">📰 Manage News</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php" target="_blank">🌐 View Website</a></li>
                </ul>
                
                <a href="<?php echo BASE_URL; ?>admin/logout.php" class="logout" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
            </nav>
        </aside>
        
        <div class="admin-content">
            <header class="admin-header">
                <h1><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></h1>
            </header>
            
            <main class="admin-main">