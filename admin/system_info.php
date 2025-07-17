<?php
include '../includes/auth.php';
// checkAdmin();
include '../includes/header.php';

// Get system information
$php_version = phpversion();
$mysql_version = $pdo->query("SELECT VERSION()")->fetchColumn();
$server_software = $_SERVER['SERVER_SOFTWARE'];
$os = php_uname('s') . ' ' . php_uname('r');
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_members = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>PHP Version</span>
                            <span class="badge bg-primary"><?= $php_version ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>MySQL Version</span>
                            <span class="badge bg-primary"><?= $mysql_version ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Server Software</span>
                            <span class="badge bg-primary"><?= $server_software ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Operating System</span>
                            <span class="badge bg-primary"><?= $os ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Library Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Books</span>
                            <span class="badge bg-success"><?= number_format($total_books) ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Members</span>
                            <span class="badge bg-success"><?= number_format($total_members) ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Active Loans</span>
                            <span class="badge bg-success">
                                <?= number_format($pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'Issued'")->fetchColumn()) ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Overdue Books</span>
                            <span class="badge bg-danger">
                                <?= number_format($pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'Overdue'")->fetchColumn()) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Database Tables</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Table Name</th>
                            <th>Rows</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                        foreach ($tables as $table):
                            $rows = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                            $size = $pdo->query("SELECT ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'")->fetchColumn();
                        ?>
                        <tr>
                            <td><?= $table ?></td>
                            <td><?= number_format($rows) ?></td>
                            <td><?= $size ?> MB</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>