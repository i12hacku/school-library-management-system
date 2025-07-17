<?php
include '../includes/auth.php';


if (!isset($_GET['id'])) {
    header("Location: manage.php");
    exit();
}

$member_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$member_id]);
$member = $stmt->fetch();

if (!$member) {
    header("Location: manage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class_grade = $_POST['class_grade'];
    $member_type = $_POST['member_type'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("
        UPDATE members SET 
        name = ?, email = ?, class_grade = ?, member_type = ?, status = ?
        WHERE member_id = ?
    ");
    $stmt->execute([$name, $email, $class_grade, $member_type, $status, $member_id]);
    
    $_SESSION['success'] = "Member updated successfully!";
    header("Location: view.php?id=$member_id");
    exit();
}
include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Member</h5>
                <a href="view.php?id=<?= $member_id ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to View
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($member['name']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($member['email']) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Class/Grade</label>
                            <input type="text" class="form-control" name="class_grade" value="<?= htmlspecialchars($member['class_grade']) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Member Type</label>
                            <select class="form-select" name="member_type" required>
                                <option value="Student" <?= $member['member_type'] == 'Student' ? 'selected' : '' ?>>Student</option>
                                <option value="Teacher" <?= $member['member_type'] == 'Teacher' ? 'selected' : '' ?>>Teacher</option>
                                <option value="Staff" <?= $member['member_type'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="Active" <?= $member['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $member['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
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

<?php include '../../includes/footer.php'; ?>