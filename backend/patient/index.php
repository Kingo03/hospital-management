<?php 
session_start();
include('assets/inc/config.php');

if (isset($_POST['pat_login'])) {
    $pat_number = $_POST['pat_number'];
    $pat_pwd = $_POST['pat_pwd']; // Do not hash here

    // Debugging Step 1: Check if input is received
    echo "Entered Patient Number: " . htmlspecialchars($pat_number) . "<br>";

    // Prepare SQL statement
    $stmt = $mysqli->prepare("SELECT pat_pwd, pat_id FROM his_patients WHERE pat_number=?");
    if (!$stmt) {
        die("SQL Prepare Error: " . $mysqli->error);
    }

    $stmt->bind_param('s', $pat_number);
    if (!$stmt->execute()) {
        die("SQL Execution Error: " . $stmt->error);
    }

    $stmt->store_result();
    
    // Debugging Step 2: Check if user exists
    if ($stmt->num_rows === 0) {
        die("No matching user found! Check if pat_number is correct.");
    }

    $stmt->bind_result($db_pat_pwd, $db_pat_id);
    $stmt->fetch();

    // Debugging Step 3: Check fetched data
    echo "DB Password Hash: $db_pat_pwd <br>";

    // Verify password
    if (password_verify($pat_pwd, $db_pat_pwd)) { 
        $_SESSION['pat_id'] = $db_pat_id;
        $_SESSION['pat_number'] = $pat_number;

        session_write_close();
        header("Location: his_pat_dashboard.php");
        exit();
    } else {
        die("Access Denied: Incorrect password.");
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Hospital Management System - Patient Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/swal.js"></script>
    <?php if(isset($err)) { ?>
    <script>
        setTimeout(function () { 
            swal("Failed", "<?php echo $err; ?>", "error");
        }, 100);
    </script>
    <?php } ?>
</head>
<body class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <a href="index.php">
                                    <span><img src="assets\images\logoDOC.jpg" alt="" height="40"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Enter your patient number and password to access your panel.</p>
                            </div>
                            <form method='post'>
                                <div class="form-group mb-3">
                                    <label for="pat_number">Patient Number</label>
                                    <input class="form-control" name="pat_number" type="text" id="pat_number" required placeholder="Enter your patient number">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="pat_pwd">Password</label>
                                    <input class="form-control" name="pat_pwd" type="password" id="pat_pwd" required placeholder="Enter your password">
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-success btn-block" name="pat_login" type="submit"> Log In </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <!-- <p> <a href="his_patient_reset_pwd.php" class="text-white-50 ml-1">Forgot your password?</a></p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include ("assets/inc/footer1.php");?>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
