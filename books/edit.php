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

// --- Add upload functions directly here ---
function uploadCoverImage($file) {
    $target_dir = "../uploads/covers/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $filename = uniqid() . "_" . basename($file["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['filename' => $filename];
    } else {
        return ['error' => 'Failed to upload cover image'];
    }
}

function uploadPdfFile($file) {
    $target_dir = "../uploads/pdfs/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $filename = uniqid() . "_" . basename($file["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['filename' => $filename];
    } else {
        return ['error' => 'Failed to upload PDF'];
    }
}
// --- End upload functions ---

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Handle file uploads
    $cover_image = $book['cover_image'];
    $pdf_attachment = $book['pdf_attachment'];

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        $upload = uploadCoverImage($_FILES['cover_image']);
        if (!isset($upload['error'])) {
            $cover_image = $upload['filename'];
            // Delete old cover image if exists
            if ($book['cover_image']) {
                @unlink("../uploads/covers/{$book['cover_image']}");
            }
        } else {
            $_SESSION['error'] = $upload['error'];
        }
    }
    if (isset($_POST['remove_cover']) && $book['cover_image']) {
        @unlink("../uploads/covers/{$book['cover_image']}");
        $cover_image = null;
    }

    if (isset($_FILES['pdf_attachment']) && $_FILES['pdf_attachment']['error'] == UPLOAD_ERR_OK) {
        $upload = uploadPdfFile($_FILES['pdf_attachment']);
        if (!isset($upload['error'])) {
            $pdf_attachment = $upload['filename'];
            // Delete old PDF if exists
            if ($book['pdf_attachment']) {
                @unlink("../uploads/pdfs/{$book['pdf_attachment']}");
            }
        } else {
            $_SESSION['error'] = $upload['error'];
        }
    }
    if (isset($_POST['remove_pdf']) && $book['pdf_attachment']) {
        @unlink("../uploads/pdfs/{$book['pdf_attachment']}");
        $pdf_attachment = null;
    }

    $stmt = $pdo->prepare("
        UPDATE books SET 
        title = ?, author = ?, isbn = ?, category = ?, 
        quantity = ?, available = available + (? - quantity), 
        location = ?, description = ?, cover_image = ?, pdf_attachment = ?
        WHERE book_id = ?
    ");
    $stmt->execute([
        $title, $author, $isbn, $category, 
        $quantity, $quantity,
        $location, $description, $cover_image, $pdf_attachment,
        $book_id
    ]);

    $_SESSION['success'] = "Book updated successfully!";
    header("Location: view.php?id=$book_id");
    exit();
}

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Book</h5>
                <a href="view.php?id=<?= $book_id ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to View
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($book['category']) ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="<?= $book['quantity'] ?>" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($book['location']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Cover Image</label>
                            <input type="file" class="form-control" name="cover_image" accept="image/*">
                            <?php if ($book['cover_image']): ?>
                                <div class="mt-2">
                                    <img src="../uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" class="img-thumbnail" style="max-height: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_cover" id="removeCover">
                                        <label class="form-check-label" for="removeCover">Remove current cover</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Book Info PDF</label>
                            <input type="file" class="form-control" name="pdf_attachment" accept=".pdf">
                            <?php if ($book['pdf_attachment']): ?>
                                <div class="mt-2">
                                    <a href="../uploads/pdfs/<?= htmlspecialchars($book['pdf_attachment']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> View Current PDF
                                    </a>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_pdf" id="removePdf">
                                        <label class="form-check-label" for="removePdf">Remove current PDF</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($book['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>