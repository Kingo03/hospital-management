<?php
$mysqli = new mysqli("localhost", "root", "", "hmisphp");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$action = $_POST['action'] ?? '';

$time_slots = [
    "09:00 AM - 09:30 AM", "09:30 AM - 10:00 AM", "10:00 AM - 10:30 AM",
    "10:30 AM - 11:00 AM", "11:00 AM - 11:30 AM", "11:30 AM - 12:00 PM",
    "12:00 PM - 12:30 PM", "12:30 PM - 01:00 PM", "02:00 PM - 02:30 PM",
    "02:30 PM - 03:00 PM", "03:00 PM - 03:30 PM", "03:30 PM - 04:00 PM"
];

if ($action == "add") {
    $patient_name = $mysqli->real_escape_string($_POST['patient_name']);
    $patient_email = $mysqli->real_escape_string($_POST['patient_email']);
    $pat_gender = $mysqli->real_escape_string($_POST['pat_gender']);
    $appointment_date = strtotime($_POST['appointment_date']);
    $appointment_date_formatted = date('Y-m-d', $appointment_date);
    $reason = $mysqli->real_escape_string($_POST['reason']);
    $doc_id = intval($_POST['doc_id']);
    date_default_timezone_set('Asia/Kolkata'); // Set your timezone

    
    // Define available time slots (Adjust as needed)
    $time_slots = [
        "09:00 AM", "09:30 AM", "10:00 AM", "10:30 AM",
        "11:00 AM", "11:30 AM", "12:00 PM", "12:30 PM",
        "02:00 PM", "02:30 PM", "03:00 PM", "03:30 PM"
    ];
    
    $current_time = date("H:i"); // Get current time (24-hour format)
    $current_date = date("Y-m-d"); // Get today's date
    
    // Remove past time slots if booking for today
    if ($appointment_date_formatted == $current_date) {
        $time_slots = array_filter($time_slots, function ($slot) use ($current_time) {
            return strtotime($slot) > strtotime($current_time);
        });
        $time_slots = array_values($time_slots); // Re-index array
    }
    
    // Fetch booked token numbers for the selected doctor and date
    $slot_query = $mysqli->query("SELECT token_number FROM appointments WHERE doc_id=$doc_id AND DATE(appointment_date) = '$appointment_date_formatted' ORDER BY token_number ASC");
    
    $booked_tokens = [];
    while ($row = $slot_query->fetch_assoc()) {
        $booked_tokens[] = $row['token_number'];
    }
    
    // Find the next available token number
    $next_token = 1;
    while (in_array($next_token, $booked_tokens)) {
        $next_token++;
    }
    
    // Ensure token number does not exceed available slots
    if ($next_token > count($time_slots)) {
        echo json_encode(["status" => "error", "message" => "No more slots available for the selected date."]);
        exit;
    }
    
    // Assign the corresponding time slot
    $assigned_time = isset($time_slots[$next_token - 1]) ? $time_slots[$next_token - 1] : "Not Assigned";
    
    // Insert appointment with assigned token number
    $query = "INSERT INTO appointments (patient_name, patient_email, pat_gender, appointment_date, reason, doc_id, token_number, time_slot) 
              VALUES ('$patient_name', '$patient_email', '$pat_gender', '$appointment_date_formatted', '$reason', $doc_id, $next_token, '$assigned_time')";
    
    if ($mysqli->query($query) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Appointment booked! Token: $next_token, Time Slot: $assigned_time"]);
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error]);
    }
    exit;
    

    
}

// Fetching doctors
$doctor_result = $mysqli->query("SELECT doc_id, CONCAT(doc_fname, ' ', doc_lname) AS name, doc_dept FROM his_docs");
$doctors = $doctor_result->fetch_all(MYSQLI_ASSOC);

// Fetching appointments
$appointment_result = $mysqli->query("
    SELECT a.*, CONCAT(d.doc_fname, ' ', d.doc_lname) AS doctor_name 
    FROM appointments a 
    JOIN his_docs d ON a.doc_id = d.doc_id 
    ORDER BY a.appointment_date ASC
");

$appointments = $appointment_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment Booking</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
/* Styling */
body { font-family: Arial, sans-serif; background-color: #f4f4f9; text-align: center; }
.container { width: 80%; margin: auto; overflow: hidden; }
h2 { color: #333; margin-bottom: 20px; }
form { background: #fff; padding: 20px; margin: 20px auto; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); max-width: 600px; text-align: left; }
label { display: block; margin: 10px 0 5px; font-weight: bold; }
input, select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
button { display: block; width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 4px; }
button:hover { background: #0056b3; }
</style>
<body>
    <h2>Book an Appointment</h2>
    <form id="appointment-form">
        <label>Name:</label><input type="text" name="patient_name" required>
        <label>Email:</label><input type="email" name="patient_email" required>
        <label>Gender:</label>
        <select name="pat_gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label>Date:</label>
        <input type="date" name="appointment_date" min="<?= date('Y-m-d'); ?>" required>
        <label>Reason:</label><input type="text" name="reason" required>
        <label>Doctor:</label>
        <select name="doc_id" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $doctor) {
                echo "<option value='{$doctor['doc_id']}'>{$doctor['name']} ({$doctor['doc_dept']})</option>";
            } ?>
        </select>
        <button type="submit">Book</button>
    </form>

    <script>
        $("#appointment-form").submit(function(event) {
            event.preventDefault();
            $.post("", $(this).serialize() + "&action=add", function(response) {
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            }, "json").fail(function() {
                alert("Failed to connect to the server.");
            });
        });
    </script>
</body>
</html>
