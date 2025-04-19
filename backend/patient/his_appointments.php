
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

// Check if patient ID is set in session
if (!isset($_SESSION['pat_id']) || empty($_SESSION['pat_id'])) {
    echo "Patient ID is not set.";
    exit;
}

$pat_id = $_SESSION['pat_id'];

// Database connection check
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch patient's appointments from the database
$query = "SELECT * FROM his_appointments WHERE pat_id = ? ORDER BY appointment_date DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $pat_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

    <!--Head Code-->
    <?php include("assets/inc/head.php");?>

    <body>

        <div id="wrapper">

            <!-- Start Content -->
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        
                        <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Patient Appointments</h4>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Appointments Table -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box">
                                    <h4 class="header-title mb-3">Your Appointments</h4>
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover table-centered m-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Doctor Name</th>
                                                    <th>Specialization</th>
                                                    <th>Appointment Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <?php while($row = $res->fetch_object()) { ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $row->doc_fname . " " . $row->doc_lname; ?></td>
                                                    <td><?php echo $row->specialization; ?></td>
                                                    <td><?php echo $row->appointment_date; ?></td>
                                                    <td><?php echo $row->status; ?></td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </body>
</html>
