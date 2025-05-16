<?php
// EmployeeModel.php
class EmployeeModel extends Model {
    public function getAll() {
        return $this->db
                ->query("SELECT * FROM employees_info ORDER BY lastname ASC")
                ->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getAllWithRelations() {
        // Since all data is in one table now, we can just return all employees
        return $this->getAll();
    }
    
    public function getByIdWithRelations($id) {
        // Since all data is in one table now, we can just return the employee
        return $this->getById($id);
    }
    
    public function insertEmployee($data) {
        // First verify the IDs exist before trying to insert
        $location = $this->getLocationName($data['location_id']);
        $department = $this->getDepartmentName($data['department_id']);
        $job_title = $this->getJobTitleName($data['job_title_id']);
        
        // If any of these are 'Unknown', there's an issue with the reference data
        if ($location == 'Unknown' || $department == 'Unknown' || $job_title == 'Unknown') {
            // Log error or handle the case where reference data is missing
            error_log("Failed to insert employee: Invalid reference data");
            return false;
        }
        
        $sql = "INSERT INTO employees_info 
                (firstname, lastname, middlename, suffix, location, department, job_title, evaluation, created_at, updated_at) 
                VALUES 
                (:firstname, :lastname, :middlename, :suffix, :location, :department, :job_title, :evaluation, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        
        try {
            $success = $stmt->execute([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'middlename' => isset($data['middlename']) ? $data['middlename'] : null,
                'suffix' => isset($data['suffix']) ? $data['suffix'] : null,
                'location' => $location,
                'department' => $department,
                'job_title' => $job_title,
                'evaluation' => isset($data['evaluation']) ? $data['evaluation'] : 0
            ]);
            
            if ($success) {
                return $this->db->lastInsertId();
            }
        } catch (PDOException $e) {
            // Log the specific database error
            error_log("Database error in insertEmployee: " . $e->getMessage());
        }
        
        return false;
    }
    
    public function updateEmployee($id, $data) {
        // Get the text values for location, department, and job_title
        $location = $this->getLocationName($data['location_id']);
        $department = $this->getDepartmentName($data['department_id']);
        $job_title = $this->getJobTitleName($data['job_title_id']);
        
        $sql = "UPDATE employees_info SET 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    middlename = :middlename, 
                    suffix = :suffix,
                    location = :location,
                    department = :department,
                    job_title = :job_title,
                    evaluation = :evaluation,
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'middlename' => $data['middlename'] ?? null,
            'suffix' => $data['suffix'] ?? null,
            'location' => $location,
            'department' => $department,
            'job_title' => $job_title,
            'evaluation' => $data['evaluation'] ?? 0
        ]);
    }
    
    public function deleteEmployee($id) {
        $stmt = $this->db->prepare("DELETE FROM employees_info WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM employees_info");
        $result = $stmt->fetch();
        return $result['count'];
    }

    public function getRecent($limit = 5) {
        $sql = "SELECT * FROM employees_info ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        // Bind as integer to avoid being quoted as string
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMonthlyStats() {
        $sql = "SELECT 
                    MONTH(created_at) as month, 
                    COUNT(*) as count 
                FROM employees_info 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                GROUP BY MONTH(created_at) 
                ORDER BY month";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getFullNameById($id) {
        $employee = $this->getById($id);
        if (!$employee) return '';
        
        $fullname = $employee['firstname'];
        if (!empty($employee['middlename'])) {
            $fullname .= ' ' . $employee['middlename'];
        }
        $fullname .= ' ' . $employee['lastname'];
        if (!empty($employee['suffix'])) {
            $fullname .= ' ' . $employee['suffix'];
        }
        return $fullname;
    }
    
    // Helper methods to get names from IDs
    private function getLocationName($location_id) {
        try {
            $sql = "SELECT location FROM locations WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$location_id]);
            $result = $stmt->fetch();
            return $result ? $result['location'] : 'Unknown';
        } catch (PDOException $e) {
            error_log("Error in getLocationName: " . $e->getMessage());
            return 'Unknown';
        }
    }

    private function getDepartmentName($department_id) {
        try {
            $sql = "SELECT department FROM departments WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$department_id]);
            $result = $stmt->fetch();
            return $result ? $result['department'] : 'Unknown';
        } catch (PDOException $e) {
            error_log("Error in getDepartmentName: " . $e->getMessage());
            return 'Unknown';
        }
    }

    private function getJobTitleName($job_title_id) {
        try {
            $sql = "SELECT job_title FROM job_titles WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$job_title_id]);
            $result = $stmt->fetch();
            return $result ? $result['job_title'] : 'Unknown';
        } catch (PDOException $e) {
            error_log("Error in getJobTitleName: " . $e->getMessage());
            return 'Unknown';
        }
    }
}