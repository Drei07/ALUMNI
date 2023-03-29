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

// religion
$user_religion_id = $user_data['religion_id'] ?? "";
$religion_name = "";

if ($user_religion_id != "") {
  $stmt = $user->runQuery("SELECT * FROM religion WHERE id=:id");
  $stmt->execute(array(":id"=>$user_religion_id));
  $religion_data = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($religion_data !== false) {
    $religion_name = $religion_data['religion'];
  }
}

// address
$user_address_id = $user_data['address_id'] ?? "";

// course
$user_course_id = $user_data['course_id'] ?? "";
$course_name = "";

if ($user_course_id != "") {
    $stmt = $user->runQuery("SELECT * FROM course WHERE id=:id");
    $stmt->execute(array(":id"=>$user_course_id));
    $course_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($course_data !== false) {
      $course_name = $course_data['course'];
    }
  }

$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | Profile</title>
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

                        <a href="controller/profile-controller.php?id=<?php echo $user_id ?>&delete_avatar=1" class="delete"><i class='bx bxs-trash'></i></a>
                        <button class="btn btn-warning change" onclick="edit()"><i class='bx bxs-edit'></i> Edit
                            Profile</button>
                        <button class="btn btn-warning change" onclick="avatar()"><i class='bx bxs-user'></i> Change
                            Avatar</button>
                        <button class="btn btn-warning change" onclick="password()"><i class='bx bxs-key'></i> Change
                            Password</button>

                    </div>

                    <div id="Edit">
                        <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Edit Profile<p>Last update:
                                        <?php echo $user_last_update  ?></p></label>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">First Name<span> *</span></label>
                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php echo $user_fname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a First Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php echo $user_mname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a Middle Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Last Name<span> *</span></label>
                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php echo $user_lname  ?>">
                                    <div class="invalid-feedback">
                                        Please provide a Last Name
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="sex" class="form-label">Sex</label>
                                    <select class="form-select form-control" name="sex" maxlength="6" autocomplete="off" id="sex">
                                        <option selected value="<?php echo $user_sex ?>"><?php echo $user_sex ?></option>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE ">FEMALE</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Sex.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label">Birth Date</label>
                                    <input type="date" class="form-control" value="<?php echo $user_birth_date ?>" autocapitalize="off" autocomplete="off" name="date_of_birth" id="date_of_birth" maxlength="10" pattern="^[a-zA-Z0-9]+@gmail\.com$" placeholder="Ex: mm/dd/yyyy" onkeyup="getAgeVal(0)" onblur="getAgeVal(0);">
                                    <div class="invalid-feedback">
                                        Please provide a Birth Date.
                                    </div>
                                </div>

                                <div class="col-md-6" style="display: none;">
                                    <label for="age" class="form-label">Age<span style="font-size:9px; color:red;">( auto-generated )</span></label>
                                    <input type="number" class="form-control" value="<?php echo $user_age ?>" autocapitalize="off" autocomplete="off" name="age" id="age">
                                    <div class="invalid-feedback">
                                        Please provide your Age.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="CivilStatus" class="form-label">Civil Status</label>
                                    <select class="form-select form-control" name="civil_status" maxlength="6" autocomplete="off" id="civil_status">
                                        <option selected value="<?php echo $user_civil_status ?>"><?php echo $user_civil_status ?></option>
                                        <option value="SINGLE">SINGLE</option>
                                        <option value="MARRIED">MARRIED</option>
                                        <option value="SEPERATED">SEPERATED</option>
                                        <option value=">WIDOW/WIDOWER">WIDOW/WIDOWER</option>
                                        <option value="ANULLED">ANULLED</option>
                                        <option value="SOLO PARENT">SOLO PARENT</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Civil Status.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="course" class="form-label">Course Graduated<span>
                                            *</span></label>
                                    <select class="form-select" id="course" autocomplete="off" name="course" required>
                                        <option selected value="<?php echo $user_course_id ?>"><?php echo $course_name ?></option>
                                        <?php
                                        $stmt = $user->runQuery("SELECT * FROM course");
                                        $stmt->execute();

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['course'] . "</option>";
                                        }
                                        ?>

                                    </select>
                                    <div class="invalid-feedback">
                                        Please select an organization.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="batch" class="form-label">Year Graduated<span>
                                            *</span></label>
                                    <select class="form-select form-control" name="batch" maxlength="6" autocomplete="off" id="batch" required>
                                        <option selected value="<?php echo $user_batch ?>"><?php echo $user_batch ?></option>
                                        <option value="2010-2011">2010-2011</option>
                                        <option value="2011-2012">2011-2012</option>
                                        <option value="2012-2013">2012-2013</option>
                                        <option value="2013-2014">2013-2014</option>
                                        <option value="2014-2015">2014-2015</option>
                                        <option value="2015-2016">2015-2016</option>
                                        <option value="2016-2017">2016-2017</option>
                                        <option value="2017-2018">2017-2018</option>
                                        <option value="2018-2019">2018-2019</option>
                                        <option value="2019-2020">2019-2020</option>
                                        <option value="2020-2021">2020-2021</option>
                                        <option value="2021-2022">2021-2022</option>
                                        <option value="2022-2023">2022-2023</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Year.
                                    </div>
                                </div>

                            </div>

                            <div class="addBtn">
                                <button type="submit" class="warning" name="btn-update-profile" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
                            </div>
                        </form>
                    </div>

                    <div id="avatar" style="display: none;">
                        <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-user'></i> Change Avatar<p>Last update:
                                        <?php echo $user_last_update  ?></p></label>

                                <div class="col-md-12">
                                    <label for="avatar" class="form-label">Update Avatar<span> *</span></label>
                                    <input type="file" class="form-control" name="avatar" id="avatar" style="height: 33px ;" required>
                                    <div class="invalid-feedback">
                                        Please provide a Logo.
                                    </div>
                                </div>

                                <div class="col-md-12" style="opacity: 0;">
                                    <label for="email" class="form-label">Default Email<span> *</span></label>
                                    <input type="email" class="form-control">
                                    <div class="invalid-feedback">
                                        Please provide a valid Email.
                                    </div>
                                </div>

                                <div class="col-md-12" style="opacity: 0; padding-bottom: 1.3rem;">
                                    <label for="sname" class="form-label">Old Password<span> *</span></label>
                                    <input type="text" class="form-control">
                                    <div class="invalid-feedback">
                                        Please provide a Old Password.
                                    </div>
                                </div>

                            </div>

                            <div class="addBtn">
                                <button type="submit" class="btn-warning" name="btn-update-avatar" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
                            </div>
                        </form>
                    </div>


                    <div id="password" style="display: none;">
                        <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-key'></i> Change Password<p>Last update:
                                        <?php echo $user_last_update  ?></p></label>

                                <div class="col-md-12">
                                    <label for="old_pass" class="form-label">Old Password<span> *</span></label>
                                    <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="old_password" id="old_pass" required>
                                    <div class="invalid-feedback">
                                        Please provide a Old Password.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="new_pass" class="form-label">New Password<span> *</span></label>
                                    <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="new_password" id="new_pass" required>
                                    <div class="invalid-feedback">
                                        Please provide a New Password.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="confirm_pass" class="form-label">Confirm Password<span> *</span></label>
                                    <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="confirm_password" id="confirm_pass" required>
                                    <div class="invalid-feedback">
                                        Please provide a Confirm Password.
                                    </div>
                                </div>

                            </div>

                            <div class="addBtn">
                                <button type="submit" class="btn-warning" name="btn-update-password" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
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