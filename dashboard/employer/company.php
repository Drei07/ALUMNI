<?php
require_once 'authentication/employer-class.php';
include_once '../../configuration/settings-configuration.php';

// instances of the classes
$config = new SystemConfig();
$user = new EMPLOYER();

// check if user is logged in and redirect if not
if (!$user->isUserLoggedIn()) {
    $user->redirect('../../private/employer/');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid" => $_SESSION['employerSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id        = $user_data['id'];
$user_profile   = $user_data['profile'];
$user_fname        = $user_data['first_name'];
$user_mname        = $user_data['middle_name'];
$user_lname        = $user_data['last_name'];
$user_fullname  = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_email        = $user_data['email'];
$user_last_update = $user_data['updated_at'];

// retrieve company data
$company_stmt = $user->runQuery("SELECT * FROM company WHERE user_id=:user_id");
$company_stmt->execute(array(":user_id" => $user_id));
$company_data = $company_stmt->fetch(PDO::FETCH_ASSOC);

$company_id    = $company_data['id'];
$company_name = $company_data['company_name'];
$company_address = $company_data['company_address'];
$company_email = $company_data['company_email'];
$company_phone_number = $company_data['company_phone_number'];
$company_description = $company_data['company_description'];
$company_logo = $company_data['company_logo'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | Company Profile</title>
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
            <li><a href="company" class="active"><i class='bx bxs-buildings icon'></i> Company</a></li>
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
                    <li><a href="settings"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="authentication/employer-signout" class="btn-signout"><i class='bx bxs-log-out-circle'></i> Signout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <h1 class="title">Company Profile</h1>
            <ul class="breadcrumbs">
                <li><a href="./">Home</a></li>
                <li class="divider">|</li>
                <li><a href="" class="active">Company Profile</a></li>

            </ul>

            <!-- PROFILE CONFIGURATION -->

            <section class="profile-form">
                <div class="header"></div>
                <div class="profile">
                    <div class="profile-img">
                        <img src="../../src/img/<?php echo $company_logo ?>" alt="logo">
                    </div>

                    <div id="Edit">
                        <form action="controller/company-controller.php?id=<?php echo $company_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                            <div class="row gx-5 needs-validation">

                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Company Profile</label>
                                <div class="col-md-12">
                                    <label for="first_name" class="form-label">Company Name<span>*</span></label>
                                    <input type="text" class="form-control" value="<?php echo $company_name ?>" autocapitalize="on" autocomplete="off" name="company_name" id="company_name" required>
                                    <div class="invalid-feedback">
                                        Please provide a Company Name.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="last_name" class="form-label">Company Address<span>*</span></label>
                                    <input type="text" class="form-control" value="<?php echo $company_address ?>" autocapitalize="on" autocomplete="off" name="company_address" id="company_address" required>
                                    <div class="invalid-feedback">
                                        Please provide a Company Address.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="phone_number" class="form-label">Company Phone Number</label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">+63</span>
                                        <input type="text" class="form-control numbers" alue="<?php echo $company_phone_number ?>" autocapitalize="off" inputmode="numeric" autocomplete="off" name="company_phone_number" id="company_phone_number" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="10-digit number">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="last_name" class="form-label">Company Description<span>*</span></label>
                                    <textarea class="form-control" value="<?php echo $company_description ?>" rows="5" name="company_description" id="company_description" required><?php echo $company_description ?></textarea>
                                    <div class="invalid-feedback">
                                        Please provide a Company Description.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="email" class="form-label">Company Email<span> *</span></label>
                                    <input type="email" class="form-control" value="<?php echo $company_email ?>" autocapitalize="on" autocomplete="off" name="company_email" id="company_email" required>
                                    <div class="invalid-feedback">
                                        Please provide a Company Email.
                                    </div>
                                </div>

                                <div class="addBtn">
                                    <button type="submit" class="warning" name="btn-update-company" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                </div>

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