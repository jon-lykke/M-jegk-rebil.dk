<?php
include('db_connection.php');

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
    <title>Må jeg køre bil?</title>
    <link rel="stylesheet" href="mycss.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Dagen derpå - Må jeg køre bil nu?</h1>
    </header>

    <main>
		<form action="calculate.php" method="POST">
        
			<!-- Gender Dropdown -->    
			<div class="form-group">			
				<label for="gender">Køn:</label>
					<select id="gender" name="gender">
						<option value="male">Mand</option>
						<option value="female">Kvinde</option>
					</select>
			</div>

			<!-- Weight Input -->
			<div class="form-group">			
					<label for="weight">Vægt i kilo:</label>
					<input type="text" id="weight" name="weight" required pattern="^\d+([.,]\d+)?$" placeholder="fx 65,7 eller 81,0">
			</div>
			
			<!-- Alcohol Consumption Input -->
			<div class="form-group">			
					<label for="alcohol_consumed">Hvor mange genstande har du drukket, siden du begyndte at drikke?</label>
					<input type="number" id="alcohol_consumed" name="alcohol_consumed" required min="0" step="1" placeholder="fx 5">
					<br>
			</div>

			<div class="form-group">
				<label for="start_day">Hvornår startede du med at drikke? (Vælg dag):</label>
				<select id="start_day" name="start_day" required>
					<option value="yesterday">I går</option>
					<option value="today">I dag</option>
				</select>
			</div>

			<div class="form-group">
				<label for="start_hour">Vælg tidspunkt (24-timers format):</label>
				
				<!-- Hour Dial -->
				<select id="start_hour" name="start_hour" required>
					<?php for ($i = 0; $i < 24; $i++): ?>
						<option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<?php endfor; ?>
				</select>
				
				<!-- Minute Dial -->
				<select id="start_minute" name="start_minute" required>
					<option value="0">00</option>
					<option value="15">15</option>
					<option value="30">30</option>
					<option value="45">45</option>
				</select>
			</div>

			<!-- Calculate Button -->
			<form action="calculate.php" method="POST">
				<!-- Existing form fields -->
				<input type="hidden" id="timezone_offset" name="timezone_offset">
				<button type="submit">Beregn</button>
			</form>

			<script>
				// Correctly set the user's timezone offset in minutes
				document.getElementById('timezone_offset').value = new Date().getTimezoneOffset();
			</script>
		
        </form>
        
        <!-- Result Field -->
		<?php if (isset($_GET['result'])): ?>
			<div id="result">
				<h2>Resultat</h2>
				<p><?php echo htmlspecialchars($_GET['result']); ?></p>
			</div>
			
			<!-- Information about metabolism rate -->
			<p><em>Viden om forbrænding: I gennemsnit forbrænder et menneske 0,15 promille i timen, men det kan variere en hel del, afhængig af køn og vægt (de primære faktorer).</em></p>

			<!-- Information about standard drinks -->
			<div style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
				<p><strong>1 genstand = 12 gram alkohol</strong></p>
				<ul>
					<li>1 øl (33 cl.) på 4,6 % = 1 genstand</li>
					<li>4 cl. snaps på 40% = 1 genstand</li>
					<li>2 dl. glas rødvin på 14% = 1,8 genstand</li>
					<li>2 dl. glas hvidvin på 12% = 1,6 genstande</li>
				</ul>
			</div>
		<?php endif; ?>
		
				<!-- Display Average Alcohol Consumption Information -->
		<div>        
			<h2>Gennemsnitligt antal genstande per bruger af siden</h2>
            <p>Gennemsnitligt antal genstande (mænd): <?php echo round($avg_male_units, 2); ?></p>
            <p>Gennemsnitligt antal genstande (kvinder): <?php echo round($avg_female_units, 2); ?></p>
        </div>
    </main>
</div>

<footer>
    <p>© 2024 Måjegkørebil.dk</p>
</footer>

</body>
</html>
