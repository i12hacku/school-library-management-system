<?php
include '../includes/auth.php';
include '../includes/header.php';

// Get inventory data
$stmt = $pdo->query("
    SELECT b.*, 
           COUNT(t.transaction_id) as total_issues,
           COUNT(CASE WHEN t.status = 'Issued' THEN 1 END) as current_issues
    FROM books b
    LEFT JOIN transactions t ON b.book_id = t.book_id
    GROUP BY b.book_id
    ORDER BY b.title
");
$books = $stmt->fetchAll();

// Get categories
$categories = $pdo->query("SELECT DISTINCT category FROM books WHERE category IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom-0 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Library Inventory</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel"></i> Filters
                    </button>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Books</h5>
                            <p class="display-4 mb-0"><?= count($books) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Available</h5>
                            <p class="display-4 mb-0">
                                <?= array_reduce($books, fn($carry, $book) => $carry + $book['available'], 0) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Categories</h5>
                            <p class="display-4 mb-0"><?= count($categories) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Active Loans</h5>
                            <p class="display-4 mb-0">
                                <?= array_reduce($books, fn($carry, $book) => $carry + $book['current_issues'], 0) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="inventoryTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px">Cover</th>
                            <th>Title / Author</th>
                            <th>Category</th>
                            <th>Availability</th>
                            <th>Issues</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <?php if ($book['cover_image']): ?>
                                <img src="uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" 
                                     class="img-thumbnail" style="width: 60px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 80px;">
                                    <i class="bi bi-book text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <h6 class="mb-1"><?= htmlspecialchars($book['title']) ?></h6>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($book['author']) ?></p>
                                <?php if ($book['isbn']): ?>
                                <span class="badge bg-secondary small">ISBN: <?= htmlspecialchars($book['isbn']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $book['category'] ? htmlspecialchars($book['category']) : '-' ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 20px;">
                                        <div class="progress-bar bg-<?= $book['available'] > 0 ? 'success' : 'danger' ?>" 
                                             style="width: <?= ($book['available'] / $book['quantity']) * 100 ?>%">
                                        </div>
                                    </div>
                                    <small class="ms-2"><?= $book['available'] ?>/<?= $book['quantity'] ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $book['total_issues'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="../books/view.php?id=<?= $book['book_id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($book['pdf_attachment']): ?>
                                    <a href="uploads/pdfs/<?= htmlspecialchars($book['pdf_attachment']) ?>" 
                                       class="btn btn-sm btn-outline-secondary" title="Download PDF" download>
                                        <i class="bi bi-file-earmark-pdf"></i>
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Availability</label>
                        <select class="form-select" name="availability">
                            <option value="">All</option>
                            <option value="available">Available Only</option>
                            <option value="unavailable">Unavailable Only</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilters">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    overflow: hidden;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple client-side filtering
    document.getElementById('applyFilters').addEventListener('click', function() {
        const category = document.querySelector('select[name="category"]').value.toLowerCase();
        const availability = document.querySelector('select[name="availability"]').value;
        
        document.querySelectorAll('#inventoryTable tbody tr').forEach(row => {
            const rowCategory = row.cells[2].textContent.toLowerCase();
            const available = parseInt(row.cells[3].querySelector('small').textContent.split('/')[0]);
            
            const categoryMatch = !category || rowCategory.includes(category);
            const availabilityMatch = 
                !availability || 
                (availability === 'available' && available > 0) || 
                (availability === 'unavailable' && available === 0);
            
            row.style.display = (categoryMatch && availabilityMatch) ? '' : 'none';
        });
        
        $('#filterModal').modal('hide');
    });
});
</script>

<?php include '../includes/footer.php'; ?>