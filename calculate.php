<?php
include('db_connection.php');

// Capture form data from POST request
$gender = $_POST['gender'] ?? 'male';
$weight = str_replace(',', '.', $_POST['weight']);
$alcohol_consumed = $_POST['alcohol_consumed'];
$start_day = $_POST['start_day']; // "yesterday" or "today"
$start_hour = $_POST['start_hour'];
$start_minute = $_POST['start_minute'];
$timezone_offset = isset($_POST['timezone_offset']) ? (int) $_POST['timezone_offset'] : 0;

// Constants
$legal_limit = 0.5;
$alcohol_per_unit = 12;
$elimination_rate = 0.15;
$r = ($gender == 'male') ? 0.68 : 0.55;

// Convert weight and calculate total alcohol
$weight_in_grams = $weight * 1000;
$total_alcohol_grams = $alcohol_consumed * $alcohol_per_unit;
$initial_bac = ($total_alcohol_grams / ($weight_in_grams * $r)) * 100;

// Set start datetime based on "Yesterday" or "Today" selection
if ($start_day === "yesterday") {
    $start_datetime = date("Y-m-d", strtotime("yesterday")) . " $start_hour:$start_minute:00";
} else {
    $start_datetime = date("Y-m-d") . " $start_hour:$start_minute:00";
}

// Define constants for time calculations
define('SECONDS_IN_AN_HOUR', 3600);

// Print debug information to verify the constructed start datetime
echo "Constructed Start Datetime (Local Time): $start_datetime<br>";

// Convert start time and current time to UTC by applying the offset
$start_time = strtotime($start_datetime) - ($timezone_offset * 60);
$current_time = strtotime("now") - ($timezone_offset * 60);

// Calculate hours since drinking started
$hours_since_start = ($current_time - $start_time) / SECONDS_IN_AN_HOUR;

// Calculate BAC after elapsed time
$current_bac = max(0, $initial_bac - ($elimination_rate * $hours_since_start));

// Debugging output
echo "User Timezone Offset: $timezone_offset minutes<br>";
echo "Current Time (UTC): " . date("Y-m-d H:i:s", $current_time) . "<br>";
echo "Start Time (UTC): " . date("Y-m-d H:i:s", $start_time) . "<br>";
echo "Hours Since Start: " . $hours_since_start . "<br>";
echo "Total Alcohol Grams: $total_alcohol_grams<br>";
echo "Initial BAC: " . round($initial_bac, 2) . "<br>";
echo "Current BAC: " . round($current_bac, 2) . "<br>";

// Consider removing exit() in production
// exit();
?>
