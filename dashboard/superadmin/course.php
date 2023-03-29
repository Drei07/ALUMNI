<?php
require_once 'authentication/superadmin-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new SUPERADMIN();

// check if user is logged in and redirect if not
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../private/superadmin/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['superadminSession']));
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
	<title>Track Me | Course</title>
</head>
<body>

<!-- Loader -->
<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar" class="hide">
		<a href="" class="brand"><img src="../../src/img/main2_logo.png" alt="logo" class="brand-img"></a>
		<ul class="side-menu">
			<li><a href="./"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>
			<li class="divider" data-text="main">Main</li>
            <li>
				<a href=""><i class='bx bxs-user icon' ></i> Users <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="employer">Employer</a></li>
					<li><a href="alumni">Alumni</a></li>
				</ul>
			</li>
			<li><a href="course" class="active"><i class='bx bx-list-ul icon'></i> Course</a></li>
			<li><a href="audit-trail"><i class='bx bxl-blogger icon'></i> Audit Trail</a></li>

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
					<li><a href="settings"><i class='bx bxs-cog' ></i> Settings</a></li>
					<li><a href="authentication/superadmin-signout" class="btn-signout"><i class='bx bxs-log-out-circle' ></i> Signout</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Course</h1>
			<ul class="breadcrumbs">
				<li><a href="./" >Home</a></li>
				<li class="divider">|</li>
                <li><a href="" class="active">Course List</a></li>
			</ul>
			<div class="level">
                <button type="button" data-bs-toggle="modal" data-bs-target="#classModal"><i class='bx bx-plus-medical'></i> Add Course</button>
            </div>
            <section class="data-table">
                <div class="searchBx">
                    <input type="input" placeholder="Search course...." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
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
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user icon'></i> Add Course</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<section class="data-form-modals">
							<div class="registration">
								<form action="controller/course-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
									<div class="row gx-5 needs-validation">

										<div class="col-md-12">
											<label for="acdemic" class="form-label">Course Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="course_name" id="course_name" placeholder="Ex. BS Information Technology" required>
											<div class="invalid-feedback">
											Please provide a Course Name.
											</div>
										</div>

									</div>

									<div class="addBtn">
										<button type="submit" class="btn-warning" name="btn-add-course" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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