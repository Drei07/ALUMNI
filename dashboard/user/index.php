<?php
require_once 'authentication/user-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new ALUMNI();

// check if user is logged in and redirect if not
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['alumniSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname		        = $user_data['first_name'];
$user_mname		        = $user_data['middle_name'];
$user_lname		        = $user_data['last_name'];
$user_sex		        = $user_data['sex'];
$user_birth_date		= $user_data['date_of_birth'];
$user_age	            = $user_data['age'];
$user_civil_status		= $user_data['civil_status'];
$user_phone_number		= $user_data['phone_number'];
$user_batch		        = $user_data['batch'];
$user_email		        = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

// religion
$user_religion_id = $user_data['religion_id'] ?? "";

// address
$user_address_id = $user_data['address_id'] ?? "";

// course
$user_courses_id = $user_data['courses_id'] ?? "";



$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | Dashboard</title>
</head>

<body>
    <!-- Loader -->
    <div class="loader"></div>

    <!-- SIDEBAR -->
    <section id="sidebar" class="hide">
        <a href="" class="brand"><img src="../../src/img/main2_logo.png" alt="logo" class="brand-img"></a>
        <ul class="side-menu">
            <li><a href=""><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
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
        <h1 class="title">Posted Jobs</h1>
            <ul class="breadcrumbs">
                <li><a href="./">Home</a></li>
                <li class="divider">|</li>
                <li><a href="" class="active">Posted Jobs</a></li>

            </ul>
        <div class="jobs-content">
                <section id="jobs">
                    <div class="container">
                        <?php
                            $stmt = $user->runQuery("SELECT * FROM jobs WHERE status = :status ORDER BY id DESC");
                            $stmt->execute(array(":status" => "active"));
                            while ($jobs_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                        <div class="job">
                            <img src="../../src/img/<?php echo $company_logo ?>" alt="Company Logo">
                            <div class="job-info">
                                <a href="view-jobs?id=<?php echo $jobs_data['id']; ?>" class="job-title"><?php echo $jobs_data['job_title']; ?></a>
                                <p class="job-company"><?php echo $company_name ?></p>
                                <p class="job-location"><?php echo $jobs_data['job_location']; ?> (<?php echo $jobs_data['job_workplace_type']; ?>)</p>
                                <p class="job-description"><?php echo $jobs_data['job_type']; ?></p>
                                <p class="job-description">Posted: <?php echo date('l j, Y', strtotime($jobs_data['created_at'])); ?></p>
                                <p class="job-applicants"><?php echo $applicant_count ?> applicants</p>
                                <a href="controller/jobs-controller?id=<?php echo $user_id ?>&jobs_id=<?php echo $jobs_data['id'] ?>&save_jobs=1" class="button" >Save</a>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                </section>
            </div>
        </main>
        <!-- MAIN -->

        <?php
        if($user_batch == NULL && $user_courses_id == NULL){
        ?>
        <div class="class-modal">
            <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true"
                data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="header"></div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user icon'></i> User
                                Information
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                onclick="history.back()"></button>
                        </div>
                        <div class="modal-body">
                            <section class="data-form-modals">
                                <div class="registration">
                                    <form action="controller/user-controller.php?id=<?php echo $user_id ?>"
                                        method="POST" class="row gx-5 needs-validation" name="form"
                                        onsubmit="return validate()" novalidate style="overflow: hidden;">
                                        <div class="row gx-5 needs-validation">

                                            <div class="col-md-12">
                                                <label for="first_name" class="form-label">First Name<span>
                                                        *</span></label>
                                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                    class="form-control" autocapitalize="on" autocomplete="off"
                                                    name="first_name" id="first_name" required>
                                                <div class="invalid-feedback">
                                                    Please provide a First Name.
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                    class="form-control" autocapitalize="on" autocomplete="off"
                                                    name="middle_name" id="middle_name">
                                            </div>


                                            <div class="col-md-12">
                                                <label for="last_name" class="form-label">Last Name<span>
                                                        *</span></label>
                                                <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                    class="form-control" autocapitalize="on" autocomplete="off"
                                                    name="last_name" id="last_name" required>
                                                <div class="invalid-feedback">
                                                    Please provide a Last Name.
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="batch" class="form-label">Year Graduated<span>
                                                        *</span></label>
                                                <select class="form-select form-control" name="batch" maxlength="6"
                                                    autocomplete="off" id="batch" required>
                                                    <option selected disabled value="">Select...</option>
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

                                            <div class="col-md-12">
                                                <label for="course" class="form-label">Course Graduated<span>
                                                        *</span></label>
                                                <select class="form-select" id="course" autocomplete="off" name="course"
                                                    required>
                                                    <option selected disabled value="">Select...</option>
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
                                        </div>

                                        <div class="addBtn">
                                            <button type="submit" class="btn-warning" name="btn-add-alumni-other"
                                                id="btn-add" onclick="return IsEmpty(); sexEmpty();">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../../src/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../../src/node_modules/jquery/dist/jquery.min.js"></script>
        <script>
        //Load Modal
        $(window).on('load', function() {
            $('#editModal').modal('show');
        });
        </script>
        <?php
        }
        ?>
        <!-- END NAVBAR -->

        <?php
    include_once '../../configuration/footer.php';
    ?>

        <script>
        // Signout
        $('.btn-signout').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href')

            swal({
                    title: "Signout?",
                    text: "Are you sure do you want to signout?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willSignout) => {
                    if (willSignout) {
                        document.location.href = href;
                    }
                });
        })
        </script>

        <!-- SWEET ALERT -->
        <?php

	if(isset($_SESSION['status']) && $_SESSION['status'] !='')
	{
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