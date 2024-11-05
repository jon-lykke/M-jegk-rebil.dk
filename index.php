<?php
include('db_connection.php');

// Initialize variables to avoid undefined variable warnings
$avg_male_units = $avg_male_units ?? 0;
$avg_female_units = $avg_female_units ?? 0;

// Check for result in GET parameters
$result = '';
if (isset($_GET['result']) && !empty($_GET['result'])) {
    $result = nl2br(htmlspecialchars($_GET['result']));
}
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
                        <option value="<?php echo htmlspecialchars($i); ?>"><?php echo htmlspecialchars(str_pad($i, 2, '0', STR_PAD_LEFT)); ?></option>
                    <?php endfor; ?>
                </select>

                <!-- Minute Dial -->
                <select id="start_minute" name="start_minute" required>
                    <?php for ($i = 0; $i < 60; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Hidden fields for local start datetime and timezone offset -->
            <input type="hidden" id="local_start_datetime" name="local_start_datetime">
            <input type="hidden" id="timezone_offset" name="timezone_offset">
            <input type="hidden" id="formatted_start_date" name="formatted_start_date">
            <input type="hidden" id="formatted_timezone" name="formatted_timezone">
            <input type="hidden" id="formatted_start_time" name="formatted_start_time">

            <!-- Calculate Button -->
            <button type="submit">Beregn</button>
        </form>

        <script>
            document.querySelector('form').addEventListener('submit', function() {
                const startDay = document.getElementById('start_day').value;
                const startHour = document.getElementById('start_hour').value;
                const startMinute = document.getElementById('start_minute').value;

                let startDate = new Date();
                if (startDay === 'yesterday') {
                    startDate.setDate(startDate.getDate() - 1);
                }
                startDate.setHours(startHour, startMinute, 0, 0);

                const formattedDate = startDate.toLocaleDateString('da-DK', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                const formattedTime = startDate.toLocaleTimeString('da-DK', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                const timezoneOffset = startDate.getTimezoneOffset();
                const timezoneOffsetHours = timezoneOffset / 60;
                const formattedTimezone = `GMT ${timezoneOffsetHours > 0 ? '-' : '+'}${Math.abs(timezoneOffsetHours)}`;

                document.getElementById('local_start_datetime').value = startDate.toISOString();
                document.getElementById('timezone_offset').value = timezoneOffset;
                document.getElementById('formatted_start_date').value = formattedDate;
                document.getElementById('formatted_timezone').value = formattedTimezone;
                document.getElementById('formatted_start_time').value = formattedTime;
            });
        </script>

        <!-- Result Field -->
        <?php if (!empty($result)): ?>
            <div id="result">
                <h2>Resultat</h2>
                <p><?php echo $result; ?></p>
            </div>
        <?php endif; ?>
        
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
        
        <!-- Display Average Alcohol Consumption Information -->
        <div>        
            <h2>Gennemsnitligt antal genstande per bruger af siden</h2>
            <p>Gennemsnitligt antal genstande (mænd): <?php echo htmlspecialchars(round($avg_male_units, 2)); ?></p>
            <p>Gennemsnitligt antal genstande (kvinder): <?php echo htmlspecialchars(round($avg_female_units, 2)); ?></p>
        </div>
    </main>
</div>

<footer>
    <p>© 2024 Måjegkørebil.dk</p>
</footer>

</body>
</html>
