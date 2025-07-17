<?php
include '../includes/auth.php';
// checkAdmin();


if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    // Check if password is being changed
    $password_update = '';
    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $password_update = ", password = ?";
        } else {
            $_SESSION['error'] = "New passwords don't match";
            header("Location: edit_user.php?id=$user_id");
            exit();
        }
    }
    
    $sql = "UPDATE users SET username = ?, role = ? $password_update WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($password_update) {
        $stmt->execute([$username, $role, $password, $user_id]);
    } else {
        $stmt->execute([$username, $role, $user_id]);
    }
    
    $_SESSION['success'] = "User updated successfully";
    header("Location: users.php");
    exit();
}
include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit User</h5>
                <a href="users.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" 
                                   value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="Librarian" <?= $user['role'] == 'Librarian' ? 'selected' : '' ?>>Librarian</option>
                                <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>