<?php
include_once '../../../configuration/settings-configuration.php';
include_once __DIR__.'/../../../database/dbconfig.php';

class Company {
    private $conn;

    public function __construct() 
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    //add course
    public function addCompany($user_id, $company_name, $company_address, $company_phone_number, $company_description, $company_logo, $company_email){
        $folder = "../../../src/img/" . basename($company_logo);
        $stmt = $this->runQuery('INSERT INTO company (user_id, company_name, company_address, company_email, company_phone_number, company_description, company_logo) VALUES (:user_id, :company_name, :company_address, :company_email, :company_phone_number, :company_description, :company_logo)');
        $exec = $stmt->execute(array(
            ":user_id"                  => $user_id,
            ":company_name"             => $company_name,
            ":company_address"          => $company_address,
            ":company_email"            => $company_email,
            ":company_phone_number"     => $company_phone_number,
            ":company_description"      => $company_description,
            ":company_logo"             => $company_logo,
        ));

        if ($exec && move_uploaded_file($_FILES['company_logo']['tmp_name'], $folder)) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Company registered successfully';
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
if (isset($_POST['btn-add-company'])) {
    $user_id                = $_GET['id'];
    $company_name           = trim($_POST['company_name']);
    $company_address        = trim($_POST['company_address']);
    $company_phone_number   = trim($_POST['company_phone_number']);
    $company_description    = trim($_POST['company_description']);
    $company_logo           = $_FILES['company_logo']['name'];
    $company_email          = trim($_POST['company_email']);


    $add_company = new Company();
    $add_company->addCompany($user_id, $company_name, $company_address, $company_phone_number, $company_description, $company_logo, $company_email);
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
