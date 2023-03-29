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
	<title>Track Me | Employer</title>
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
			<h1 class="title">Employer</h1>
			<ul class="breadcrumbs">
				<li><a href="./" >Home</a></li>
				<li class="divider">|</li>
                <li><a href="" class="active">Employer Dashboard</a></li>
			</ul>
			<div class="level">
                <button type="button" data-bs-toggle="modal" data-bs-target="#classModal"><i class='bx bx-plus-medical'></i> Add Employer</button>
            </div>
            <section class="data-table">
                <div class="searchBx">
                    <input type="input" placeholder="Search employer...." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
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
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user icon'></i> Add Employer</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<section class="data-form-modals">
							<div class="registration">
								<form action="controller/user-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
									<div class="row gx-5 needs-validation">

                                        <div class="col-md-12">
											<label for="first_name" class="form-label">First Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="first_name" id="first_name" required>
											<div class="invalid-feedback">
											Please provide a First Name.
											</div>
										</div>


										<div class="col-md-12">
											<label for="middle_name" class="form-label">Middle Name</label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="middle_name" id="middle_name">
										</div>


										<div class="col-md-12">
											<label for="last_name" class="form-label">Last Name<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="last_name" id="last_name" required>
											<div class="invalid-feedback">
											Please provide a Last Name.
											</div>
										</div>

                                        <div class="col-md-6" >
                                            <label for="phone_number" class="form-label">Phone Number<span> *</span></label>
                                            <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping">+63</span>
                                            <input type="text" class="form-control numbers"  autocapitalize="off" inputmode="numeric" autocomplete="off" name="phone_number" id="phone_number" required minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  placeholder="10-digit number">
                                            </div>
                                        </div>


										<div class="col-md-12">
											<label for="position" class="form-label">Position<span> *</span></label>
											<input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" autocapitalize="on"  autocomplete="off" name="position" id="position" required>
											<div class="invalid-feedback">
											Please provide a Position.
											</div>
										</div>


										<div class="col-md-12">
											<label for="email" class="form-label">Email<span> *</span></label>
											<input type="email" class="form-control" autocapitalize="on"  autocomplete="off" name="email" id="email" required>
											<div class="invalid-feedback">
											Please provide a Email.
											</div>
										</div>

									</div>

									<div class="addBtn">
										<button type="submit" class="btn-warning" name="btn-add-employer" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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
            url:"tables/employer-tables.php",
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