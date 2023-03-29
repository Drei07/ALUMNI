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
    // public function editCourse($course_id, $course_name){
    //     // Check if the course name has actually changed
    //     $old_name_stmt = $this->runQuery('SELECT course FROM course WHERE id=:id');
    //     $old_name_stmt->execute(array(
    //         ":id" => $course_id,
    //     ));
    //     $old_name = $old_name_stmt->fetchColumn();
    //     if ($old_name == $course_name) {
    //         // Course name has not changed, don't need to update
    //         $_SESSION['status_title'] = 'Oops!';
    //         $_SESSION['status'] = 'No changes were made.';
    //         $_SESSION['status_code'] = 'error';
    //         $_SESSION['status_timer'] = 40000;
            
    //         header('Location: ../course');
    //         exit();
    //     }

    //     // Course name has changed, execute UPDATE query
    //     $stmt = $this->runQuery('UPDATE course SET course=:course WHERE id=:id');
    //     $exec = $stmt->execute(array(
    //         ":id"         => $course_id,
    //         ":course"     => $course_name,
    //     ));

    //     if ($exec) {
    //         $_SESSION['status_title'] = 'Success!';
    //         $_SESSION['status'] = 'Course successfully updated!';
    //         $_SESSION['status_code'] = 'success';
    //         $_SESSION['status_timer'] = 40000;
    //     } else {
    //         $_SESSION['status_title'] = 'Oops!';
    //         $_SESSION['status'] = 'Something went wrong, please try again!';
    //         $_SESSION['status_code'] = 'error';
    //         $_SESSION['status_timer'] = 100000;
    //     }

    //     header('Location: ../course');
    //     exit();
    // }


    // //delete course
    // public function deleteCourse($course_id){
    //     $disabled = "disabled";
    //     $stmt = $this->runQuery('UPDATE course SET status=:status WHERE id=:id');
    //     $exec = $stmt->execute(array(
    //         ":id"        => $course_id,
    //         ":status"   => $disabled,
    //     ));

    //     if ($exec) {
    //         $_SESSION['status_title'] = 'Success!';
    //         $_SESSION['status'] = 'Course successfully deleted!';
    //         $_SESSION['status_code'] = 'success';
    //         $_SESSION['status_timer'] = 40000;
    //     } else {
    //         $_SESSION['status_title'] = 'Oops!';
    //         $_SESSION['status'] = 'Something went wrong, please try again!';
    //         $_SESSION['status_code'] = 'error';
    //         $_SESSION['status_timer'] = 100000;
    //     }

    //     header('Location: ../course');
    //     exit();

    // }

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

// //edit
// if (isset($_POST['btn-edit-course'])) {
//     $course_id       = $_GET["id"];
//     $course_name     = trim($_POST['course_name']);

//     $edit_course = new Course();
//     $edit_course->editCourse($course_id, $course_name);
// }

// //delete
// if (isset($_GET['delete_course'])) {
//     $course_id = $_GET["id"];

//     $delete_course = new Course();
//     $delete_course->deleteCourse($course_id);
// }

// //activate
// if (isset($_GET['activate_course'])) {
//     $course_id = $_GET["id"];

//     $activate_course = new Course();
//     $activate_course->activateCourse($course_id);
// }
