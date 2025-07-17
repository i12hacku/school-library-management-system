<?php
include '../includes/auth.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class_grade = $_POST['class_grade'];
    $member_type = $_POST['member_type'];
    
    $stmt = $pdo->prepare("INSERT INTO members (name, email, class_grade, member_type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $class_grade, $member_type]);
    
    $_SESSION['success'] = "Member added successfully!";
    header("Location: manage.php");
    exit();
}
?>

<div class="container mt-4">
    <h2>Add New Member</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Class/Grade</label>
            <input type="text" class="form-control" name="class_grade">
        </div>
        <div class="mb-3">
            <label class="form-label">Member Type</label>
            <select class="form-select" name="member_type" required>
                <option value="Student">Student</option>
                <option value="Teacher">Teacher</option>
                <option value="Staff">Staff</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Member</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>