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
            <li><a href=""><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
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

                        <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#classModal"><i class='bx bxs-edit'></i> Edit</a>
                        <a href="controller/job-controller?jobs_id=<?php echo $jobs_data['id'] ?>&delete_jobs=1"  class="btn btn-warning delete2" ><i class='bx bxs-trash' ></i> Disabled</a>
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
            <br>
            <h1 class="title">Applicants</h1>
            <section class="data-table">
                <div class="searchBx">
                    <input type="input" placeholder="Search applicants...." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
                </div>

                <div class="table">
                <div id="dynamic_content">
                </div>

            </section>
        </main>
        <!-- MAIN -->

        		<!-- MODALS -->
		<div class="class-modal">
			<div class="modal fade" id="classModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
					<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-briefcase icon'></i> Edit job post</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<section class="data-form-modals">
							<div class="registration">
								<form action="controller/job-controller?jobs_id=<?php echo $_GET['id'] ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" enctype="multipart/form-data"  novalidate style="overflow: hidden;">
									<div class="row gx-5 needs-validation">

                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">Job Title<span>*</span></label>
                                        <input type="text" class="form-control" value="<?php echo $jobs_data['job_title'] ?>" autocapitalize="on" autocomplete="off" name="job_title" id="job_title" required>
                                        <div class="invalid-feedback">
                                            Please provide a Job Title.
                                    </div>

                                    </div>

                                    <div class="col-md-6">
                                        <label for="sex" class="form-label">Workplace type<span> *</span></label>
                                        <select class="form-select form-control"  name="job_workplace_type"  autocapitalize="on" maxlength="6" autocomplete="off" id="job_workplace_type" required>
                                        <option selected value="<?php echo $jobs_data['job_workplace_type']?>"><?php echo $jobs_data['job_workplace_type']?></option>
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
                                        <input type="text" class="form-control" value="<?php echo $jobs_data['job_location']?>" autocapitalize="on" autocomplete="off" name="job_location" id="job_location" required>
                                        <div class="invalid-feedback">
                                            Please provide a Job location.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sex" class="form-label">Job type<span> *</span></label>
                                        <select class="form-select form-control"  name="job_type"  autocapitalize="on" maxlength="6" autocomplete="off" id="job_type" required>
                                        <option selected value="<?php echo $jobs_data['job_type']?>"><?php echo $jobs_data['job_type']?></option>
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
                                        <textarea class="form-control" rows="9" value="<?php echo $jobs_data['job_description']?>" name="job_description" id="job_description" required><?php echo $jobs_data['job_description']?></textarea>
                                        <div class="invalid-feedback">
                                            Please provide a Job Description.
                                        </div>
                                    </div>
                                </div>

									</div>

									<div class="addBtn">
										<button type="submit" class="btn-warning" name="btn-edit-job" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Update</button>
									</div>
								</form>
							</div>
						</section>
						</div>
					</div>
				</div>
			</div>
		</div>
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


            //live search---------------------------------------------------------------------------------------//
            $(document).ready(function(){

            load_data(1);

            function load_data(page, query = '')
            {
            $.ajax({
                url:"tables/applicants-tables?jobs_id=<?php echo $_GET['id'] ?>",
                method:"POST",
                data:{page:page, query:query},
                success:function(data)
                {
                $('#dynamic_content').html(data);
                }
            });
            }

            $(document).on('click', '.page-link', function(){
            var page = $(this).data('page_number');
            var query = $('#search_box').val();
            load_data(page, query);
            });

            $('#search_box').keyup(function(){
            var query = $('#search_box').val();
            load_data(1, query);
            });

            });

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