<?php 
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

$pat_id = $_SESSION['pat_id']; // Get logged-in patient ID
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php'); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">My Prescriptions</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Prescription Date</th>
                                                <th>Doctor Name</th>
                                                <th>Medicines</th>
                                                <th>Dosage</th>
                                                <th>Instructions</th>
                                                <th>Patient Type</th>
                                                <th>Patient Age</th>
                                                <th>Ailment</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                // Fetch prescriptions for the patient
                                                $query = "SELECT * FROM his_prescriptions WHERE pat_id = ?"; 
                                                $stmt = $mysqli->prepare($query);
                                                
                                                if (!$stmt) {
                                                    die("Query preparation failed: " . $mysqli->error);
                                                }

                                                $stmt->bind_param('i', $pat_id);
                                                $stmt->execute();
                                                $res = $stmt->get_result();

                                                if ($res->num_rows == 0) {
                                                    echo "<tr><td colspan='10' class='text-center'>No prescriptions found</td></tr>";
                                                }

                                                $cnt = 1;
                                                while($row = $res->fetch_object()) {
                                                    echo "<tr>";
                                                    echo "<td>{$cnt}</td>";
                                                    echo "<td>" . date("d-m-Y", strtotime($row->prescription_date)) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->doc_fname) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->medicines) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->dosage) . "</td>";
                                                    echo "<td>" . nl2br(htmlspecialchars($row->instructions)) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->pres_pat_type) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->pres_pat_age) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->pres_pat_ailment) . "</td>";
                                                    echo "<td>";
                                                    echo "<a href='view_single_prescription.php?prescription_id=" . urlencode($row->prescription_id) . "' class='badge badge-success'>View</a> ";
                                                    echo "<a href='cancel_prescription.php?prescription_id=" . urlencode($row->prescription_id) . "' class='badge badge-danger' onclick=\"return confirm('Are you sure you want to cancel this prescription?');\">Cancel</a>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                    $cnt++;
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('assets/inc/footer.php'); ?>
        </div>
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
