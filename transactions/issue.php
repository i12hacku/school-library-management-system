<?php
include '../includes/auth.php';

// Fetch all books and members for the dropdowns
$stmt = $pdo->prepare("SELECT book_id, title FROM books WHERE available > 0");
$stmt->execute();
$books = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT member_id, name FROM members");
$stmt->execute();
$members = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'] ?? null;
    $member_id = $_POST['member_id'] ?? null;
    $due_date = date('Y-m-d', strtotime('+14 days')); // 2 weeks from today

    if ($book_id && $member_id) {
        // Check book availability
        $stmt = $pdo->prepare("SELECT available FROM books WHERE book_id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();

        if ($book && $book['available'] > 0) {
            // Create transaction
            $stmt = $pdo->prepare("INSERT INTO transactions (book_id, member_id, issue_date, due_date, status) 
                                  VALUES (?, ?, CURDATE(), ?, 'Issued')");
            $stmt->execute([$book_id, $member_id, $due_date]);

            // Update book availability
            $stmt = $pdo->prepare("UPDATE books SET available = available - 1 WHERE book_id = ?");
            $stmt->execute([$book_id]);

            $_SESSION['success'] = "Book issued successfully! Due date: $due_date";
        } else {
            $_SESSION['error'] = "Book not available for issuing";
        }
    } else {
        $_SESSION['error'] = "Please select both a book and a member.";
    }
    header("Location: issue.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Issue Books</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" class="row g-2 mb-4">
                <div class="col-md-4">
                    <select name="book_id" class="form-select" required>
                        <option value="">Select Book</option>
                        <?php foreach ($books as $b): ?>
                            <option value="<?= $b['book_id'] ?>"><?= htmlspecialchars($b['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="member_id" class="form-select" required>
                        <option value="">Select Member</option>
                        <?php foreach ($members as $m): ?>
                            <option value="<?= $m['member_id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">Issue Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>