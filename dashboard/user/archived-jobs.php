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
$user_id        = $user_data['id'];
$user_profile   = $user_data['profile'];
$user_fname		= $user_data['first_name'];
$user_mname		= $user_data['middle_name'];
$user_lname		= $user_data['last_name'];
$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_email		= $user_data['email'];
$user_last_update = $user_data['updated_at'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
	<title>Track Me | Save Jobs</title>
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
			<i class='bx bx-menu toggle-sidebar' ></i>

			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
				<span class="badge">5</span>
			</a>
			<a href="#" class="nav-link">
				<i class='bx bxs-message-square-dots icon' ></i>
				<span class="badge">8</span>
			</a>
			<span class="divider"></span>
			<div class="dropdown">
				<span><?php echo $user_fullname ?></i></span>
			</div>	
			<div class="profile">
				<img src="../../src/img/<?php echo $user_profile ?>" alt="">
				<ul class="profile-link">
					<li><a href="profile"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="authentication/user-signout" class="btn-signout"><i class='bx bxs-log-out-circle' ></i> Signout</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Archived Jobs</h1>
			<ul class="breadcrumbs">
				<li><a href="./" >Home</a></li>
				<li class="divider">|</li>
                <li><a href="" class="active">Archived Jobs</a></li>
			</ul>
            <div class="jobs-content">
                <section id="jobs">
                    <div class="container">
                        <?php

                            //save jobs
                            $save_jobs_stmt = $user->runQuery("SELECT * FROM save_jobs WHERE user_id=:user_id AND status = :status");
                            $save_jobs_stmt->execute(array(":user_id" => $user_id, ":status" => "disabled"));
                            while ($save_jobs_data = $save_jobs_stmt->fetch(PDO::FETCH_ASSOC)){
                                $jobs_id = $save_jobs_data['jobs_id'];
                                $save_jobs_id = $save_jobs_data['id'];
                            
                            
                            $stmt = $user->runQuery("SELECT * FROM jobs WHERE id = :id AND status = :status ORDER BY id DESC");
                            $stmt->execute(array(":id" => $jobs_id, ":status" => "active"));
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
                                <a href="controller/jobs-controller?id=<?php echo $save_jobs_id ?>&activate_jobs=1" class="button" >Remove</a>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>
                </section>
            </div>
		</main>
		<!-- MAIN -->

		<!-- MODALS -->
	</section>
	<!-- END NAVBAR -->
    <?php
    include_once '../../configuration/footer.php';
    ?>
	<script>

        //live search---------------------------------------------------------------------------------------//
        $(document).ready(function(){

        load_data(1);

        function load_data(page, query = '')
        {
        $.ajax({
            url:"tables/course-tables.php",
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