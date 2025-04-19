<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$pat_id = $_SESSION['pat_id'];

// Check if patient exists
$patient_check_query = "SELECT pat_id FROM his_patients WHERE pat_id = ?";
$stmt = $mysqli->prepare($patient_check_query);
$stmt->bind_param("i", $pat_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    die("Error: Patient does not exist.");
}
$stmt->close();

if(isset($_POST['book_appointment'])){
    $doc_id = $_POST['doc_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    
    $query = "INSERT INTO his_appointments (pat_id, doc_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iiss", $pat_id, $doc_id, $appointment_date, $appointment_time);
    
    if($stmt->execute()){
        header("Location: view_appointments.php"); // Redirect to view appointments page
        exit();
    } else {
        $error = "Error! Try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include("assets/inc/head.php");?>
<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php');?>
        <?php include('assets/inc/sidebar.php');?>
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Book Appointment</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="post">
                                <label>Doctor</label>
                                <select name="doc_id" class="form-control" required>
    <option value="">Select Doctor</option>
    <?php
    $result = $mysqli->query("SELECT doc_id, CONCAT(doc_fname, ' ', doc_lname) AS doc_name FROM his_docs");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['doc_id']."'>".$row['doc_name']."</option>";
        }
    } else {
        echo "<option value=''>No doctors available</option>";
    }
    ?>
</select>

                                <label>Date</label>
                                <input type="date" name="appointment_date" class="form-control" required>
                                <label>Time</label>
                                <input type="time" name="appointment_time" class="form-control" required>
                                <br>
                                <button type="submit" name="book_appointment" class="btn btn-primary">Book Appointment</button>
                            </form>
                            <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('assets/inc/footer.php');?>
        </div>
    </div>
</body>
</html>
