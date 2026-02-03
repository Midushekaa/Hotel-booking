<?php
include 'components/connect.php'; // Database connection

// Check if admin is logged in
if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
}else{
    header('location:login.php');
    exit;
}

// Handle booking deletion
if(isset($_POST['delete'])){
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){
        $delete_booking = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_booking->execute([$delete_id]);
        $success_msg[] = 'Booking deleted successfully!';
    } else {
        $warning_msg[] = 'Booking already deleted or does not exist!';
    }
}

// Fetch all bookings
$select_bookings = $conn->prepare("SELECT * FROM `bookings` ORDER BY check_in DESC");
$select_bookings->execute();
$bookings = $select_bookings->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookings</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<link rel="stylesheet" href="css/admin_style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table, th, td {
    border: 1px solid #ccc;
}
th, td {
    padding: 12px;
    text-align: left;
}
th {
    background-color: #f4f4f4;
}
.btn {
    background-color: #e74c3c;
    color: #fff;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
}
.btn:hover {
    background-color: #c0392b;
}
</style>
</head>
<body>

<section class="grid">
    <h1 class="heading">All Bookings</h1>

    <?php if(count($bookings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Rooms</th>
                    <th>Adults</th>
                    <th>Childs</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['booking_id']); ?></td>
                    <td><?= htmlspecialchars($booking['name']); ?></td>
                    <td><?= htmlspecialchars($booking['email']); ?></td>
                    <td><?= htmlspecialchars($booking['number']); ?></td>
                    <td><?= htmlspecialchars($booking['check_in']); ?></td>
                    <td><?= htmlspecialchars($booking['check_out']); ?></td>
                    <td><?= htmlspecialchars($booking['rooms']); ?></td>
                    <td><?= htmlspecialchars($booking['adults']); ?></td>
                    <td><?= htmlspecialchars($booking['childs']); ?></td>
                    <td>
                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($booking['booking_id']); ?>">
                            <input type="submit" value="Delete" name="delete" class="btn">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; margin-top:20px;">No bookings found!</p>
    <?php endif; ?>
</section>

<!-- SweetAlert Notifications -->
<?php if(isset($success_msg)): ?>
<script>
<?php foreach($success_msg as $msg): ?>
swal("Success!", "<?= $msg ?>", "success");
<?php endforeach; ?>
</script>
<?php endif; ?>

<?php if(isset($warning_msg)): ?>
<script>
<?php foreach($warning_msg as $msg): ?>
swal("Warning!", "<?= $msg ?>", "warning");
<?php endforeach; ?>
</script>
<?php endif; ?>

<script src="js/admin_script.js"></script>
</body>
</html>
