<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $pat_id = $_SESSION['pat_id'];
  $pat_number = $_SESSION['pat_number'];

?>
<!DOCTYPE html>
<html lang="en">

    <!--Head Code-->
    <?php include("assets/inc/head.php");?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include('assets/inc/sidebar.php');?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Patient Dashboard</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        
                        <div class="row">
                            <!-- Start Profile -->
                            <div class="col-md-6 col-xl-4">
                                <a href="patient_profile.php">
                                    <div class="widget-rounded-circle card-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                    <i class="fas fa-user-circle font-22 avatar-title text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-right">
                                                    <h3 class="text-dark mt-1"></h3>
                                                    <p class="text-muted mb-1 text-truncate">My Profile</p>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>
                                </a> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!-- End Profile -->

                            <!-- Start Appointments -->
                            <div class="col-md-6 col-xl-4">
                                <a href="patient_appointments.php">
                                    <div class="widget-rounded-circle card-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                                    <i class="fas fa-calendar-check font-22 avatar-title text-success"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-right">
                                                    <?php
                                                        // Updated query to fetch the count from 'his_appointments' table
                                                        $result = "SELECT count(*) FROM his_appointments WHERE pat_id = ?";
                                                        $stmt = $mysqli->prepare($result);
                                                        $stmt->bind_param("i", $pat_id);
                                                        $stmt->execute();
                                                        $stmt->bind_result($appointments);
                                                        $stmt->fetch();
                                                        $stmt->close();
                                                    ?>
                                                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $appointments;?></span></h3>
                                                    <p class="text-muted mb-1 text-truncate">Appointments</p>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>
                                </a> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!-- End Appointments -->

                            <!-- Start Medication -->
                            <div class="col-md-6 col-xl-4">
                                <a href="patient_medications.php">
                                    <div class="widget-rounded-circle card-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                                    <i class="fas fa-pills font-22 avatar-title text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-right">
                                                    <?php
                                                        // Updated query to fetch the count from 'his_medications' table
                                                        $result = "SELECT count(*) FROM his_medications WHERE pat_id = ?";
                                                        $stmt = $mysqli->prepare($result);
                                                        $stmt->bind_param("i", $pat_id);
                                                        $stmt->execute();
                                                        $stmt->bind_result($his_medications);
                                                        $stmt->fetch();
                                                        $stmt->close();
                                                    ?>
                                                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $his_medications;?></span></h3>
                                                    <p class="text-muted mb-1 text-truncate">Prescriptions</p>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>
                                </a> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!-- End Medication -->

                            <!-- Start Consulted Doctors -->
                            <div class="col-md-6 col-xl-4">
                                <a href="patient_consulted_doctors.php">
                                    <div class="widget-rounded-circle card-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                                    <i class="fas fa-user-md font-22 avatar-title text-info"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-right">
                                                    <?php
                                                        // Updated query to fetch the count from 'consulted_doctors' table
                                                        $result = "SELECT count(*) FROM consulted_doctors WHERE pat_id = ?";
                                                        $stmt = $mysqli->prepare($result);
                                                        $stmt->bind_param("i", $pat_id);
                                                        $stmt->execute();
                                                        $stmt->bind_result($consulted_doctors);
                                                        $stmt->fetch();
                                                        $stmt->close();
                                                    ?>
                                                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $consulted_doctors;?></span></h3>
                                                    <p class="text-muted mb-1 text-truncate">Consulted Doctors</p>
                                                </div>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>
                                </a> <!-- end widget-rounded-circle-->
                            </div> <!-- end col-->
                            <!-- End Consulted Doctors -->

                        </div> <!-- End row-->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div> <!-- End page -->

    </body>
</html>
