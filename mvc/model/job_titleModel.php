<?php
class job_titleModel {
    private $db;
    public function __construct(){
        $this->db = \lib\Database::getInstance()->getConnection();
    }
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM job_titles");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
