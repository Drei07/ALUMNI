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

$user_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM users WHERE id=:id");
$stmt->execute(array(":id"=>$user_id));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$user_type          = $user_data['user_type'];;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | Edit User</title>
</head>

<body>

    <!-- Loader -->
    <div class="loader"></div>
    <!-- EDIT EMPLOYEE -->
    <?php

            if($user_type == 1){
        ?>

    <!-- code -->
    <?php
            }
            else if ($user_type == 2){
        ?>
    <div class="class-modal">
        <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="header"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-building-house icon'></i> Edit
                            User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="history.back()"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <?php
                                // employee data
                                    $first_name         = $user_data['first_name'];
                                    $middle_name        = $user_data['middle_name'];
                                    $last_name          = $user_data['last_name'];
                                    $phone_number       = $user_data['phone_number'];
                                    $position           = $user_data['position'];
                                ?>
                                <form action="controller/user-controller.php?id=<?php echo $user_id ?>" method="POST"
                                    class="row gx-5 needs-validation" name="form" onsubmit="return validate()"
                                    novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <div class="col-md-12">
                                            <label for="first_name" class="form-label">First Name<span> *</span></label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                class="form-control" value="<?php echo $first_name ?>"
                                                autocapitalize="on" autocomplete="off" name="first_name" id="first_name"
                                                required>
                                            <div class="invalid-feedback">
                                                Please provide a First Name.
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                class="form-control" value="<?php echo $middle_name ?>"
                                                autocapitalize="on" autocomplete="off" name="middle_name"
                                                id="middle_name">
                                        </div>


                                        <div class="col-md-12">
                                            <label for="last_name" class="form-label">Last Name<span> *</span></label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                class="form-control" value="<?php echo $last_name ?>"
                                                autocapitalize="on" autocomplete="off" name="last_name" id="last_name"
                                                required>
                                            <div class="invalid-feedback">
                                                Please provide a Last Name.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone_number" class="form-label">Phone Number<span>
                                                    *</span></label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">+63</span>
                                                <input type="text" class="form-control numbers" autocapitalize="off"
                                                    inputmode="numeric" value="<?php echo $phone_number ?>"
                                                    autocomplete="off" name="phone_number" id="phone_number" required
                                                    minlength="10" maxlength="10"
                                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                    placeholder="10-digit number">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <label for="position" class="form-label">Position<span> *</span></label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                class="form-control" value="<?php echo $position ?>" autocapitalize="on"
                                                autocomplete="off" name="position" id="position" required>
                                            <div class="invalid-feedback">
                                                Please provide a Position.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-warning" name="btn-edit-employer" id="btn-add"
                                            onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
            }
            else if ($user_type == 3){
        ?>
    <!-- code -->
    <?php
            }
        ?>
    <?php
    include_once '../../configuration/footer.php';
    ?>
    <script>
    //Load Modal
    $(window).on('load', function() {
        $('#editModal').modal('show');
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