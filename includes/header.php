<?php
include 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Library System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <!-- <link href="assets/css/header.css" rel="stylesheet"> -->
    <!-- Page-specific CSS -->

</head>
<body>
    <style>
        :root {
  --sidebar-width: 280px;
  --sidebar-bg: #2c3e50;
  --sidebar-active: #34495e;
  --sidebar-text: #ecf0f1;
  --sidebar-hover: #3d566e;
  --topbar-height: 70px;
  --topbar-bg: #ffffff;
  --primary-color: #3498db;
  --transition-speed: 0.3s;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Sidebar Styles */
.sidebar {
  width: var(--sidebar-width);
  background: var(--sidebar-bg);
  color: var(--sidebar-text);
  position: fixed;
  height: 100vh;
  z-index: 1000;
  transition: all var(--transition-speed) ease;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-brand {
  padding: 1.5rem 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-brand h3 {
  font-weight: 600;
  font-size: 1.3rem;
  display: flex;
  align-items: center;
  margin: 0;
  color: white;
}

.sidebar-brand i {
  margin-right: 0.75rem;
  font-size: 1.5rem;
  color: var(--primary-color);
}

.sidebar-nav {
  padding: 1rem 0;
  overflow-y: auto;
  height: calc(100vh - var(--topbar-height));
}

.nav-item {
  position: relative;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.85rem 1.5rem;
  color: var(--sidebar-text);
  text-decoration: none;
  transition: all var(--transition-speed) ease;
  font-weight: 500;
}

.nav-link:hover {
  background: var(--sidebar-hover);
  color: white;
}

.nav-link.active {
  background: var(--sidebar-active);
  color: white;
  border-left: 4px solid var(--primary-color);
}

.nav-link i {
  font-size: 1.1rem;
  margin-right: 0.75rem;
  width: 20px;
  text-align: center;
}

.nav-link .bi-chevron-down {
  margin-left: auto;
  transition: transform var(--transition-speed) ease;
}

.nav-link[aria-expanded="true"] .bi-chevron-down {
  transform: rotate(180deg);
}

.collapse {
  background: rgba(0, 0, 0, 0.1);
}

.nav-item .nav-link {
  padding-left: 3.5rem;
  font-size: 0.95rem;
}

/* Top Navigation Bar */
.topbar {
  height: var(--topbar-height);
  background: var(--topbar-bg);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: fixed;
  width: calc(100% - var(--sidebar-width));
  margin-left: var(--sidebar-width);
  z-index: 999;
  display: flex;
  align-items: center;
  padding: 0 1.5rem;
  transition: all var(--transition-speed) ease;
}

.sidebar-toggle {
  background: none;
  border: none;
  color: #7f8c8d;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all var(--transition-speed) ease;
}

.sidebar-toggle:hover {
  color: var(--primary-color);
}

.topbar-nav {
  display: flex;
  align-items: center;
  margin-left: auto;
}

.user-menu {
  display: flex;
  align-items: center;
  
}



/* Main Content Area */
.main-content {
  width: calc(100% - var(--sidebar-width));
  margin-left: var(--sidebar-width);
  padding: calc(var(--topbar-height) + 1.5rem) 1.5rem 1.5rem;
  min-height: 100vh;
  transition: all var(--transition-speed) ease;
  background: #f5f7fa;
}

/* Responsive Styles */
@media (max-width: 992px) {
  .sidebar {
    margin-left: calc(-1 * var(--sidebar-width));
  }
  
  .sidebar.active {
    margin-left: 0;
  }
  
  .topbar {
    width: 100%;
    margin-left: 0;
  }
  
  .main-content {
    width: 100%;
    margin-left: 0;
  }
  
  .main-content.active {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
  }
}
    </style>
    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h3><i class="bi bi-book me-2"></i> School Library</h3>
            </div>
            
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                           href="../admin/dashboard.php">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Books Section -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#booksMenu">
                            <i class="bi bi-book"></i>
                            <span>Books</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="booksMenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="../books/add.php">
                                        <i class="bi bi-plus-circle"></i>
                                        <span>Add Book</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../books/manage.php">
                                        <i class="bi bi-list-ul"></i>
                                        <span>Manage Books</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../books/search.php">
                                        <i class="bi bi-search"></i>
                                        <span>Search Books</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Members Section -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#membersMenu">
                            <i class="bi bi-people"></i>
                            <span>Members</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="membersMenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="../members/add.php">
                                        <i class="bi bi-person-plus"></i>
                                        <span>Add Member</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../members/manage.php">
                                        <i class="bi bi-card-list"></i>
                                        <span>Manage Members</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Transactions Section -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#transactionsMenu">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Transactions</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="transactionsMenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="../transactions/issue.php">
                                        <i class="bi bi-bookmark-plus"></i>
                                        <span>Issue Book</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../transactions/return.php">
                                        <i class="bi bi-bookmark-check"></i>
                                        <span>Return Book</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../transactions/history.php">
                                        <i class="bi bi-clock-history"></i>
                                        <span>History</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Reports Section -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#reportsMenu">
                            <i class="bi bi-graph-up"></i>
                            <span>Reports</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="reportsMenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="../reports/overdue.php">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <span>Overdue Books</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../reports/popular.php">
                                        <i class="bi bi-star"></i>
                                        <span>Popular Books</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../reports/inventory.php">
                                        <i class="bi bi-clipboard-data"></i>
                                        <span>Inventory</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <?php if ($_SESSION['role'] == 'Admin'): ?>
                    <!-- Admin Section -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#adminMenu">
                            <i class="bi bi-shield-lock"></i>
                            <span>Admin</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="adminMenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="../admin/users.php">
                                        <i class="bi bi-person-gear"></i>
                                        <span>User Management</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../admin/settings.php">
                                        <i class="bi bi-gear"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../admin/backup.php">
                                        <i class="bi bi-database"></i>
                                        <span>Backup</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Add to your existing sidebar navigation -->


<li class="nav-item">
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'help.php' ? 'active' : '' ?>" 
       href="../help.php">
        <i class="bi bi-question-circle"></i>
        <span>Help</span>
    </a>
</li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        
                            <!-- Top Navigation Bar -->
        <nav class="topbar">
            <!-- <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button> -->
            
            <div class="topbar-nav ms-auto">
                <div class="user-menu">
                    <span class="username">
                        <a href="../admin/profile.php" class="text-decoration-none text-dark">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="../logout.php" class="btn btn-sm btn-outline-danger btn-logout ms-3">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content" id="main-content">
            <!-- Page Content will be inserted here -->
            <div class="container-fluid">




