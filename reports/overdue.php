<?php
include '../includes/auth.php';
include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Overdue Books</h2>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT b.title, m.name, t.due_date, 
                               DATEDIFF(CURDATE(), t.due_date) AS days_overdue,
                               DATEDIFF(CURDATE(), t.due_date) * 5 AS calculated_fine
                        FROM transactions t
                        JOIN books b ON t.book_id = b.book_id
                        JOIN members m ON t.member_id = m.member_id
                        WHERE t.status = 'Issued' AND t.due_date < CURDATE()
                        ORDER BY t.due_date ASC
                    ");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['title']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['due_date']}</td>
                            <td>{$row['days_overdue']}</td>
                            <td>₹{$row['calculated_fine']}</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>