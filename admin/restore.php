<?php
include '../includes/auth.php';
// checkAdmin();
include '../includes/header.php';

$filename = isset($_GET['file']) ? $_GET['file'] : null;
$backup_file = 'backups/' . $filename;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $filename) {
    // Read backup file
    $sql = file_get_contents($backup_file);
    
    try {
        // Disable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        // Execute each query
        $queries = explode(";\n", $sql);
        foreach ($queries as $query) {
            if (trim($query)) {
                $pdo->exec($query);
            }
        }
        
        // Enable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        $_SESSION['success'] = "Database restored successfully from $filename";
        header("Location: backup.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error restoring database: " . $e->getMessage();
        header("Location: restore.php?file=" . urlencode($filename));
        exit();
    }
}

if (!file_exists($backup_file) || !$filename) {
    $_SESSION['error'] = "Backup file not found";
    header("Location: backup.php");
    exit();
}
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Restore Database</h5>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="alert alert-warning">
                <h5><i class="bi bi-exclamation-triangle-fill"></i> Warning</h5>
                <p>Restoring from a backup will overwrite all current data in the database. This action cannot be undone.</p>
                <p class="mb-0"><strong>Backup file:</strong> <?= htmlspecialchars($filename) ?></p>
            </div>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Confirm Backup File</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($filename) ?>" readonly>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="backup.php" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise"></i> Confirm Restore
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>