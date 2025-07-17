<?php
include '../includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location: manage.php");
    exit();
}

$book_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: manage.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <?php if ($book['cover_image']): ?>
                    <img src="../uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" 
                         class="img-fluid rounded mb-3" style="max-height: 400px; width: auto;">
                    <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" 
                         style="height: 400px;">
                        <i class="bi bi-book text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($book['pdf_attachment']): ?>
                    <a href="../uploads/pdfs/<?= htmlspecialchars($book['pdf_attachment']) ?>" 
                       class="btn btn-outline-primary w-100 mt-2" download>
                        <i class="bi bi-download"></i> Download Book Info PDF
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h2 class="mb-0"><?= htmlspecialchars($book['title']) ?></h2>
                    <p class="text-muted mb-0">by <?= htmlspecialchars($book['author']) ?></p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>ISBN:</strong> <?= $book['isbn'] ? htmlspecialchars($book['isbn']) : 'N/A' ?></p>
                            <p><strong>Category:</strong> <?= $book['category'] ? htmlspecialchars($book['category']) : 'N/A' ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Available:</strong> <?= $book['available'] ?>/<?= $book['quantity'] ?></p>
                            <p><strong>Location:</strong> <?= $book['location'] ? htmlspecialchars($book['location']) : 'N/A' ?></p>
                        </div>
                    </div>
                    
                    <?php if ($book['description']): ?>
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex gap-2">
                        <a href="edit.php?id=<?= $book['book_id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Book
                        </a>
                        <?php if ($book['available'] > 0): ?>
                        <a href="../transactions/issue.php?book_id=<?= $book['book_id'] ?>" class="btn btn-success">
                            <i class="bi bi-bookmark-plus"></i> Issue Book
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';