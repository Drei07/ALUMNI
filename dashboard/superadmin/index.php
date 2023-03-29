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
	<title>Track Me | Dashboard</title>
</head>
<body>
<!-- Loader -->
<div class="loader"></div>

<section id="sidebar" class="hide">
		<a href="" class="brand"><img src="../../src/img/main2_logo.png" alt="logo" class="brand-img"></a>
		<ul class="side-menu">
			<li><a href="" class="active"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>
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
			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="home" >Home</a></li>
				<li class="divider">|</li>
                <li><a href="" class="active">Dashboard</a></li>
			</ul>
			<div class="dashboard-data">
				<div class="dashboard-card">
					<div class="head">
						<div>
							<?php
								$employer_stmt = $user->runQuery("SELECT * FROM users WHERE user_type = :user_type");
								$employer_stmt->execute(array(":user_type" => 2));
								$employer_count = $employer_stmt->rowCount();

								echo
								"
									<h2>$employer_count</h2>
								";
							?>
							<p>Employer</p>
						</div>
						<i class='bx bxs-user icon' ></i>
					</div>
					<span class="progress" data-value="40%"></span>				
				</div>
				<div class="dashboard-card">
					<div class="head">
						<div>
							<?php
								$alumni_stmt = $user->runQuery("SELECT * FROM users WHERE user_type = :user_type");
								$alumni_stmt->execute(array(":user_type" => 3));
								$alumni_count = $alumni_stmt->rowCount();

								echo
								"
									<h2>$alumni_count</h2>
								";
							?>
							<p>Alumni</p>
						</div>
						<i class='bx bxs-user-pin icon' ></i>
					</div>
					<span class="progress" data-value="30%"></span>
				</div>
			</div>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Calendar</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>
					<div class="chart">
						<div id="chart"></div>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- END NAVBAR -->

    <?php
    include_once '../../configuration/footer.php';
    ?>
	
	<script>

		// Signout
		$('.btn-signout').on('click', function(e){
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