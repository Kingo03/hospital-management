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

// Fetch patient's medications from the database
$query = "SELECT * FROM his_medications WHERE pat_id = ? ORDER BY medication_date DESC";
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
                                    <h4 class="page-title">Patient Medications</h4>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Medications Table -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card-box">
                                    <h4 class="header-title mb-3">Your Medications</h4>
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover table-centered m-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Medication Name</th>
                                                    <th>Dosage</th>
                                                    <th>Frequency</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <?php while($row = $res->fetch_object()) { ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $row->medication_name; ?></td>
                                                    <td><?php echo $row->dosage; ?></td>
                                                    <td><?php echo $row->frequency; ?></td>
                                                    <td><?php echo $row->medication_date; ?></td>
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
