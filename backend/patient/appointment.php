<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid = $_SESSION['pat_id']; // Get logged-in patient ID

  if (isset($_POST['appointment_date'], $_POST['patient_email'], $_POST['reason'], $_POST['pat_gender'])) {
      
    // Sanitize form data
    $appointment_date = $_POST['appointment_date'];
    $patient_email = $_POST['patient_email'];
    $reason = $_POST['reason'];
    $pat_gender = $_POST['pat_gender'];

    // Insert into the database
    $query = "INSERT INTO his_appointments (pat_id, appointment_date, patient_email, reason, gender) 
              VALUES (?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('issss', $pat_id, $appointment_date, $patient_email, $reason, $pat_gender);

    // Execute the query
    if ($stmt->execute()) {
        echo "Appointment created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

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
                                <h4 class="page-title">Create Appointment</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <form action="process_appointment.php" method="POST">
                                    <div class="form-group">
                                        <label for="appointment_date">Appointment Date</label>
                                        <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="patient_email">Email</label>
                                        <input type="email" class="form-control" id="patient_email" name="patient_email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="reason">Reason for Appointment</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pat_gender">Gender</label>
                                        <select class="form-control" id="pat_gender" name="pat_gender" required>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Appointment</button>
                                </form>
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
