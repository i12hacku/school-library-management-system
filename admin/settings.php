<?php
include '../includes/auth.php';
// checkAdmin();
include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">System Settings</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Library Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Library Name</label>
                            <input type="text" class="form-control" value="School Library">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fine Per Day (₹)</label>
                            <input type="number" class="form-control" value="5">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="mb-3 text-primary">Loan Periods</h6>
                        <div class="mb-3">
                            <label class="form-label">Students (Days)</label>
                            <input type="number" class="form-control" value="14">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Staff (Days)</label>
                            <input type="number" class="form-control" value="30">
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="mb-3 text-primary">System Configuration</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="autoBackup" checked>
                                <label class="form-check-label" for="autoBackup">Weekly Auto Backup</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Items Per Page</label>
                            <select class="form-select">
                                <option>10</option>
                                <option selected>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-light me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>