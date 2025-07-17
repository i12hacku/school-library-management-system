<?php
include '../includes/auth.php';

// Define the category list
$categories = [
    'අධ්‍යාපනික',
    'පරිවර්තන',
    'නවකතා',
    'සම්මාන කාව්‍යය',
    'බෞද්ධ',
    'කෙටිකතා',
    'ළමා',
    'ඉතිහාසය හා පුරාවිද්‍යාව',
    'චරිතාපදාන',
    'රහස් පරීක්ෂණ කතා',
    'ත්‍රාස්‍යජනක',
    'දාර්ශනික',
    'රුසියානු කතා',
    'විවිධ',
    'සඟරා',
    'කලාව හා සෞන්දර්යාත්මක',
    'දේශපාලන',
    'ආගමික',
    'ක්‍රීඩා',
    'අපරාධ',
    'ආදර කතා'
];

// Handle both single book addition and bulk import
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_FILES['import_file'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Handle cover image upload
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/covers/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = uniqid() . "_" . basename($_FILES['cover_image']['name']);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            $cover_image = $filename;
        } else {
            $_SESSION['error'] = "Failed to upload cover image.";
        }
    }

    $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, category, quantity, available, location, description, cover_image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $title, $author, $isbn, $category, $quantity, $quantity, $location, $description, $cover_image
    ]);
    $_SESSION['success'] = "Book added successfully!";
    header("Location: manage.php");
    exit();
}

// ...bulk import logic if needed...

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Add New Book</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Single Book Entry</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Cover Image</label>
                            <input type="file" class="form-control" name="cover_image" accept="image/*">
                            <small class="text-muted">Max 2MB (JPG, PNG, GIF)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="1" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Book</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Bulk Import Books</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select File (CSV/Excel) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="import_file" accept=".csv,.xlsx,.xls" required>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6>File Format Requirements:</h6>
                            <ul class="mb-0">
                                <li>First row should be headers (will be skipped)</li>
                                <li>Columns should be in this order: Title, Author, ISBN, Category, Quantity, Location, Description</li>
                                <li>Minimum required fields: Title and Author</li>
                                <li>Imported books will use default cover image</li>
                                <li><a href="sample_books.csv" download>Download sample CSV file</a></li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Import Books</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>