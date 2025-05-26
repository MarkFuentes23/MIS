
    <!-- End of Footer -->
    
    </div>
    <!-- End of Content Wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable();
        });
    </script>

    
  <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
  </script>

    
    <!-- Process Flash Messages with SweetAlert -->
    <?php if (isset($_SESSION['flash'])) : ?>
        <script>
            Swal.fire({
                title: "<?= $_SESSION['flash']['title'] ?>",
                text: "<?= $_SESSION['flash']['message'] ?>",
                icon: "<?= $_SESSION['flash']['type'] ?>",
                confirmButtonColor: '#4e73df'
            });
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <!-- Custom scripts for specific pages -->
    <?php if (isset($pageScripts) && is_array($pageScripts)) : ?>
        <?php foreach ($pageScripts as $script) : ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
</body>
</html>