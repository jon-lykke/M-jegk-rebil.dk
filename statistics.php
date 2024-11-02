<?php
include('db_connection.php'); // Assuming you have a separate DB connection file

// Fetch visitor stats data from your database
$query = "SELECT * FROM visitor_statistics ORDER BY visit_date DESC LIMIT 10";
$result = $conn->query($query);

// Query for average alcohol consumption by gender
$male_avg_query = "SELECT AVG(alcohol_consumed) AS avg_male_units FROM maajegkoerebil_visitor_log_data WHERE gender = 'male'";
$female_avg_query = "SELECT AVG(alcohol_consumed) AS avg_female_units FROM maajegkoerebil_visitor_log_data WHERE gender = 'female'";

$male_result = $conn->query($male_avg_query);
$female_result = $conn->query($female_avg_query);


$avg_male_units = $male_result->fetch_assoc()['avg_male_units'];
$avg_female_units = $female_result->fetch_assoc()['avg_female_units'];

$male_result->free();
$female_result->free();
$conn->close();
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistikker - Må jeg køre bil?</title>
    <link rel="stylesheet" href="mycss.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Statistikker</h1>
    </header>

    <main>
        <table>
            <tr>
                <th>Dato</th>
                <th>Browser</th>
                <th>Enhed</th>
                <th>Referrer</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['visit_date']; ?></td>
                    <td><?php echo $row['browser']; ?></td>
                    <td><?php echo $row['device']; ?></td>
                    <td><?php echo $row['referrer']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</div>

<footer>
    <p>© 2024 MåJegKøreBil.dk</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
