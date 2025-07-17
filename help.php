<?php
include 'includes/auth.php';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Help & Support</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <!-- Getting Started -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <i class="bi bi-question-circle me-2"></i> Getting Started
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <h6>Welcome to School Library System</h6>
                                    <p>To get started with the system:</p>
                                    <ol>
                                        <li>Add books to your library using the Books section</li>
                                        <li>Register members (students and staff) in the Members section</li>
                                        <li>Issue books to members through the Transactions section</li>
                                        <li>Generate reports to track library activities</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Managing Books -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <i class="bi bi-book me-2"></i> Managing Books
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <h6>Adding New Books</h6>
                                    <p>To add new books to your library:</p>
                                    <ol>
                                        <li>Navigate to Books > Add Book</li>
                                        <li>Fill in the book details (title, author, ISBN, etc.)</li>
                                        <li>Specify the quantity available</li>
                                        <li>Click "Add Book" to save</li>
                                    </ol>
                                    
                                    <h6 class="mt-4">Searching Books</h6>
                                    <p>You can search books by:</p>
                                    <ul>
                                        <li>Title</li>
                                        <li>Author</li>
                                        <li>ISBN</li>
                                        <li>Category</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Management -->
                        <?php if ($_SESSION['role'] == 'Admin'): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    <i class="bi bi-people me-2"></i> User Management
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <h6>Adding System Users</h6>
                                    <p>Administrators can add new users to the system:</p>
                                    <ol>
                                        <li>Go to Admin > User Management</li>
                                        <li>Click "Add User"</li>
                                        <li>Enter username and password</li>
                                        <li>Assign appropriate role (Admin or Librarian)</li>
                                        <li>Click "Add User" to save</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Contact Support -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                    <i class="bi bi-headset me-2"></i> Contact Support
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <h6>Need Further Assistance?</h6>
                                    <p>If you need additional help with the system, please contact:</p>
                                    <ul class="list-unstyled">
                                        <li><i class="bi bi-envelope me-2"></i> support@schoollibrary.com</li>
                                        <li><i class="bi bi-telephone me-2"></i> +1 (555) 123-4567</li>
                                        <li><i class="bi bi-clock me-2"></i> Monday-Friday, 9AM-5PM</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>