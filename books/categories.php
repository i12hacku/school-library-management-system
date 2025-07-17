<?php
include '../includes/auth.php';


// Handle category actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $category = trim($_POST['new_category']);
        if (!empty($category)) {
            // Update books with this category if they have the same name
            $stmt = $pdo->prepare("UPDATE books SET category = ? WHERE category = ?");
            $stmt->execute([$category, $_POST['existing_category']]);
            $_SESSION['success'] = "Category updated successfully";
        }
    } elseif (isset($_POST['delete_category'])) {
        $category = $_POST['category'];
        $stmt = $pdo->prepare("UPDATE books SET category = NULL WHERE category = ?");
        $stmt->execute([$category]);
        $_SESSION['success'] = "Category deleted successfully";
    }
    header("Location: categories.php");
    exit();
}

// Get all categories with counts
$categories = $pdo->query("
    SELECT category, COUNT(*) as book_count 
    FROM books 
    WHERE category IS NOT NULL AND category != ''
    GROUP BY category 
    ORDER BY category
")->fetchAll();
include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Book Categories</h5>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Existing Categories</h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($categories)): ?>
                                <p class="text-muted">No categories found</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($categories as $cat): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><?= htmlspecialchars($cat['category']) ?></span>
                                        <div>
                                            <span class="badge bg-primary rounded-pill me-2"><?= $cat['book_count'] ?></span>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-category="<?= htmlspecialchars($cat['category']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Add/Edit Category</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Existing Category (to edit)</label>
                                    <select class="form-select" name="existing_category">
                                        <option value="">-- Create New Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat['category']) ?>">
                                            <?= htmlspecialchars($cat['category']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Category Name</label>
                                    <input type="text" class="form-control" name="new_category" required>
                                </div>
                                <button type="submit" name="add_category" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category? Books with this category will have their category removed.</p>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="category" id="deleteCategoryName">
                    <input type="hidden" name="delete_category" value="1">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteForm" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var category = button.getAttribute('data-category');
        document.getElementById('deleteCategoryName').value = category;
    });
});
</script>

<?php include '../includes/footer.php'; ?>