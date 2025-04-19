<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid = $_SESSION['pat_id']; // Get logged-in patient ID
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?> <!-- Include head section for styling and meta tags -->

<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php'); ?> <!-- Include top navigation bar -->
        <?php include("assets/inc/sidebar.php"); ?> <!-- Include left sidebar -->

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">My Billing</h4>
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
                                                <th>Bill Date</th>
                                                <th>Total Amount</th>
                                                <th>Payment Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $ret = "SELECT * FROM bills WHERE pat_id = ?";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('i', $aid);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while($row = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row->bill_date; ?></td>
                                                <td><?php echo number_format($row->total_amount, 2); ?></td>
                                                <td><?php echo $row->payment_status; ?></td>
                                                <td>
                                                    <a href="view_single_bill.php?bill_id=<?php echo $row->bill_id; ?>" class="badge badge-success">View</a>
                                                    <?php if($row->payment_status == 'Unpaid'): ?>
                                                        <a href="pay_bill.php?bill_id=<?php echo $row->bill_id; ?>" class="badge badge-warning">Pay</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php $cnt++; } ?>
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
