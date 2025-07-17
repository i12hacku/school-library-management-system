<?php
include '../includes/auth.php';
include '../includes/header.php';

// Get current user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle password change
    if (!empty($_POST['current_password'])) {
        if (password_verify($_POST['current_password'], $user['password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $update_stmt->execute([$new_password, $_SESSION['user_id']]);
                $_SESSION['success'] = "Password changed successfully";
            } else {
                $_SESSION['error'] = "New passwords don't match";
            }
        } else {
            $_SESSION['error'] = "Current password is incorrect";
        }
        header("Location: profile.php");
        exit();
    }
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center">
                        <div class="avatar-lg mb-3">
                            <div class="avatar-initial rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-1"><?= htmlspecialchars($user['username']) ?></h4>
                    <p class="text-muted mb-3"><?= ucfirst($user['role']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Change Password</h5>
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
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>