        </div> <!-- End of container-fluid -->
    </main> <!-- End of main-content -->
    
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-muted">
                    &copy; <?php echo date('Y'); ?> School Library System by Pulitha (13-Tech 2025)
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-primary">v1.0</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/header.js"></script>
    
    <!-- Page-specific JS -->
    <?php if (isset($page_js)): ?>
        <script src="assets/js/<?php echo $page_js; ?>"></script>
    <?php endif; ?>
</body>
</html>