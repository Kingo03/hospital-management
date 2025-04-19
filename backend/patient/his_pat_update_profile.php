<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();  // Assuming you have a DB connection script

// Fetching current profile details
$aid = $_SESSION['pat_id'];
$query = "SELECT * FROM his_patients WHERE pat_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $aid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_object();

// Handling form submission to update the profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['pat_fname'];
    $lname = $_POST['pat_lname'];
    $email = $_POST['pat_email'];
    $phone = $_POST['pat_phone'];
    $gender = $_POST['pat_gender'];
    $dob = $_POST['pat_dob'];
    $dpic = $_FILES['pat_dpic']['name'];

    // Handle file upload (Profile picture)
    if ($dpic) {
        $target_dir = "assets/images/users/";
        $target_file = $target_dir . basename($_FILES["pat_dpic"]["name"]);
        move_uploaded_file($_FILES["pat_dpic"]["tmp_name"], $target_file);
    } else {
        $dpic = $row->pat_dpic; // Retain the previous profile picture if no new one is uploaded
    }

    // Update patient details in the database
    $update_query = "UPDATE his_patients SET pat_fname = ?, pat_lname = ?, pat_email = ?, pat_phone = ?, pat_gender = ?, pat_dob = ?, pat_dpic = ? WHERE pat_id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('sssssssi', $fname, $lname, $email, $phone, $gender, $dob, $dpic, $aid);
    $update_stmt->execute();

    // Redirect after update
    header('Location: view_profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <!-- Include your CSS here -->
</head>
<body>
    <div class="container">
        <h2>Update Your Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pat_fname">First Name:</label>
                <input type="text" id="pat_fname" name="pat_fname" value="<?php echo $row->pat_fname; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pat_lname">Last Name:</label>
                <input type="text" id="pat_lname" name="pat_lname" value="<?php echo $row->pat_lname; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pat_email">Email:</label>
                <input type="email" id="pat_email" name="pat_email" value="<?php echo $row->pat_email; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pat_phone">Phone:</label>
                <input type="text" id="pat_phone" name="pat_phone" value="<?php echo $row->pat_phone; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pat_gender">Gender:</label>
                <select id="pat_gender" name="pat_gender" class="form-control">
                    <option value="Male" <?php echo ($row->pat_gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($row->pat_gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo ($row->pat_gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pat_dob">Date of Birth:</label>
                <input type="date" id="pat_dob" name="pat_dob" value="<?php echo $row->pat_dob; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="pat_dpic">Profile Picture:</label>
                <input type="file" id="pat_dpic" name="pat_dpic" class="form-control">
                <img src="assets/images/users/<?php echo $row->pat_dpic;?>" alt="Current Profile Picture" height="150" class="mt-2">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
