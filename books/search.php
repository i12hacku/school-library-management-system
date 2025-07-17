<?php
include '../includes/auth.php';
include '../includes/header.php';

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';
    $stmt = $pdo->prepare("SELECT * FROM books 
                          WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ? OR category LIKE ?
                          ORDER BY title");
    $stmt->execute([$query, $query, $query, $query]);
    $search_results = $stmt->fetchAll();
}
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Search Books</h5>
                <a href="add.php" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" 
                           placeholder="Search by title, author, ISBN or category" 
                           value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>

            <?php if (!empty($search_results)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['isbn']) ?></td>
                            <td>
                                <span class="badge bg-<?= $book['available'] > 0 ? 'success' : 'danger' ?>">
                                    <?= $book['available'] > 0 ? 'Available' : 'Checked Out' ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="view.php?id=<?= $book['book_id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $book['book_id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($book['available'] > 0): ?>
                                    <a href="../transactions/issue.php?book_id=<?= $book['book_id'] ?>" 
                                       class="btn btn-sm btn-outline-success" title="Issue">
                                        <i class="bi bi-bookmark-plus"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php elseif (isset($_GET['query'])): ?>
            <div class="alert alert-warning mb-0">No books found matching your search.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>