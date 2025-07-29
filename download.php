<?php
require 'vendor/autoload.php'; // Make sure you have the library installed via Composer

use Spipu\Html2Pdf\Html2Pdf;

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: parent_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the bookings data
    $bookings = json_decode($_POST['bookings'], true);
    $username = $_POST['username'];

    // Create the HTML content for the PDF
    $htmlContent = '<h1>Booking Details</h1>';
    $htmlContent .= '<p>Username: ' . htmlspecialchars($username) . '</p>';
    $htmlContent .= '<table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Bus Number</th>
                                <th>Driver Name</th>
                                <th>Starting Point</th>
                                <th>Stopping Point</th>
                                <th>Travel Date</th>
                                <th>Student Name</th>
                                <th>Parent Name</th>
                                <th>Parent Phone</th>
                                <th>Parent Email</th>
                            </tr>
                        </thead>
                        <tbody>';

    foreach ($bookings as $booking) {
        $htmlContent .= '<tr>
                            <td>' . htmlspecialchars($booking['bus_number']) . '</td>
                            <td>' . htmlspecialchars($booking['driver_name']) . '</td>
                            <td>' . htmlspecialchars($booking['starting_point']) . '</td>
                            <td>' . htmlspecialchars($booking['stopping_point']) . '</td>
                            <td>' . htmlspecialchars($booking['travel_date']) . '</td>
                            <td>' . htmlspecialchars($booking['student_name']) . '</td>
                            <td>' . htmlspecialchars($booking['parent_name']) . '</td>
                            <td>' . htmlspecialchars($booking['parent_phone']) . '</td>
                            <td>' . htmlspecialchars($booking['parent_email']) . '</td>
                        </tr>';
    }

    $htmlContent .= '</tbody></table>';

    // Create PDF
    try {
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($htmlContent);
        $html2pdf->output('booking_details.pdf');
    } catch (Html2PdfException $e) {
        $html2pdf->clean();
        $e = $e->getMessage();
    }
}
?>
