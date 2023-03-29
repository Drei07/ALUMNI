<?php
require_once 'authentication/user-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new ALUMNI();

// check if user is logged in and redirect if not
if (!$user->isUserLoggedIn()) {
    $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid" => $_SESSION['alumniSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname                = $user_data['first_name'];
$user_mname                = $user_data['middle_name'];
$user_lname                = $user_data['last_name'];
$user_sex                = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age                = $user_data['age'];
$user_civil_status        = $user_data['civil_status'];
$user_phone_number        = $user_data['phone_number'];
$user_batch                = $user_data['batch'];
$user_email                = $user_data['email'];
$user_last_update       = $user_data['updated_at'];
$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];

// retrieve user data
$job_stmt = $user->runQuery("SELECT * FROM jobs WHERE id=:id");
$job_stmt->execute(array(":id" => $_GET['id']));
$jobs_data = $job_stmt->fetch(PDO::FETCH_ASSOC);

// Company
$company_stmt = $user->runQuery("SELECT * FROM company WHERE id=:id");
$company_stmt->execute(array(":id" => $jobs_data['company_id']));
$company_data = $company_stmt->fetch(PDO::FETCH_ASSOC);

$company_logo = $company_data['company_logo'];
$company_name =  $company_data['company_name'];

//applicants
$applicant_stmt = $user->runQuery("SELECT * FROM application WHERE jobs_id=:jobs_id");
$applicant_stmt->execute(array(":jobs_id" => $jobs_data['id']));
$applicant_count = $applicant_stmt->rowCount();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | View Jobs</title>
</head>

<body>

    <!-- Loader -->
    <div class="loader"></div>

    <!-- SIDEBAR -->
    <section id="sidebar" class="hide">
        <a href="" class="brand"><img src="../../src/img/main2_logo.png" alt="logo" class="brand-img"></a>
        <ul class="side-menu">
            <li><a href="./"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="main">My Jobs </li>
            <li><a href="save-jobs"><i class='bx bxs-bookmarks icon'></i> Save Jobs</a></li>
            <li><a href="applied-jobs"><i class='bx bxs-briefcase icon'></i> Applied Jobs</a></li>
            <li><a href="archived-jobs"><i class='bx bxl-blogger icon'></i> Archived Jobs</a></li>

        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>

            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
                <span class="badge">8</span>
            </a>
            <span class="divider"></span>
            <div class="dropdown">
                <span><?php echo $user_fullname ?></i></span>
            </div>
            <div class="profile">
                <img src="../../src/img/<?php echo $user_profile ?>" alt="">
                <ul class="profile-link">
                    <li><a href="profile"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="authentication/user-signout" class="btn-signout"><i class='bx bxs-log-out-circle'></i>
                            Signout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <h1 class="title">Job Details</h1>
            <ul class="breadcrumbs">
                <li><a href="./">Home</a></li>
                <li class="divider">|</li>
                <li><a href="" class="active">Details</a></li>

            </ul>

            <!-- PROFILE CONFIGURATION -->

            <section class="profile-form">
                <div class="header"></div>
                <div class="profile">
                    <div class="profile-img">
                        <img src="../../src/img/<?php echo $company_logo ?>" alt="logo">

                        <a href="controller/jobs-controller?id=<?php echo $user_id ?>&jobs_id=<?php echo $jobs_data['id'] ?>&applied_jobs=1" class="btn btn-warning applied" ><i class='bx bxs-paper-plane'></i> Apply</a>
                        <a href="controller/jobs-controller?id=<?php echo $user_id ?>&jobs_id=<?php echo $jobs_data['id'] ?>&save_jobs=1"  class="btn btn-warning save" ><i class='bx bxs-bookmark-alt-plus' ></i> Save</a>
                        <a href="./"  class="btn btn-warning" ><i class='bx bxs-right-arrow' ></i> Back</a>

                    </div>

                    <div class="job-info">
                        <a class="job-title"><?php echo $jobs_data['job_title']; ?></a><br><br>
                        <p class="job-company"><?php echo $company_name ?></p>
                        <p class="job-location"><?php echo $jobs_data['job_location']; ?> (<?php echo $jobs_data['job_workplace_type']; ?>)</p>
                        <p class="job-type"><?php echo $jobs_data['job_type']; ?></p>
                        <p class="job-type">Posted: <?php echo date('l j, Y', strtotime($jobs_data['created_at'])); ?></p>
                        <p class="job-applicants"><?php echo $applicant_count ?> applicants</p>
                        <a class="job-description">Job Description</a><br><br>
                        <p class="job-description-2"><?php echo $jobs_data['job_description']; ?></p>


                    </div>
                    
                </div>
            </section>
        </main>
        <!-- MAIN -->
    </section>
    <!-- END NAVBAR -->
    <?php
    include_once '../../configuration/footer.php';
    ?>

    <script>
        window.onpageshow = function() {
            document.getElementById('avatar').style.display = 'none';
            document.getElementById('password').style.display = 'none';
        };

        function edit() {
            document.getElementById('Edit').style.display = 'block';
            document.getElementById('password').style.display = 'none';
            document.getElementById('avatar').style.display = 'none';
        }

        function avatar() {
            document.getElementById('avatar').style.display = 'block';
            document.getElementById('Edit').style.display = 'none';
            document.getElementById('password').style.display = 'none';
        }

        function password() {
            document.getElementById('password').style.display = 'block';
            document.getElementById('avatar').style.display = 'none';
            document.getElementById('Edit').style.display = 'none';
        }
    </script>

    <!-- SWEET ALERT -->
    <?php

    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
            swal({
                title: "<?php echo $_SESSION['status_title']; ?>",
                text: "<?php echo $_SESSION['status']; ?>",
                icon: "<?php echo $_SESSION['status_code']; ?>",
                button: false,
                timer: <?php echo $_SESSION['status_timer']; ?>,
            });
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</body>

</html>