<?php
include '../includes/header.php';
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-muted mb-2">Total Books</h6>
                        <h2 class="mb-0">
                            <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM books");
                            echo number_format($stmt->fetchColumn());
                            ?>
                        </h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-book text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="../books/manage.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-muted mb-2">Available Books</h6>
                        <h2 class="mb-0">
                            <?php 
                            $stmt = $pdo->query("SELECT SUM(available) FROM books");
                            $available = $stmt->fetchColumn();
                            echo number_format($available ?? 0);
                            ?>
                        </h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="../books/search.php" class="btn btn-sm btn-outline-success">Search Books</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase text-muted mb-2">Overdue Books</h6>
                        <h2 class="mb-0">
                            <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'Overdue'");
                            echo number_format($stmt->fetchColumn());
                            ?>
                        </h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="../reports/overdue.php" class="btn btn-sm btn-outline-warning">View Overdue</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Transactions</h5>
                    <a href="../transactions/history.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Issued</th>
                                <th>Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("
                                SELECT t.*, b.title, m.name 
                                FROM transactions t
                                JOIN books b ON t.book_id = b.book_id
                                JOIN members m ON t.member_id = m.member_id
                                ORDER BY t.issue_date DESC
                                LIMIT 7
                            ");
                            while ($row = $stmt->fetch()):
                                $status_class = '';
                                if ($row['status'] == 'Overdue') $status_class = 'danger';
                                elseif ($row['status'] == 'Issued') $status_class = 'primary';
                                else $status_class = 'success';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= $row['issue_date'] ?></td>
                                <td><?= $row['due_date'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $status_class ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="../books/add.php" class="btn btn-outline-primary text-start">
                        <i class="bi bi-plus-circle me-2"></i> Add New Book
                    </a>
                    <a href="../members/add.php" class="btn btn-outline-success text-start">
                        <i class="bi bi-person-plus me-2"></i> Add New Member
                    </a>
                    <a href="../transactions/issue.php" class="btn btn-outline-info text-start">
                        <i class="bi bi-bookmark-plus me-2"></i> Issue Book
                    </a>
                    <a href="../transactions/return.php" class="btn btn-outline-warning text-start">
                        <i class="bi bi-bookmark-check me-2"></i> Return Book
                    </a>
                    <?php if ($_SESSION['role'] == 'Admin'): ?>
                    <a href="../admin/backup.php" class="btn btn-outline-danger text-start">
                        <i class="bi bi-database me-2"></i> Backup Database
                    </a>
                    <?php endif; ?>
                </div>
                
                <hr>
                
                <h6 class="mt-3">Popular Books</h6>
                <div class="list-group">
                    <?php
                    $stmt = $pdo->query("
                        SELECT b.title, COUNT(t.transaction_id) as issue_count
                        FROM books b
                        LEFT JOIN transactions t ON b.book_id = t.book_id
                        GROUP BY b.book_id
                        ORDER BY issue_count DESC
                        LIMIT 3
                    ");
                    while ($row = $stmt->fetch()):
                    ?>
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?= htmlspecialchars($row['title']) ?></h6>
                            <small class="text-muted"><?= $row['issue_count'] ?> issues</small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>