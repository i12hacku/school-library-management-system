<?php
include '../includes/auth.php';
checkAdmin();
include '../includes/header.php';

// Get activity logs
$stmt = $pdo->query("
    SELECT a.*, u.username 
    FROM activity_log a
    JOIN users u ON a.user_id = u.user_id
    ORDER BY a.created_at DESC
    LIMIT 100
");
$logs = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Activity Log</h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= date('M j, Y H:i', strtotime($log['created_at'])) ?></td>
                            <td><?= htmlspecialchars($log['username']) ?></td>
                            <td><?= htmlspecialchars($log['activity_type']) ?></td>
                            <td><?= htmlspecialchars($log['description']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>