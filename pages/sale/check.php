<?php

$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';

// Check if dress_id is provided
if (!isset($_GET['dress_id']) || empty($_GET['dress_id'])) {
    die("Dress ID is required.");
}

$dress_id = (int)$_GET['dress_id'];

// Fetch booking data
$sql = "SELECT start_date, end_date, customer_name, status FROM bookings WHERE dress_id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Database query error: " . $con->error);
}

$stmt->bind_param("i", $dress_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}








$sql="SELECT * FROM dress WHERE id = $dress_id";
$result = $con->query($sql);
$row = $result->fetch_assoc();
$dress_name = $row['dress_name'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dress Booking Calendar</title>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <style>
        /* Center the calendar on the page */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h1 {
            margin-top: 20px;
            font-size: 1.5em;
            color: #333;
        }

        p {
            color: #555;
        }

        #calendar {
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        /* Adjust calendar header styles */
        .fc-toolbar {
            font-size: 14px;
            margin: 10px;
        }

        /* Customize event colors and styles */
        .fc-event {
            font-size: 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Booking Calendar for Dress ID:
         <?= htmlspecialchars($dress_name); ?></h1>

    <?php if (empty($bookings)): ?>
        <p>No bookings found for this dress.</p>
    <?php else: ?>
        <div id="calendar"></div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto', // Adjust height to fit content
                    contentHeight: 400, // Fixed content height
                    events: [
                        <?php foreach ($bookings as $booking): ?>
                        {
                            title: "<?= htmlspecialchars($booking['customer_name'] . ' (' . $booking['status'] . ')'); ?>",
                            start: "<?= $booking['start_date']; ?>",
                            end: "<?= date('Y-m-d', strtotime($booking['end_date'] . ' +1 day')); ?>",
                            color: "<?= $booking['status'] === 'booked' ? 'green' : ($booking['status'] === 'returned' ? 'blue' : 'red'); ?>"
                        },
                        <?php endforeach; ?>
                    ]
                });
                calendar.render();
            });
        </script>
    <?php endif; ?>
</body>
</html>
