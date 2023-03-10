<?php
require_once 'authentication/superadmin-class.php';
include_once '../../configuration/settings-configuration..php';

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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../../src/img/<?php echo $config->getSystemLogo() ?>">
	<link rel="stylesheet" href="../../src/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../src/node_modules/boxicons/css/boxicons.min.css">
	<link rel="stylesheet" href="../../src/node_modules/aos/dist/aos.css">
    <link rel="stylesheet" href="../../src/css/admin.css?v=<?php echo time(); ?>">
	<title>Dashboard</title>
</head>
<body>

<!-- Loader -->
<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar" class="hide">
		<a href="./" class="brand"><img src="../../src/img/<?php echo $config->getSystemLogo() ?>" alt="logo" class="brand-img"></i>&nbsp;&nbsp;NES</a>
		<ul class="side-menu">
			<li><a href="./" class=""><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>
			<li class="divider" data-text="main">Main</li>
            <li>
				<a href=""><i class='bx bxs-user icon' ></i> Users <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="teacher">Teacher</a></li>
					<li><a href="admin">Admin</a></li>
					<li><a href="pricipal">Principal</a></li>
					<li><a href="scheduler">Scheduler</a></li>
				</ul>
			</li>
			<li><a href="department"><i class='bx bxs-building-house icon'></i> Department</a></li>
			<li><a href="schedules"><i class='bx bxs-calendar-event icon'></i> Schedules</a></li>
			<li><a href="rooms"><i class='bx bxs-door-open icon'></i> Rooms</a></li>
			<li><a href="classes"><i class='bx bxs-chalkboard icon'></i> Classes</a></li>
			<li><a href="subjects"><i class='bx bxs-book icon'></i> Subjects</a></li>

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
			<h1 class="title">Profile</h1>
            <ul class="breadcrumbs">
				<li><a href="./" >Home</a></li>
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
						<button class="btn btn-primary change" onclick="edit()"><i class='bx bxs-edit'></i> Edit Profile</button>
						<button class="btn btn-primary change" onclick="avatar()"><i class='bx bxs-user'></i> Change Avatar</button>
						<button class="btn btn-primary change" onclick="password()"><i class='bx bxs-key'></i> Change Password</button>

					</div>
					
					<div id="Edit">
					<form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

							<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Edit Profile<p>Last update: <?php  echo $user_last_update  ?></p></label>

							<div class="col-md-12">
								<label for="name" class="form-label">First Name<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php  echo $user_fname  ?>">
								<div class="invalid-feedback">
								Please provide a Old Password.
								</div>
							</div>

							<div class="col-md-12">
								<label for="name" class="form-label">Middle Name</label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php  echo $user_mname  ?>">
								<div class="invalid-feedback">
								Please provide a Old Password.
								</div>
							</div>

							<div class="col-md-12">
								<label for="name" class="form-label">Last Name<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php  echo $user_lname  ?>">
								<div class="invalid-feedback">
								Please provide a Old Password.
								</div>
							</div>

							<div class="col-md-12">
								<label for="email" class="form-label">Email<span> *</span></label>
								<input type="email" class="form-control" autocapitalize="off" autocomplete="off" name="email" id="email" required value="<?php  echo $user_email  ?>">
								<div class="invalid-feedback">
								Please provide a valid Email.
								</div>
							</div>


						</div>

						<div class="addBtn">
							<button type="submit" class="primary" name="btn-update-profile" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
					</div>

					<div id="avatar" style="display: none;">
					<form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

							<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-user'></i> Change Avatar<p>Last update: <?php  echo $user_last_update  ?></p></label>

							<div class="col-md-12">
								<label for="avatar" class="form-label">Update Avatar<span> *</span></label>
								<input type="file" class="form-control" name="avatar" id="avatar" style="height: 33px ;" required>
								<div class="invalid-feedback">
								Please provide a Logo.
								</div>
							</div>

							<div class="col-md-12" style="opacity: 0;">
								<label for="email" class="form-label">Default Email<span> *</span></label>
								<input type="email" class="form-control" >
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
							<button type="submit" class="primary" name="btn-update-avatar" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
					</div>

					
					<div id="password" style="display: none;">
					<form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

							<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-key'></i> Change Password<p>Last update: <?php  echo $user_last_update  ?></p></label>

							<div class="col-md-12">
								<label for="old_pass" class="form-label">Old Password<span> *</span></label>
								<input type="password" class="form-control" autocapitalize="on" autocomplete="off"  name="old_password" id="old_pass" required>
								<div class="invalid-feedback">
								Please provide a Old Password.
								</div>
							</div>

							<div class="col-md-12">
								<label for="new_pass" class="form-label">New Password<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="new_password" id="new_pass" required>
								<div class="invalid-feedback">
								Please provide a New Password.
								</div>
							</div>

							<div class="col-md-12">
								<label for="confirm_pass" class="form-label">Confirm Password<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="confirm_password" id="confirm_pass" required>
								<div class="invalid-feedback">
								Please provide a Confirm Password.
								</div>
							</div>

						</div>

						<div class="addBtn">
							<button type="submit" class="btn-primary" name="btn-update-password" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
					</div>
                </div>
            </section>		
		</main>
		<!-- MAIN -->
	</section>
	<!-- END NAVBAR -->

	<script src="../../src/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="../../src/node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../src/js/dashboard.js"></script>
    <script src="../../src/js/loader.js"></script>
	<script src="../../src/js/form.js"></script>

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