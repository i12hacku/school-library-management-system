<?php
include '../includes/auth.php';
checkAdmin();
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        $_SESSION['success'] = "User added successfully";
        header("Location: users.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate username
            $error = "Username already exists";
        } else {
            $error = "Error creating user: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-4">
    <h2>Add New User</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="Librarian">Librarian</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>