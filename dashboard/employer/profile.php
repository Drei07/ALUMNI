<?php
require_once 'authentication/employer-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new EMPLOYER();

// check if user is logged in and redirect if not
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../private/employer/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['employerSession']));
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
	<title>Track Me | Profile</title>
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
					<li><a href="authentication/employer-signout" class="btn-signout"><i class='bx bxs-log-out-circle' ></i> Signout</a></li>
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
						<button class="btn btn-warning change" onclick="edit()"><i class='bx bxs-edit'></i> Edit Profile</button>
						<button class="btn btn-warning change" onclick="avatar()"><i class='bx bxs-user'></i> Change Avatar</button>
						<button class="btn btn-warning change" onclick="password()"><i class='bx bxs-key'></i> Change Password</button>

					</div>
					
					<div id="Edit">
					<form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

							<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Edit Profile<p>Last update: <?php  echo $user_last_update  ?></p></label>

							<div class="col-md-12">
								<label for="name" class="form-label">First Name<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php  echo $user_fname  ?>">
								<div class="invalid-feedback">
								Please provide a First Name.
								</div>
							</div>

							<div class="col-md-12">
								<label for="name" class="form-label">Middle Name</label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php  echo $user_mname  ?>">
								<div class="invalid-feedback">
								Please provide a Middle Name.
								</div>
							</div>

							<div class="col-md-12">
								<label for="name" class="form-label">Last Name<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php  echo $user_lname  ?>">
								<div class="invalid-feedback">
								Please provide a Last Name.
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
							<button type="submit" class="warning" name="btn-update-profile" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
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
							<button type="submit" class="btn-warning" name="btn-update-avatar" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
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
	
			function edit(){
				document.getElementById('Edit').style.display = 'block';
				document.getElementById('password').style.display = 'none';
				document.getElementById('avatar').style.display = 'none';
			}
	
			function avatar(){
				document.getElementById('avatar').style.display = 'block';
				document.getElementById('Edit').style.display = 'none';
				document.getElementById('password').style.display = 'none';
			}
	
			function password(){
				document.getElementById('password').style.display = 'block';
				document.getElementById('avatar').style.display = 'none';
				document.getElementById('Edit').style.display = 'none';
			}
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