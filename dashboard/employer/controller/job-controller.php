<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class JOB {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add job
    public function addJob($user_id, $company_id, $job_title, $job_workplace_type, $job_location, $job_type, $job_description){
        $stmt = $this->runQuery('INSERT INTO jobs (user_id, company_id, job_title, job_workplace_type, job_location, job_type, job_description) VALUES (:user_id, :company_id, :job_title, :job_workplace_type, :job_location, :job_type, :job_description)');
        $exec = $stmt->execute(array(
            ":user_id"              => $user_id,
            ":company_id"           => $company_id,
            ":job_title"            => $job_title,
            ":job_workplace_type"   => $job_workplace_type,
            ":job_location"         => $job_location,
            ":job_type"             => $job_type,
            ":job_description"      => $job_description,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Job is posted successfully';
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
    
    // //edit course
    public function editJob($jobs_id, $job_title, $job_workplace_type, $job_location, $job_type, $job_description){
        // Course name has changed, execute UPDATE query
        $stmt = $this->runQuery('UPDATE jobs SET job_title=:job_title, job_workplace_type=:job_workplace_type, job_location=:job_location, job_type=:job_type, job_description=:job_description WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"                   => $jobs_id,
            ":job_title"            => $job_title,
            ":job_workplace_type"   => $job_workplace_type,
            ":job_location"         => $job_location,
            ":job_type"             => $job_type,
            ":job_description"      => $job_description,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Job is successfully updated!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header("Location: ../view-jobs?id=$jobs_id");
        exit();
    }


    //delete course
    public function deleteJobs($jobs_id){
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE jobs SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $jobs_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Jobs successfully delete!';
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


    //delete course
    public function activateJobs($jobs_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE jobs SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $jobs_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Jobs successfully activated!';
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

    // //activate course
    // public function activateCourse($course_id){
    //     $active = "active";
    //     $stmt = $this->runQuery('UPDATE course SET status=:status WHERE id=:id');
    //     $exec = $stmt->execute(array(
    //         ":id"        => $course_id,
    //         ":status"   => $active,
    //     ));

    //     if ($exec) {
    //         $_SESSION['status_title'] = 'Success!';
    //         $_SESSION['status'] = 'Course successfully activated!';
    //         $_SESSION['status_code'] = 'success';
    //         $_SESSION['status_timer'] = 40000;
    //     } else {
    //         $_SESSION['status_title'] = 'Oops!';
    //         $_SESSION['status'] = 'Something went wrong, please try again!';
    //         $_SESSION['status_code'] = 'error';
    //         $_SESSION['status_timer'] = 100000;
    //     }

    //     header('Location: ../course');
    // }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

}

//add
if (isset($_POST['btn-post-job'])) {
    $user_id                = $_GET['id'];
    $company_id             = $_GET['company_id'];
    $job_title              = trim($_POST['job_title']);
    $job_workplace_type     = trim($_POST['job_workplace_type']);
    $job_location           = trim($_POST['job_location']);
    $job_type               = trim($_POST['job_type']);
    $job_description        = trim($_POST['job_description']);


    $add_job = new JOB();
    $add_job->addJob($user_id, $company_id, $job_title, $job_workplace_type, $job_location, $job_type, $job_description);

}

//edit
if (isset($_POST['btn-edit-job'])) {
    $jobs_id                = $_GET['jobs_id'];
    $job_title              = trim($_POST['job_title']);
    $job_workplace_type     = trim($_POST['job_workplace_type']);
    $job_location           = trim($_POST['job_location']);
    $job_type               = trim($_POST['job_type']);
    $job_description        = trim($_POST['job_description']);


    $edit_job = new JOB();
    $edit_job->editJob($jobs_id, $job_title, $job_workplace_type, $job_location, $job_type, $job_description);

}

//delete
if (isset($_GET['delete_jobs'])) {
    $jobs_id                = $_GET['jobs_id'];

    $delete_jobs = new JOB();
    $delete_jobs->deleteJobs($jobs_id);
}

// //remove jobs
if (isset($_GET['activate_jobs'])) {
    $jobs_id     = $_GET['id'];

    $activate_jobs = new JOB();
    $activate_jobs->activateJobs($jobs_id);
}
// //activate
// if (isset($_GET['activate_course'])) {
//     $course_id = $_GET["id"];

//     $activate_course = new Course();
//     $activate_course->activateCourse($course_id);
// }
