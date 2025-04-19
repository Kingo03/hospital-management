<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

// Ensure patient ID is set
$pat_id = isset($_SESSION['pat_id']) ? (int) $_SESSION['pat_id'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<?php include("assets/inc/head.php"); ?>

<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php'); ?>
        <?php include('assets/inc/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">My Appointments</h4>
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
                                                <th>Doctor</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($pat_id > 0) {
                                                // Fetch appointments
                                                $query = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, d.doc_fname 
                                                          FROM his_appointments a 
                                                          JOIN his_docs d ON a.doc_id = d.doc_id 
                                                          WHERE a.pat_id = ?";

                                                if ($stmt = $mysqli->prepare($query)) {
                                                    $stmt->bind_param("i", $pat_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;

                                                    while ($row = $res->fetch_object()) {
                                            ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo htmlspecialchars($row->doc_fname); ?></td>
                                                            <td><?php echo date("d-m-Y", strtotime($row->appointment_date)); ?></td>
                                                            <td><?php echo date("h:i A", strtotime($row->appointment_time)); ?></td>
                                                            <td>
                                                                <a href="cancel_appointment.php?id=<?php echo $row->appointment_id; ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                                                            </td>
                                                        </tr>
                                            <?php
                                                        $cnt++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>Error: " . $mysqli->error . "</td></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>Invalid Patient ID</td></tr>";
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
</body>
</html>
