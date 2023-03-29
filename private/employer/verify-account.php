<?php
include_once __DIR__. '/src/api/api.php';
include_once '../../dashboard/employer/authentication/employer-verify.php';
include_once '../../configuration/settings-configuration.php';

$config = new SystemConfig();
$main_url = new MainUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../../src/img/<?php echo $config->getSystemLogo() ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/signin.css?v=<?php echo time(); ?>">
    <title>Track Me | Verify Account</title>
</head>
<body class="my-login-page">
	<div class="container" id="container">
			<div class="form-container sign-in-container">
				<form method="POST">
					<h1>Verify Account</h1>
                    <?php if(isset($msg)) { echo $msg; } ?>
                </form>
			</div>
			<div class="overlay-container">
				<div class="overlay">
					<div class="overlay-panel overlay-right">
						<img src="../../src/img/sign_in_logo.png" width="60%" alt="logo">
						<p>Stay on track with Track Me - your ultimate employment and document solution!</p>
					</div>
				</div>
			</div>
		</div>
		<footer>&copy; <?php echo $config->getSystemCopyright() ?></footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="../../src/node_modules/jquery/dist/jquery.min.js"></script>
	<script src="../../src/js/signin.js"></script>


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