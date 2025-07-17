<?php
include '../includes/auth.php';
include '../includes/header.php';

// Handle member deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM members WHERE member_id = ?");
        $stmt->execute([$delete_id]);
        $_SESSION['success'] = "Member deleted successfully";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting member: " . $e->getMessage();
    }
    header("Location: manage.php");
    exit();
}

// Get all members
$stmt = $pdo->query("SELECT * FROM members ORDER BY name");
$members = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manage Members</h5>
                <a href="add.php" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus"></i> Add Member
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Class/Grade</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?= $member['member_id'] ?></td>
                            <td><?= htmlspecialchars($member['name']) ?></td>
                            <td><?= $member['member_type'] ?></td>
                            <td><?= htmlspecialchars($member['class_grade']) ?></td>
                            <td>
                                <span class="badge bg-<?= $member['status'] == 'Active' ? 'success' : 'danger' ?>">
                                    <?= $member['status'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="view.php?id=<?= $member['member_id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $member['member_id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="manage.php?delete=<?= $member['member_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this member?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>