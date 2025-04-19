<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  
  $aid = $_SESSION['pat_id']; // Logged-in patient ID
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php');?>

<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php');?>
        <?php include("assets/inc/sidebar.php");?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">My Medical Records</h4>
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
                                                <th>Record Number</th>
                                                <th>Record Date</th>
                                                <th>Patient Name</th>
                                                <th>Age</th>
                                                <th>Ailment</th>
                                                <th>Prescription</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                // Fetch medical records for the logged-in patient
                                                $query = "SELECT mdr_number, mdr_date_rec, mdr_pat_name, mdr_pat_age, mdr_pat_ailment, mdr_pat_prescr 
                                                          FROM his_medical_records 
                                                          WHERE pat_id = ? ORDER BY mdr_date_rec DESC";
                                                
                                                $stmt = $mysqli->prepare($query);
                                                
                                                if (!$stmt) {
                                                    die("Query preparation failed: " . $mysqli->error);
                                                }

                                                $stmt->bind_param('i', $aid);
                                                $stmt->execute();
                                                $res = $stmt->get_result();

                                                if ($res->num_rows == 0) {
                                                    echo "<tr><td colspan='8' class='text-center'>No medical records found</td></tr>";
                                                }

                                                $cnt = 1;
                                                while ($row = $res->fetch_object()) {
                                                    echo "<tr>";
                                                    echo "<td>{$cnt}</td>";
                                                    echo "<td>" . htmlspecialchars($row->mdr_number) . "</td>";
                                                    echo "<td>" . date("d-m-Y H:i:s", strtotime($row->mdr_date_rec)) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->mdr_pat_name) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->mdr_pat_age) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->mdr_pat_ailment) . "</td>";
                                                    echo "<td>" . nl2br(htmlspecialchars($row->mdr_pat_prescr)) . "</td>";
                                                    echo "<td>";
                                                    echo "<a href='view_single_medical_record.php?mdr_number=" . urlencode($row->mdr_number) . "' class='badge badge-success'>View</a> ";
                                                    echo "<a href='delete_medical_record.php?mdr_number=" . urlencode($row->mdr_number) . "' class='badge badge-danger' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>";
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
