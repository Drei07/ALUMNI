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

// address
$user_address_id = $user_data['address_id'] ?? "";

// course
$user_courses_id = $user_data['courses_id'] ?? "";



$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];

//company details
$stmt = $user->runQuery("SELECT * FROM company WHERE user_id=:user_id");
$stmt->execute(array(":user_id" => $user_id));
$company_data = $stmt->fetch(PDO::FETCH_ASSOC);

$company_id = $company_data['id'] ?? "";
$company_logo = $company_data['company_logo'] ?? "";
$company_name =  $company_data['company_name'] ?? "";


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
            <li><a href="" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="main">My Jobs </li>
            <li><a href="company"><i class='bx bxs-buildings icon'></i> Company</a></li>
            <li><a href="archived"><i class='bx bxl-blogger icon'></i> Archived Jobs</a></li>

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
                    <li><a href="authentication/employer-signout" class="btn-signout"><i class='bx bxs-log-out-circle'></i>
                            Signout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="jobs-content">
                <section class="data-form">
                    <div class="header"></div>
                    <div class="registration">
                        <form action="controller/job-controller.php?id=<?php echo $user_id ?>&company_id=<?php echo $company_id ?>" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .1rem; padding-bottom: 2rem; font-size: 2rem; font-weight: bold;"><i class='bx bxs-edit'></i> Post a job</label>

                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Job Title<span>*</span></label>
                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="job_title" id="job_title" required>
                                    <div class="invalid-feedback">
                                        Please provide a Job Title.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="sex" class="form-label">Workplace type<span> *</span></label>
                                    <select class="form-select form-control"  name="job_workplace_type"  autocapitalize="on" maxlength="6" autocomplete="off" id="job_workplace_type" required>
                                    <option selected value="">Select...</option>
                                    <option value="On-site">On-site</option>
                                    <option value="Hybrid ">Hybrid</option>
                                    <option value="Remote ">Remote</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Workplace type.
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Job location<span>*</span></label>
                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="job_location" id="job_location" required>
                                    <div class="invalid-feedback">
                                        Please provide a Job location.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="sex" class="form-label">Job type<span> *</span></label>
                                    <select class="form-select form-control"  name="job_type"  autocapitalize="on" maxlength="6" autocomplete="off" id="job_type" required>
                                    <option selected value="">Select...</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Contract ">Contract</option>
                                    <option value="Temporary ">Temporary</option>
                                    <option value="Volunteer ">Volunteer</option>
                                    <option value="Internship ">Internship</option>
                                    <option value="Other ">Other</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Job type.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="last_name" class="form-label">Job Description<span>*</span></label>
                                    <textarea class="form-control" rows="9" name="job_description" id="job_description" required></textarea>
                                    <div class="invalid-feedback">
                                        Please provide a Job Description.
                                    </div>
                                </div>
                            </div>

                            <div class="addBtn" style="padding-top: 2rem;">
                                <button type="submit" class="btn-warning" name="btn-post-job" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Post</button>
                            </div>
                        </form>
                    </div>
                </section>
                <section id="jobs">
                    <div class="container">
                        <h2>Posted Jobs</h2>
                        <?php
                            $stmt = $user->runQuery("SELECT * FROM jobs WHERE user_id = :user_id AND status = :status ORDER BY id DESC");
                            $stmt->execute(array(":user_id" => $user_id, ":status" => "active"));
                            while ($jobs_data = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            //applicants
                            $applicant_stmt = $user->runQuery("SELECT * FROM application WHERE jobs_id=:jobs_id");
                            $applicant_stmt->execute(array(":jobs_id" => $jobs_data['id']));
                            $applicant_count = $applicant_stmt->rowCount();
                        ?>
                        <div class="job" onclick="location.href='view-jobs?id=<?php echo $jobs_data['id'] ?>'">
                            <img src="../../src/img/<?php echo $company_logo ?>" alt="Company Logo">
                            <div class="job-info">
                                <a href="view-jobs?id=<?php echo $jobs_data['id']; ?>" class="job-title"><?php echo $jobs_data['job_title']; ?></a>
                                <p class="job-company"><?php echo $company_name ?></p>
                                <p class="job-location"><?php echo $jobs_data['job_location']; ?> (<?php echo $jobs_data['job_workplace_type']; ?>)</p>
                                <p class="job-description"><?php echo $jobs_data['job_type']; ?></p>
                                <p class="job-description">Posted: <?php echo date('l j, Y', strtotime($jobs_data['created_at'])); ?></p>
                                <p class="job-applicants"><?php echo $applicant_count ?> applicants</p>
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

        $stmt = $user->runQuery("SELECT * FROM company WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $company_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($company_data == NULL) {
        ?>
            <div class="class-modal">
                <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="header"></div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-buildings icon'></i>
                                    Register Company
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="history.back()"></button>
                            </div>
                            <div class="modal-body">
                                <section class="data-form-modals">
                                    <div class="registration">
                                        <form action="controller/company-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" enctype="multipart/form-data" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <div class="row gx-5 needs-validation">

                                                <div class="col-md-12">
                                                    <label for="first_name" class="form-label">Company Name<span>*</span></label>
                                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="company_name" id="company_name" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Company Name.
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="last_name" class="form-label">Company Address<span>*</span></label>
                                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="company_address" id="company_address" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Company Address.
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="phone_number" class="form-label">Company Phone Number</label>
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">+63</span>
                                                        <input type="text" class="form-control numbers" autocapitalize="off" inputmode="numeric" autocomplete="off" name="company_phone_number" id="company_phone_number" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="10-digit number">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="last_name" class="form-label">Company Description<span>*</span></label>
                                                    <textarea class="form-control" rows="5" name="company_description" id="company_description" required></textarea>
                                                    <div class="invalid-feedback">
                                                        Please provide a Company Description.
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="logo" class="form-label">Company Logo<span> *</span></label>
                                                    <input type="file" class="form-control" name="company_logo" id="company_logo" style="height: 33px ;" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Company Logo.
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="email" class="form-label">Company Email<span> *</span></label>
                                                    <input type="email" class="form-control" autocapitalize="on" autocomplete="off" name="company_email" id="company_email" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Company Email.
                                                    </div>
                                                </div>


                                                <div class="addBtn">
                                                    <button type="submit" class="btn-warning" name="btn-add-company" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Submit</button>
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