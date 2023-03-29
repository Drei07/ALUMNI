<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class APPLICANTS {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //accept course
    public function acceptApplicants($applicant_id){
        $verified = "verified";
        $stmt = $this->runQuery('UPDATE application SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $applicant_id,
            ":status"   => $verified,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Applicant successfully accepted!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../');
        exit();

    }

    //rejected course
    public function rejectApplicants($applicant_id){
        $rejected = "rejected";
        $stmt = $this->runQuery('UPDATE application SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $applicant_id,
            ":status"   => $rejected,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Applicant successfully rejected!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../');
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//ACCEPT
if (isset($_GET['accept_applicants'])) {
    $applicant_id = $_GET["applicants_id"];

    $accept_applicant = new APPLICANTS();
    $accept_applicant->acceptApplicants($applicant_id);
}

//activate
if (isset($_GET['reject_applicants'])) {
    $applicant_id = $_GET["applicants_id"];

    $reject_applicant = new APPLICANTS();
    $reject_applicant->rejectApplicants($applicant_id);
}


?>