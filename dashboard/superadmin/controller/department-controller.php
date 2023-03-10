<?php
session_start();
include_once __DIR__.'/../../../database/dbconfig.php';

class Department {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //update avatar to default
    public function addDepartment($name){
        $stmt = $this->runQuery('INSERT INTO department (name) VALUES (:name)');
        $exec = $stmt->execute(array(
            ":name"  => $name,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Avatar successfully updated to default';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../department');
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}


if (isset($_POST['btn-add'])) {
    $name     = trim($_POST['department']);

    $department = new Department();
    $department->addDepartment($name);
}

?>