<?php
include 'db_connection.php';

// Set the default timezone
date_default_timezone_set('Europe/Copenhagen');

// Capture form data from POST request
$local_start_datetime = $_POST['local_start_datetime'];
$timezone_offset = $_POST['timezone_offset'];

// Convert local start datetime to DateTime object
$start_time = new DateTime($local_start_datetime);
$current_time = new DateTime("now");

// Adjust start time based on the user's timezone offset
$start_time->modify("{$timezone_offset} minutes");

// Debug: Current time and start time
error_log("Current time: " . $current_time->format("Y-m-d H:i:s"));
error_log("Start time: " . $start_time->format("Y-m-d H:i:s"));

// Calculate hours and minutes since drinking started
$interval = $current_time->diff($start_time);
$hours_since_start = ($interval->days * 24) + $interval->h;
$minutes_since_start = $interval->i;

// Debug: Hours and minutes since start
error_log("Hours since start: " . $hours_since_start);
error_log("Minutes since start: " . $minutes_since_start);

// Redirect to index.php with the result message
$result_message = "Time since start: " . $hours_since_start . " hours and " . $minutes_since_start . " minutes.";
error_log("Result message: " . $result_message);
header("Location: index.php?result=" . urlencode($result_message));
exit();
?>
