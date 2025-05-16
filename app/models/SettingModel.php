<?php
class SettingModel extends Model {
    // Job Title Methods
    public function getAllJobTitles() {
        $stmt = $this->db->query("SELECT * FROM job_titles ORDER BY job_title");
        return $stmt->fetchAll();
    }
    
    public function addJobTitle($title) {
        $stmt = $this->db->prepare("INSERT INTO job_titles (job_title) VALUES (?)");
        return $stmt->execute([$title]);
    }
    
    public function getJobTitleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM job_titles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateJobTitle($id, $title) {
        $stmt = $this->db->prepare("UPDATE job_titles SET job_title = ? WHERE id = ?");
        return $stmt->execute([$title, $id]);
    }
    
    public function deleteJobTitle($id) {
        $stmt = $this->db->prepare("DELETE FROM job_titles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Department Methods
    public function getAllDepartments() {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY department");
        return $stmt->fetchAll();
    }
    
    public function addDepartment($department) {
        $stmt = $this->db->prepare("INSERT INTO departments (department) VALUES (?)");
        return $stmt->execute([$department]);
    }
    
    public function getDepartmentById($id) {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateDepartment($id, $department) {
        $stmt = $this->db->prepare("UPDATE departments SET department = ? WHERE id = ?");
        return $stmt->execute([$department, $id]);
    }
    
    public function deleteDepartment($id) {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Location Methods
    public function getAllLocations() {
        $stmt = $this->db->query("SELECT * FROM locations ORDER BY location");
        return $stmt->fetchAll();
    }
    
    public function addLocation($location) {
        $stmt = $this->db->prepare("INSERT INTO locations (location) VALUES (?)");
        return $stmt->execute([$location]);
    }
    
    public function getLocationById($id) {
        $stmt = $this->db->prepare("SELECT * FROM locations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateLocation($id, $location) {
        $stmt = $this->db->prepare("UPDATE locations SET location = ? WHERE id = ?");
        return $stmt->execute([$location, $id]);
    }
    
    public function deleteLocation($id) {
        $stmt = $this->db->prepare("DELETE FROM locations WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Employee Methods
    public function getAllEmployees() {
        $stmt = $this->db->query("SELECT * FROM employees_info ORDER BY lastname, firstname");
        return $stmt->fetchAll();
    }
    
    public function addEmployee($data) {
        $stmt = $this->db->prepare("INSERT INTO employees_info 
            (firstname, lastname, middlename, suffix, location, department, job_title, evaluation, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        return $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['middlename'] ?? null,
            $data['suffix'] ?? null,
            $data['location'],
            $data['department'],
            $data['job_title'],
            $data['evaluation'] ?? 0.0
        ]);
    }
    
    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateEmployee($data) {
        $stmt = $this->db->prepare("UPDATE employees_info SET 
            firstname = ?, 
            lastname = ?, 
            middlename = ?, 
            suffix = ?, 
            location = ?, 
            department = ?, 
            job_title = ?,
            evaluation = ?,
            updated_at = NOW()
            WHERE id = ?");
        
        return $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['middlename'] ?? null,
            $data['suffix'] ?? null,
            $data['location'],
            $data['department'],
            $data['job_title'],
            $data['evaluation'] ?? 0.0,
            $data['id']
        ]);
    }
    
    public function deleteEmployee($id) {
        $stmt = $this->db->prepare("DELETE FROM employees_info WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // KRA Methods
    public function getAllKras() {
        $stmt = $this->db->query("SELECT * FROM kras ORDER BY kra");
        return $stmt->fetchAll();
    }
    
    public function addKra($kra) {
        $stmt = $this->db->prepare("INSERT INTO kras (kra) VALUES (?)");
        return $stmt->execute([$kra]);
    }
    
    public function getKraById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kras WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateKra($id, $kra) {
        $stmt = $this->db->prepare("UPDATE kras SET kra = ? WHERE id = ?");
        return $stmt->execute([$kra, $id]);
    }
    
    public function deleteKra($id) {
        $stmt = $this->db->prepare("DELETE FROM kras WHERE id = ?");
        return $stmt->execute([$id]);
    }
}