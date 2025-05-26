<?php
class ProfileController extends Controller {


    // Main records page showing all tabs
    public function view() {
        $this->isAuthenticated();
        
        // Change this line to use the correct view path
        $this->view->render('employee/profile');
    }
}