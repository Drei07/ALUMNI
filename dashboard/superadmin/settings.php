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
	<title>Track Me | Settings</title>
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
			<li><a href="course"><i class='bx bx-list-ul icon'></i> Course</a></li>
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
			<h1 class="title">Settings</h1>
            <ul class="breadcrumbs">
				<li><a href="./" >Home</a></li>
				<li class="divider">|</li>
                <li><a href="" class="active">Settings</a></li>
			</ul>
            <!-- SYSTEM CONFIGURATION -->

            <section class="data-form">
				<div class="header"></div>
				<div class="registration">
					<form action="controller/system-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

						<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> System Configuration <p>Last update: <?php  echo $config->getSystemConfigLastUpdate()  ?></p></label>

							<div class="col-md-6">
								<label for="sname" class="form-label">System Name<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="system_name" id="sname" required value="<?php  echo $config->getSystemName()  ?>">
								<div class="invalid-feedback">
								Please provide a System Name.
								</div>
							</div>

							<div class="col-md-6">
								<label for="cright" class="form-label">System Copyright<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="system_copy_right" id="cright" required value="<?php  echo $config->getSystemCopyright() ?>">
								<div class="invalid-feedback">
								Please provide a System Copyright.
								</div>
							</div>

							<div class="col-md-6" >
								<label for="phone_number" class="form-label">Default Phone Number<span> *</span></label>
								<div class="input-group flex-nowrap">
								<span class="input-group-text" id="addon-wrapping">+63</span>
								<input type="text" class="form-control numbers"  autocapitalize="off" inputmode="numeric" autocomplete="off" name="system_phone_number" id="phone_number" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required value="<?php  echo $config->getSystemNumber()  ?>">
								</div>
							</div>

							<div class="col-md-6">
								<label for="email" class="form-label">Default Email<span> *</span></label>
								<input type="email" class="form-control" autocapitalize="off" autocomplete="off" name="system_email" id="email" required value="<?php  echo $config->getSystemEmail()  ?>">
								<div class="invalid-feedback">
								Please provide a valid Email.
								</div>
							</div>

						</div>

						<div class="addBtn">
							<button type="submit" class="btn-warning" name="btn-update-system" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
                </div>
            </section>
			
			<!-- System Logo  -->

			<section class="data-form">
				<div class="header"></div>
				<div class="registration">
					<form action="controller/system-controller.php" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

							<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Logo Configuration <p>Last update: <?php  echo $config->getSystemConfigLastUpdate()  ?></p></label>

							<div class="col-md-12">
								<label for="logo" class="form-label">Upload Logo<span> *</span></label>
								<input type="file" class="form-control" name="system_logo" id="logo" style="height: 33px ;" required>
								<div class="invalid-feedback">
								Please provide a Logo.
								</div>
							</div>

						</div>

						<div class="addBtn" style="padding-top: 2rem;">
							<button type="submit" class="btn-warning" name="btn-update-logo" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
                </div>
            </section>

			<!-- SMTP MAILER -->

			<section class="data-form">
				<div class="header"></div>
				<div class="registration">
					<form action="controller/system-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

						<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> SMTP Email Configuration <p>Last update: <?php  echo $config->getEmailConfigLastUpdate()  ?></p></label>

							<div class="col-md-6">
								<label for="email" class="form-label">Email<span> *</span></label>
								<input type="email" class="form-control" autocapitalize="off" autocomplete="off" name="email" id="email" required placeholder = "<?php  echo $config->getSmtpEmail()  ?>">
								<div class="invalid-feedback">
								Please provide a valid Email.
								</div>
							</div>

							<div class="col-md-6">
								<label for="Gpassword" class="form-label">Generated Password<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="password" id="Gpassword" required placeholder ="<?php  echo $config->getSmtpPassword()  ?>">
								<div class="invalid-feedback">
								Please provide a Generated Password.
								</div>
							</div>

						</div>

						<div class="addBtn">
							<button type="submit" class="btn-warning" name="btn-update-smtp" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
                </div>
            </section>

			<!-- Google reCAPTCHA V3  -->

			<section class="data-form">
				<div class="header"></div>
				<div class="registration">
					<form action="controller/system-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
						<div class="row gx-5 needs-validation">

						<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Google reCAPTCHA API Configuration <p>Last update: <?php  echo $config->getGoogleRecaptchaApiLastUpdate()  ?></p></label>

						<div class="col-md-6">
								<label for="Skey" class="form-label">Site Key<span> *</span></label>
								<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="site_key" id="site_key" required placeholder ="<?php  echo $config->getSKey()  ?>">
								<div class="invalid-feedback">
								Please provide a Site Key.
								</div>
						</div>

						<div class="col-md-6">
							<label for="Sskey" class="form-label">Site Secret Key<span> *</span></label>
							<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="site_secret_key" id="site_secret_key" required placeholder ="<?php  echo $config->getSSKey()  ?>">
							<div class="invalid-feedback">
							Please provide a Site Secret Key.
							</div>
						</div>

						</div>

						<div class="addBtn">
							<button type="submit" class="btn-warning" name="btn-update-recaptcha" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
						</div>
					</form>
                </div>
            </section>
		</main>
		<!-- MAIN -->
	</section>
	<!-- END NAVBAR -->
    <?php
    include_once '../../configuration/footer.php';
    ?>

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