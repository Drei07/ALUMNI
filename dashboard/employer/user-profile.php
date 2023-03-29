<?php
require_once 'authentication/employer-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new EMPLOYER();

// check if user is logged in and redirect if not
if (!$user->isUserLoggedIn()) {
    $user->redirect('../../private/employer');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid" => $_SESSION['employerSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname                = $user_data['first_name'];
$user_mname                = $user_data['middle_name'];
$user_lname                = $user_data['last_name'];
$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];

$applicant_id = $_GET['id'];
$applicant_stmt = $user->runQuery("SELECT * FROM users WHERE id=:id");
$applicant_stmt->execute(array(":id" => $applicant_id));
$applicant_data = $applicant_stmt->fetch(PDO::FETCH_ASSOC);

$applicant_profile           = $applicant_data['profile'];
$applicant_fname                = $applicant_data['first_name'];
$applicant_mname                = $applicant_data['middle_name'];
$applicant_lname                = $applicant_data['last_name'];
$applicant_sex                = $applicant_data['sex'];
$applicant_birth_date        = $applicant_data['date_of_birth'];
$applicant_age                = $applicant_data['age'];
$applicant_civil_status        = $applicant_data['civil_status'];
$applicant_phone_number        = $applicant_data['phone_number'];
$applicant_batch                = $applicant_data['batch'];
$applicant_email                = $applicant_data['email'];

// course
$applicant_course_id = $applicant_data['course_id'] ?? "";
$course_name = "";

if ($applicant_course_id != "") {
    $stmt = $user->runQuery("SELECT * FROM course WHERE id=:id");
    $stmt->execute(array(":id"=>$applicant_course_id));
    $course_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($course_data !== false) {
      $course_name = $course_data['course'];
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | User Profile</title>
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
            <h1 class="title">Profile</h1>
            <ul class="breadcrumbs">
                <li><a href="./">Home</a></li>
                <li class="divider">|</li>
                <li><a href="" class="active">Profile</a></li>

            </ul>

            <!-- PROFILE CONFIGURATION -->

            <section class="profile-form">
                <div class="header"></div>
                <div class="profile">
                    <div class="profile-img">
                        <img src="../../src/img/<?php echo $user_profile ?>" alt="logo">
                        <a href="controller/applicants-controller?applicants_id=<?php echo $_GET['applicants_id'] ?>&accept_applicants=1"  class="btn btn-warning accept" ><i class='bx bxs-check-circle' ></i> Accept</a>
                        <a href="controller/applicants-controller?applicants_id=<?php echo $_GET['applicants_id'] ?>&reject_applicants=1"  class="btn btn-warning reject" ><i class='bx bxs-x-circle' ></i> Reject</a>
                        <a href="./"  class="btn btn-warning" ><i class='bx bxs-right-arrow' ></i> Back</a>

                    </div>

                    <div id="Edit">
                        <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-user'></i> Applicant Profile</label>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">First Name</label>
                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php echo $applicant_fname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a First Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Middle Name</label>
                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php echo $applicant_mname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a Middle Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Last Name</label>
                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php echo $applicant_lname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a Last Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="sex" class="form-label">Sex</label>
                                    <select class="form-select form-control" disabled name="sex" maxlength="6" autocomplete="off" id="sex">
                                        <option selected  disabled><?php echo $applicant_sex ?></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Sex.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label">Birth Date</label>
                                    <input type="date" disabled class="form-control" value="<?php echo $applicant_birth_date ?>" autocapitalize="off" autocomplete="off" name="date_of_birth" id="date_of_birth" maxlength="10" pattern="^[a-zA-Z0-9]+@gmail\.com$" placeholder="Ex: mm/dd/yyyy" onkeyup="getAgeVal(0)" onblur="getAgeVal(0);">
                                    <div class="invalid-feedback">
                                        Please provide a Birth Date.
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none;">
                                    <label for="age" class="form-label">Age<span style="font-size:9px; color:red;">( auto-generated )</span></label>
                                    <input type="number" disabled class="form-control" value="<?php echo $applicant_age ?>" autocapitalize="off" autocomplete="off" name="age" id="age">
                                    <div class="invalid-feedback">
                                        Please provide your Age.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="CivilStatus" class="form-label">Civil Status</label>
                                    <select class="form-select form-control" disabled name="civil_status" maxlength="6" autocomplete="off" id="civil_status">
                                        <option selected value="<?php echo $applicant_civil_status ?>"><?php echo $applicant_civil_status ?></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Civil Status.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="course" class="form-label">Course Graduated<span>
                                            *</span></label>
                                    <select class="form-select" id="course" disabled autocomplete="off" name="course" required>
                                        <option selected value="<?php echo $applicant_course_id ?>"><?php echo $course_name ?></option>

                                    </select>
                                    <div class="invalid-feedback">
                                        Please select an organization.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="batch" class="form-label">Year Graduated<span>
                                            *</span></label>
                                    <select class="form-select form-control" disabled name="batch" maxlength="6" autocomplete="off" id="batch" required>
                                        <option selected value="<?php echo $applicant_batch ?>"><?php echo $applicant_batch ?></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Year.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Email</label>
                                    <input type="email" disabled class="form-control" value="<?php echo $applicant_email  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a Email
                                    </div>
                                </div>

                            </div>
                        </form>
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