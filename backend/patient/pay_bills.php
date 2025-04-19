<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];
    
    // Fetch bill details
    $stmt = $mysqli->prepare("SELECT * FROM view_bills WHERE bill_id = ?");
    $stmt->bind_param('i', $bill_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bill = $result->fetch_object();
    
    // Razorpay Key and Secret
    $key_id = "YOUR_RAZORPAY_KEY_ID";  // Replace with your Razorpay Key ID
    $secret_key = "YOUR_RAZORPAY_SECRET_KEY";  // Replace with your Razorpay Secret Key
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <div id="wrapper">
        <h2>Bill Payment</h2>
        <p><strong>Bill Amount:</strong> ₹<?php echo number_format($bill->total_amount, 2); ?></p>

        <!-- Payment Form -->
        <form action="verify_payment.php" method="POST" id="payment-form">
            <input type="hidden" name="bill_id" value="<?php echo $bill->bill_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $bill->total_amount * 100; ?>"> <!-- Convert to paise -->
            <button id="pay-button">Pay Now</button>
        </form>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "<?php echo $key_id; ?>", // Your Razorpay Key ID
            "amount": "<?php echo $bill->total_amount * 100; ?>", // Amount in paise
            "currency": "INR",
            "name": "Hospital Management System",
            "description": "Bill Payment",
            "image": "logo.png",
            "handler": function (response) {
                document.getElementById("payment-form").submit();
            },
            "prefill": {
                "name": "<?php echo $_SESSION['pat_name']; ?>", // Patient's name
                "email": "<?php echo $_SESSION['pat_email']; ?>" // Patient's email
            }
        };

        var rzp1 = new Razorpay(options);
        document.getElementById("pay-button").onclick = function(e) {
            e.preventDefault();
            rzp1.open();
        };
    </script>
</body>
</html>
