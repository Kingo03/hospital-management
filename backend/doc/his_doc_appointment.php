<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

$doc_id = $_SESSION['doc_id']; // Get the logged-in doctor's ID
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
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Appointments</a></li>
                                        <li class="breadcrumb-item active">View Appointments</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Your Appointments</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Token Number</th>
                                                <th>Patient Name</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Slot</th>
                                                <th>Patient Email</th>
                                                <th>Reason</th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch only appointments assigned to the logged-in doctor
                                            $query = "SELECT id, token_number, patient_name, appointment_date, time_slot, patient_email, reason 
                                                      FROM appointments 
                                                      WHERE doc_id = ? 
                                                      ORDER BY appointment_date DESC";

                                            if ($stmt = $mysqli->prepare($query)) {
                                                $stmt->bind_param("i", $doc_id);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $cnt = 1;

                                                while ($row = $res->fetch_object()) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row->token_number; ?></td>
                                                        <td><?php echo $row->patient_name; ?></td>
                                                        <td><?php echo $row->appointment_date; ?></td>
                                                        <td><?php echo $row->time_slot; ?></td>
                                                        <td><?php echo $row->patient_email; ?></td>
                                                        <td><?php echo $row->reason; ?></td>
                                                        <!-- <td>
                                                            <a href="his_admin_view_single_appointment.php?appointment_id=<?php echo $row->appointment_id; ?>" class="badge badge-success">
                                                                <i class="mdi mdi-eye"></i> View
                                                            </a>
                                                        </td> -->
                                                    </tr>
                                            <?php 
                                                    $cnt++; 
                                                }
                                                $stmt->close();
                                            } 
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="8">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
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

    <div class="rightbar-overlay"></div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/libs/footable/footable.all.min.js"></script>
    <script src="assets/js/pages/foo-tables.init.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
