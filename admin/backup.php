<?php
include '../includes/auth.php';
// checkAdmin();
// include '../includes/header.php';

// Handle backup creation
if (isset($_POST['create_backup'])) {
    if (!is_dir('backups')) {
        mkdir('backups', 0777, true);
    }
    $backup_file = 'backups/library_backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Get all table names
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    // Create backup content
    $output = "";
    foreach ($tables as $table) {
        $output .= "-- Table: $table\n";
        
        // Table structure
        $stmt = $pdo->query("SHOW CREATE TABLE $table");
        $row = $stmt->fetch();
        $output .= $row['Create Table'] . ";\n\n";
        
        // Table data
        $stmt = $pdo->query("SELECT * FROM $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns = implode('`, `', array_keys($row));
            $values = implode("', '", array_values($row));
            $output .= "INSERT INTO `$table` (`$columns`) VALUES ('$values');\n";
        }
        $output .= "\n";
    }
    
    // Save to file
    file_put_contents($backup_file, $output);
    $_SESSION['success'] = "Backup created successfully: " . basename($backup_file);
    header("Location: backup.php");
    exit();
}

// Now include header and continue with page output
include '../includes/header.php';

// Get existing backups
$backups = glob('backups/*.sql');
rsort($backups);
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Database Backups</h5>
                <form method="POST">
                    <button type="submit" name="create_backup" class="btn btn-primary">
                        <i class="bi bi-database-add"></i> Create New Backup
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Backup File</th>
                            <th>Date Created</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backups as $backup): 
                            $filename = basename($backup);
                            $date = date('F j, Y H:i', filemtime($backup));
                            $size = round(filesize($backup) / 1024, 2) . ' KB';
                        ?>
                        <tr>
                            <td><?= $filename ?></td>
                            <td><?= $date ?></td>
                            <td><?= $size ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?= $backup ?>" download class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                    <a href="restore.php?file=<?= urlencode($filename) ?>" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </a>
                                    <a href="delete_backup.php?file=<?= urlencode($filename) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this backup?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($backups)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No backups available</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>