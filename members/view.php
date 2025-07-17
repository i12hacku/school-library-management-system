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

<div class="container mt-4">
    <h2>Search Books</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="query" placeholder="Search by title, author, ISBN or category" 
                           value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (!empty($search_results)): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Search Results</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= $book['available'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <a href="view.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-info">View</a>
                            <?php if ($book['available'] > 0): ?>
                            <a href="../transactions/issue.php?book_id=<?= $book['book_id'] ?>" class="btn btn-sm btn-success">Issue</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif (isset($_GET['query'])): ?>
    <div class="alert alert-warning">No books found matching your search.</div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>