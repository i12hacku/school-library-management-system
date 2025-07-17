<?php
include '../includes/auth.php';

// Fetch all books that are currently issued
$stmt = $pdo->prepare("SELECT b.book_id, b.title FROM books b
    JOIN transactions t ON b.book_id = t.book_id
    WHERE t.status = 'Issued'
    GROUP BY b.book_id, b.title");
$stmt->execute();
$books = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'] ?? null;

    if ($book_id) {
        // Find active transaction
        $stmt = $pdo->prepare("SELECT * FROM transactions 
                              WHERE book_id = ? AND status = 'Issued' 
                              ORDER BY issue_date DESC LIMIT 1");
        $stmt->execute([$book_id]);
        $transaction = $stmt->fetch();

        if ($transaction) {
            // Calculate fine if overdue
            $fine = 0;
            $return_date = date('Y-m-d');
            $due_date = $transaction['due_date'];

            if (strtotime($return_date) > strtotime($due_date)) {
                $days_late = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
                $fine = $days_late * 5; // ₹5 per day fine
            }

            // Update transaction
            $stmt = $pdo->prepare("UPDATE transactions 
                                  SET return_date = ?, fine = ?, status = 'Returned' 
                                  WHERE transaction_id = ?");
            $stmt->execute([$return_date, $fine, $transaction['transaction_id']]);

            // Update book availability
            $stmt = $pdo->prepare("UPDATE books SET available = available + 1 WHERE book_id = ?");
            $stmt->execute([$book_id]);

            $_SESSION['success'] = "Book returned successfully!";
            if ($fine > 0) {
                $_SESSION['success'] .= " Fine: ₹$fine";
            }
        } else {
            $_SESSION['error'] = "No active transaction found for this book";
        }
    } else {
        $_SESSION['error'] = "Please select a book to return.";
    }
    header("Location: return.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Return Books</h2>
    
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
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Book</label>
                    <select name="book_id" class="form-select" required>
                        <option value="">Select Book</option>
                        <?php foreach ($books as $b): ?>
                            <option value="<?= $b['book_id'] ?>"><?= htmlspecialchars($b['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Return Book</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>