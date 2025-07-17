<?php
include '../includes/auth.php';
include '../includes/header.php';

// Get popular books (most issued)
$stmt = $pdo->query("
    SELECT b.book_id, b.title, b.author, b.category, 
           COUNT(t.transaction_id) as issue_count,
           b.quantity, b.available
    FROM books b
    LEFT JOIN transactions t ON b.book_id = t.book_id
    GROUP BY b.book_id
    ORDER BY issue_count DESC, title
");
$popular_books = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Popular Books Report</h5>
                <button class="btn btn-sm btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Report
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Rank</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Times Issued</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($popular_books as $index => $book): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['category']) ?></td>
                            <td><?= $book['issue_count'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress w-100" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: <?= ($book['available'] / $book['quantity']) * 100 ?>%">
                                        </div>
                                    </div>
                                    <small class="ms-2">
                                        <?= $book['available'] ?>/<?= $book['quantity'] ?>
                                    </small>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <canvas id="popularBooksChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('popularBooksChart').getContext('2d');
    const labels = <?= json_encode(array_column($popular_books, 'title')) ?>;
    const data = <?= json_encode(array_column($popular_books, 'issue_count')) ?>;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.slice(0, 10),
            datasets: [{
                label: 'Number of Times Issued',
                data: data.slice(0, 10),
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 10 Most Popular Books'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>