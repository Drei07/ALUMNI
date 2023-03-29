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

$course_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM course WHERE id=:id");
$stmt->execute(array(":id"=>$course_id));
$course_data = $stmt->fetch(PDO::FETCH_ASSOC);

$course_name = $course_data['course'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once '../../configuration/header.php';
    ?>
    <title>Track Me | Edit Course</title>
</head>

<body>

    <!-- Loader -->
    <div class="loader"></div>
    <!-- EDIT COURSE -->
    <div class="class-modal">
        <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="header"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-building-house icon'></i> Edit
                            Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="location.href='course'"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <form action="controller/course-controller.php?id=<?php echo $course_id?>" method="POST"
                                    class="row gx-5 needs-validation" name="form" onsubmit="return validate()"
                                    novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">
                                        <div class="col-md-12">
                                            <label for="course" class="form-label">Course Name<span> *</span></label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                                value="<?php echo $course_name ?>" class="form-control"
                                                autocapitalize="on" autocomplete="off" name="course_name"
                                                id="course_name" placeholder="Ex. BS Information Technology" required>
                                            <div class="invalid-feedback">
                                                Please provide a Course Name.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-warning" name="btn-edit-course" id="btn-add"
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