<?php

include('config.php'); // Ensure this file establishes a proper connection

$pat_id = $_SESSION['pat_id'];
$pat_number = $_SESSION['pat_number'];

$ret = "SELECT * FROM his_patients WHERE pat_id = ? AND pat_number = ?";
$stmt = $mysqli->prepare($ret);

if ($stmt === false) {
    die("MySQL Error: " . $mysqli->error);
}

$stmt->bind_param('is', $pat_id, $pat_number);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_object()) {
?>
   
    <div class="navbar-custom">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="d-none d-sm-block">
                <form class="app-search">
                    <div class="app-search-box">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <button class="btn" type="submit">
                                    <i class="fe-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </li>

            <li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <img src="assets/images/users/<?php echo $row->pat_dpic;?>" alt="dpic" class="rounded-circle">
        <span class="pro-user-name ml-1">
            <?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?> <i class="mdi mdi-chevron-down"></i> 
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <!-- <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div> -->

                    <!-- item-->
                    <!-- <a href="his_admin_account.php" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a> -->


                    <!-- <div class="dropdown-divider"></div> -->

                    <!-- item-->
                    <a href="his_pat_logout_partial.php" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>

                </div>
</li>


        </ul>

        <div class="logo-box">
            <a href="his_admin_dashboard.php" class="logo text-center">
                <span class="logo-lg">
                    <img src="assets/images/logo-light.png" alt="" height="18">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm-white.png" alt="" height="24">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li class="dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    Create New
                    <i class="mdi mdi-chevron-down"></i> 
                </a>
                <div class="dropdown-menu">
                    <a href="his_admin_add_employee.php" class="dropdown-item">
                        <i class="fe-users mr-1"></i>
                        <span>Employee</span>
                    </a>
                    <a href="his_admin_register_patient.php" class="dropdown-item">
                        <i class="fe-activity mr-1"></i>
                        <span>Patient</span>
                    </a>
                    <a href="his_admin_add_payroll.php" class="dropdown-item">
                        <i class="fe-layers mr-1"></i>
                        <span>Payroll</span>
                    </a>
                    <a href="his_admin_add_vendor.php" class="dropdown-item">
                        <i class="fe-shopping-cart mr-1"></i>
                        <span>Vendor</span>
                    </a>
                    <a href="his_admin_add_medical_record.php" class="dropdown-item">
                        <i class="fe-list mr-1"></i>
                        <span>Medical Report</span>
                    </a>
                    <a href="his_admin_lab_report.php" class="dropdown-item">
                        <i class="fe-hard-drive mr-1"></i>
                        <span>Laboratory Report</span>
                    </a>
                    <a href="his_admin_surgery_records.php" class="dropdown-item">
                        <i class="fe-anchor mr-1"></i>
                        <span>Surgical/Theatre Report</span>
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
        </ul>
    </div>
<?php } ?>
