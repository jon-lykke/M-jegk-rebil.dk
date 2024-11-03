<?php
// Ensure these variables are defined before use
$avg_male_units = $avg_male_units ?? 0;
$avg_female_units = $avg_female_units ?? 0;

// Capture form data from POST request
$gender = $_POST['gender'] ?? 'unknown';
$weight = $_POST['weight'] ?? 'unknown';
$local_start_datetime = $_POST['local_start_datetime'] ?? 'unknown';
$timezone_offset = $_POST['timezone_offset'] ?? 'unknown';
$formatted_start_date = $_POST['formatted_start_date'] ?? 'unknown';
$formatted_timezone = $_POST['formatted_timezone'] ?? 'unknown';
$formatted_start_time = $_POST['formatted_start_time'] ?? 'unknown';
$alcohol_consumed = $_POST['alcohol_consumed'] ?? 0;

// Log the gender, weight, local start datetime, timezone offset, formatted start date, formatted timezone, and formatted start time
error_log("Gender: " . $gender);
error_log("Weight: " . $weight);
error_log("Local Start Datetime: " . $local_start_datetime);
error_log("Timezone Offset: " . $timezone_offset);
error_log("Formatted Start Date: " . $formatted_start_date);
error_log("Formatted Timezone: " . $formatted_timezone);
error_log("Formatted Start Time: " . $formatted_start_time);
error_log("Alcohol Consumed: " . $alcohol_consumed);

// Calculate the time difference in hours
$start_datetime = new DateTime($local_start_datetime);
$current_datetime = new DateTime();
$current_datetime->setTimezone(new DateTimeZone('UTC'));
$interval = $current_datetime->diff($start_datetime);
$hours_passed = $interval->days * 24 + $interval->h + $interval->i / 60 + $interval->s / 3600;
$hours_passed = round($hours_passed, 1);

// Log the time difference
error_log("Time Passed: " . $hours_passed . " hours");

// Calculate the BAC using the provided formula
$widmark_factor = ($gender === 'male') ? 0.68 : 0.55;
$metabolism_rate = 0.15; // permille per hour
$body_weight_grams = $weight * 1000;
$alcohol_grams = $alcohol_consumed * 12; // 1 unit = 12 grams of alcohol

$bac = max(0, ($alcohol_grams / ($body_weight_grams * $widmark_factor)) * 1000 - ($metabolism_rate * $hours_passed));
$bac = round($bac, 2);

// Log the BAC with two decimal places
error_log("Current BAC: " . number_format($bac, 2) . " permille");

// Redirect back to index.php with a message
header("Location: index.php?result=Gender, weight, start time, and BAC logged successfully");
exit();

// Lines 120 and 121
echo "Average male units: " . $avg_male_units;
echo "Average female units: " . $avg_female_units;
?>