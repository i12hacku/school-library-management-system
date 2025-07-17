<?php
include '../includes/auth.php';
include '../includes/header.php';

// Get filter parameters
$member_id = $_GET['member_id'] ?? '';
$book_id = $_GET['book_id'] ?? '';
$status = $_GET['status'] ?? '';

// Build WHERE clause
$where = [];
$params = [];

if (!empty($member_id)) {
    $where[] = "t.member_id = ?";
    $params[] = $member_id;
}

if (!empty($book_id)) {
    $where[] = "t.book_id = ?";
    $params[] = $book_id;
}

if (!empty($status)) {
    $where[] = "t.status = ?";
    $params[] = $status;
}

$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";

// Get transaction history
$stmt = $pdo->prepare("
    SELECT t.*, b.title, b.isbn, m.name as member_name, m.member_type
    FROM transactions t
    JOIN books b ON t.book_id = b.book_id
    JOIN members m ON t.member_id = m.member_id
    $where_clause
    ORDER BY t.issue_date DESC
");
$stmt->execute($params);
$transactions = $stmt->fetchAll();

// Get members and books for filters
$members = $pdo->query("SELECT member_id, name FROM members ORDER BY name")->fetchAll();
$books = $pdo->query("SELECT book_id, title FROM books ORDER BY title")->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaction History</h5>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Member</label>
                        <select class="form-select" name="member_id">
                            <option value="">All Members</option>
                            <?php foreach ($members as $m): ?>
                            <option value="<?= $m['member_id'] ?>" <?= $m['member_id'] == $member_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Book</label>
                        <select class="form-select" name="book_id">
                            <option value="">All Books</option>
                            <?php foreach ($books as $b): ?>
                            <option value="<?= $b['book_id'] ?>" <?= $b['book_id'] == $book_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['title']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            <option value="Issued" <?= $status == 'Issued' ? 'selected' : '' ?>>Issued</option>
                            <option value="Returned" <?= $status == 'Returned' ? 'selected' : '' ?>>Returned</option>
                            <option value="Overdue" <?= $status == 'Overdue' ? 'selected' : '' ?>>Overdue</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Issued</th>
                            <th>Due</th>
                            <th>Returned</th>
                            <th>Status</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= $t['transaction_id'] ?></td>
                            <td>
                                <div><?= htmlspecialchars($t['title']) ?></div>
                                <small class="text-muted">ISBN: <?= $t['isbn'] ?></small>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($t['member_name']) ?></div>
                                <small class="badge bg-secondary"><?= $t['member_type'] ?></small>
                            </td>
                            <td><?= date('M j, Y', strtotime($t['issue_date'])) ?></td>
                            <td><?= date('M j, Y', strtotime($t['due_date'])) ?></td>
                            <td><?= $t['return_date'] ? date('M j, Y', strtotime($t['return_date'])) : '-' ?></td>
                            <td>
                                <?php 
                                $status_class = [
                                    'Issued' => 'primary',
                                    'Returned' => 'success',
                                    'Overdue' => 'danger'
                                ][$t['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $status_class ?>"><?= $t['status'] ?></span>
                            </td>
                            <td><?= $t['fine'] > 0 ? '₹' . $t['fine'] : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No transactions found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Issue a book
    if (isset($_POST['book_id'], $_POST['member_id'])) {
        $book_id = $_POST['book_id'];
        $member_id = $_POST['member_id'];
        $due_date = date('Y-m-d', strtotime('+14 days'));

        $stmt = $pdo->prepare("SELECT available FROM books WHERE book_id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();

        if ($book && $book['available'] > 0) {
            $pdo->prepare("INSERT INTO transactions (book_id, member_id, issue_date, due_date, status) VALUES (?, ?, CURDATE(), ?, 'Issued')")
                ->execute([$book_id, $member_id, $due_date]);
            $pdo->prepare("UPDATE books SET available = available - 1 WHERE book_id = ?")
                ->execute([$book_id]);
            $_SESSION['success'] = "Book issued!";
        } else {
            $_SESSION['error'] = "Book not available!";
        }
        header("Location: history.php");
        exit();
    }

    // Return a book
    if (isset($_POST['transaction_id'])) {
        $transaction_id = $_POST['transaction_id'];
        $return_date = date('Y-m-d');

        // Get transaction info
        $stmt = $pdo->prepare("SELECT book_id, due_date FROM transactions WHERE transaction_id = ?");
        $stmt->execute([$transaction_id]);
        $trans = $stmt->fetch();

        if ($trans) {
            $fine = 0;
            if ($return_date > $trans['due_date']) {
                $days_late = (strtotime($return_date) - strtotime($trans['due_date'])) / 86400;
                $fine = $days_late * 10; // Example: 10 currency units per day late
            }

            $pdo->prepare("UPDATE transactions SET return_date = ?, status = 'Returned', fine = ? WHERE transaction_id = ?")
                ->execute([$return_date, $fine, $transaction_id]);
            $pdo->prepare("UPDATE books SET available = available + 1 WHERE book_id = ?")
                ->execute([$trans['book_id']]);
        }
        header("Location: history.php");
        exit();
    }
}
include '../includes/footer.php';