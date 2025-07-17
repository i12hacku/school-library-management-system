<?php
include '../includes/auth.php';
// checkAdmin();
include '../includes/header.php';

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    if ($delete_id != $_SESSION['user_id']) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$delete_id]);
            $_SESSION['success'] = "User deleted successfully";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "You cannot delete your own account";
    }
    header("Location: users.php");
    exit();
}

// Get all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY role, username");
$users = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Management</h5>
                <a href="add_user.php" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus"></i> Add User
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] == 'Admin' ? 'danger' : 'primary' ?>">
                                    <?= $user['role'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="edit_user.php?id=<?= $user['user_id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <a href="users.php?delete=<?= $user['user_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>