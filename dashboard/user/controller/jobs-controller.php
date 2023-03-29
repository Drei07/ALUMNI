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

    // Save Jobs
    public function saveJob($user_id, $jobs_id){
        $stmt = $this->runQuery('SELECT * FROM save_jobs WHERE user_id=:user_id AND jobs_id=:jobs_id');
        $stmt->execute(array(
            ":user_id"  => $user_id,
            ":jobs_id"  => $jobs_id,
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'You already saved this job';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        } else {
            $stmt = $this->runQuery('INSERT INTO save_jobs (user_id, jobs_id) VALUES (:user_id, :jobs_id)');
            $exec = $stmt->execute(array(
                ":user_id"  => $user_id,
                ":jobs_id"  => $jobs_id,
            ));
    
            if ($exec) {
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Save successfully';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }
    
        header('Location: ../');
    }
    
    public function appliedJobs($user_id, $jobs_id, $resume){
        $folder = "../../../src/pdf/" . basename($resume);
        $stmt = $this->runQuery('SELECT * FROM application WHERE user_id=:user_id AND jobs_id=:jobs_id');
        $stmt->execute(array(
            ":user_id"  => $user_id,
            ":jobs_id"  => $jobs_id,
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'You already applied to this job!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        } else {
            $file_type = pathinfo($folder, PATHINFO_EXTENSION);
            if (strtolower($file_type) != 'pdf') {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Only PDF files are allowed!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            } else {
                $stmt = $this->runQuery('INSERT INTO application (user_id, jobs_id, resume) VALUES (:user_id, :jobs_id, :resume)');
                $exec = $stmt->execute(array(
                    ":user_id"  => $user_id,
                    ":jobs_id"  => $jobs_id,
                    ":resume"   => $resume
                ));
        
                if ($exec && move_uploaded_file($_FILES['resume']['tmp_name'], $folder)) {
                    $_SESSION['status_title'] = 'Success!';
                    $_SESSION['status'] = 'Applied succesfully';
                    $_SESSION['status_code'] = 'success';
                    $_SESSION['status_timer'] = 40000;
                } else {
                    $_SESSION['status_title'] = 'Oops!';
                    $_SESSION['status'] = 'Something went wrong, please try again!';
                    $_SESSION['status_code'] = 'error';
                    $_SESSION['status_timer'] = 100000;
                }
            }
        }
    
        header("Location: ../view-jobs?id=$jobs_id");
    }
    

    // //remove save jobs
    public function removeSaveJobs($save_jobs_id){
        $disabled = "disabled";
        $stmt = $this->runQuery('UPDATE save_jobs SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $save_jobs_id,
            ":status"   => $disabled,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = ' Remove successfully!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../save-jobs');
        exit();

    }


    // //activate save jobs
    public function activateSaveJobs($save_jobs_id){
        $active = "active";
        $stmt = $this->runQuery('UPDATE save_jobs SET status=:status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $save_jobs_id,
            ":status"   => $active,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = ' Save successfully!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../archived-jobs');
        exit();

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

//save jobs
if (isset($_GET['save_jobs'])) {
    $user_id     = $_GET['id'];
    $jobs_id     = $_GET['jobs_id'];

    $save_jobs = new JOB();
    $save_jobs->saveJob($user_id, $jobs_id);
}

//applied 
if (isset($_POST['btn-add-resume'])) {
    $user_id     = $_GET['id'];
    $jobs_id     = $_GET['jobs_id'];
    $resume      = $_FILES['resume']['name'];;

    $applied_jobs = new JOB();
    $applied_jobs->appliedJobs($user_id, $jobs_id, $resume);
}

// //remove jobs
if (isset($_GET['remove_jobs'])) {
    $save_jobs_id     = $_GET['id'];

    $remove_save_jobs = new JOB();
    $remove_save_jobs->removeSaveJobs($save_jobs_id);
}

// activate jobs
if (isset($_GET['activate_jobs'])) {
    $save_jobs_id     = $_GET['id'];

    $activate_save_jobs = new JOB();
    $activate_save_jobs->activateSaveJobs($save_jobs_id);
}

// //edit
// if (isset($_POST['btn-edit-course'])) {
//     $course_id       = $_GET["id"];
//     $course_name     = trim($_POST['course_name']);

//     $edit_course = new Course();
//     $edit_course->editCourse($course_id, $course_name);
// }


// //activate
// if (isset($_GET['activate_course'])) {
//     $course_id = $_GET["id"];

//     $activate_course = new Course();
//     $activate_course->activateCourse($course_id);
// }


?>