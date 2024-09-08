<?php
include 'config.php';

// Query for the latest record
$sql = "SELECT t, h, time FROM test ORDER BY time DESC LIMIT 1";
$result = $conn->query($sql);

$temperature = "00.00";
$humidity = "00.00";
$timestamp = "No data";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $temperature = htmlspecialchars($row['t']);
    $humidity = htmlspecialchars($row['h']);
    $timestamp = htmlspecialchars($row['time']);
} else {
    echo "0 results";
}

// Query for the average temperature and humidity
$sql = "SELECT AVG(t) AS avg_temp, AVG(h) AS avg_humidity FROM test";
$avgResult = $conn->query($sql);
$avgTemperature = $avgHumidity = "No data";
if ($avgResult->num_rows > 0) {
    $row = $avgResult->fetch_assoc();
    $avgTemperature = number_format($row['avg_temp'], 2);
    $avgHumidity = number_format($row['avg_humidity'], 2);
}

// Function to get chart data for plotting
function getChartData($conn) {
    $sql = "SELECT time, t, h FROM test ORDER BY time DESC LIMIT 10";
    $result = $conn->query($sql);

    $data = ['timestamps' => [], 'temperatures' => [], 'humidities' => []];

    while ($row = $result->fetch_assoc()) {
        array_unshift($data['timestamps'], $row['time']);
        array_unshift($data['temperatures'], $row['t']);
        array_unshift($data['humidities'], $row['h']);
    }

    return $data;
}

$chartData = getChartData($conn);

// Close connection
$conn->close();

// Determine colors based on values
function getTemperatureColor($temp) {
    if ($temp < 20) {
        return '#1E90FF'; // Blue
    } elseif ($temp < 30) {
        return '#32CD32'; // LimeGreen
    } elseif ($temp < 40) {
        return '#FFA500'; // Orange
    } else {
        return '#FF4500'; // OrangeRed
    }
}

function getHumidityColor($humidity) {
    if ($humidity < 30) {
        return '#32CD32'; // LimeGreen
    } elseif ($humidity < 60) {
        return '#FFA500'; // Orange
    } else {
        return '#FF6347'; // Tomato
    }
}

$temperatureColor = getTemperatureColor($temperature);
$humidityColor = getHumidityColor($humidity);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="Vikkey" content="Vivek Gupta & IoTMonk">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <title>ESP32 Temperature &amp; Humidity Sensor From PHP API</title>
    <style>
        body {
            background: linear-gradient(to right, #2980b9, #6dd5fa, #ffffff);
            font-family: 'Helvetica', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .dashboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            position: relative;
            width: 100%;
            max-width: 1200px;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .stats-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: right;
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .data {
            font-size: 50px;
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .data img {
            margin-right: 10px;
        }

        .temperature {
            color: <?php echo $temperatureColor; ?>;
        }

        .humidity {
            color: <?php echo $humidityColor; ?>;
        }

        .timestamp, .stat {
            font-size: 16px;
            color: #666;
        }

        .stat img {
            vertical-align: middle;
            margin-right: 5px;
        }

        .poste {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
        }

        .poste img {
            width: 100px;
            height: auto;
        }

        .chart-container {
            width: 500px;
            height: 300px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-container .stat {
            text-align: right;
        }

        .stats-container .timestamp {
            display: block;
            font-size: 12px;
            color: #999;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <div class="chart-container">
            <canvas id="chart"></canvas>
        </div>
        <div class="container">
            <h1>Servers Temperature &amp; Humidity Tracking</h1>
            <div class="data temperature">
                <img src='temperature.png' height="85px" width="85px" /> <?php echo $temperature; ?>
            </div>
            <div class="data humidity">
                <img src='humidity.png' height="55px" width="55px" /> <?php echo $humidity; ?>
            </div>
            <div class="timestamp">
                <img src='timestamp.png' height="20px" width="20px" /> <?php echo $timestamp; ?>
            </div>
        </div>
        <div class="stats-container">
            <h2>Statistics</h2>
            <div class="stat">
                <img src='temperature.png' height="20px" width="20px" /><strong>Average Temperature:</strong> <?php echo $avgTemperature; ?>°C
            </div>
            <div class="stat">
                <img src='humidity.png' height="20px" width="20px" /><strong>Average Humidity:</strong> <?php echo $avgHumidity; ?>%
            </div>
        </div>
    </div>
    <div class="poste">
        <img src='poste.jpg' />
    </div>
    <script>
        var timestamps = <?php echo json_encode($chartData['timestamps']); ?>;
        var temperatures = <?php echo json_encode($chartData['temperatures']); ?>;
        var humidities = <?php echo json_encode($chartData['humidities']); ?>;

        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: temperatures,
                        fill: false,
                    },
                    {
                        label: 'Humidity (%)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: humidities,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + (context.dataset.label === 'Temperature (°C)' ? '°C' : '%');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
