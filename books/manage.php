<?php
include '../includes/auth.php';
include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Books</h2>
        <a href="add.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Book
        </a>
    </div>
    
    <div class="row">
        <?php
        $stmt = $pdo->query("SELECT * FROM books ORDER BY title");
        while ($row = $stmt->fetch()) {
            // Determine availability badge color and text
            $available = $row['available'];
            $total = $row['quantity'];
            $badgeClass = ($available > 0) ? 'bg-success' : 'bg-danger';
            $statusText = ($available > 0) ? 'Available' : 'Checked Out';
            
            // Generate a placeholder cover if no image exists
            $coverImage = !empty($row['cover_image']) ? 
                "../uploads/covers/{$row['cover_image']}" : 
                "https://via.placeholder.com/150x200?text=" . urlencode(substr($row['title'], 0, 2));
        ?>
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <!-- Book Cover -->
                <img src="<?= $coverImage ?>" class="card-img-top" alt="Book Cover" style="height: 200px; object-fit: cover;">
                
                <div class="card-body">
                    <!-- Book ID -->
                    <small class="text-muted">ID: <?= $row['book_id'] ?></small>
                    
                    <!-- Title -->
                    <h5 class="card-title mt-2"><?= htmlspecialchars($row['title']) ?></h5>
                    
                    <!-- Author -->
                    <p class="card-text text-muted">
                        <i class="bi bi-person"></i> <?= htmlspecialchars($row['author']) ?>
                    </p>
                    
                    <!-- Availability -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge <?= $badgeClass ?>">
                            <?= "$available/$total $statusText" ?>
                        </span>
                        
                        <!-- Action Buttons -->
                        <div class="btn-group btn-group-sm">
                            <a href="view.php?id=<?= $row['book_id'] ?>" 
                               class="btn btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $row['book_id'] ?>" 
                               class="btn btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($available > 0): ?>
                            <a href="../transactions/issue.php?book_id=<?= $row['book_id'] ?>" 
                               class="btn btn-outline-success" title="Issue">
                                <i class="bi bi-bookmark-plus"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>