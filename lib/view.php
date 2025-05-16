<?php
class View {
    public function render($viewPath, $data = []) {
        // Extract data for use in view
        extract($data);
        
        // Default values
        $pageTitle  = $data['pageTitle']  ?? 'IBSC';
        $activeMenu = $data['activeMenu'] ?? '';

        if (strpos($viewPath, 'auth/') !== 0) {
            // e.g.:
            require __DIR__ . '/../app/views/layouts/header.php';
            require __DIR__ . '/../app/views/layouts/sidebar.php'; 
        }

        // Include the requested view file
        require __DIR__ . '/../app/views/' . $viewPath . '.php';
        
        echo '    </div>';
        echo '  </section>';
        echo '</div>';
        
        if (strpos($viewPath, 'auth/') !== 0) {
            // e.g.:
            require __DIR__ . '/../app/views/layouts/footer.php';
        }
    }
}
