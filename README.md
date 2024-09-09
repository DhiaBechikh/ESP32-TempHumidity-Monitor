
# IoT Temperature & Humidity Monitor

This project is an IoT-based temperature and humidity monitoring system using an ESP32 microcontroller and a DHT11 sensor. The system reads temperature and humidity data, sends it to a server via Wi-Fi, and triggers an SMS alert using Twilio when the values exceed predefined thresholds.

## Features

- **Real-time Monitoring:** Captures temperature and humidity data using a DHT11 sensor.
- **Data Logging:** Sends the data to a server for storage in a database.
- **Visual Dashboard:** Displays live data and statistics on a web dashboard.
- **Alerts:** Sends SMS alerts using Twilio when the temperature or humidity exceeds set thresholds.

## Getting Started

Follow these steps to set up and run the project on your local machine.

### Prerequisites

1. **Hardware:**
   - ESP32 microcontroller
   - DHT11 Temperature and Humidity Sensor
   - Jumper wires
   - Breadboard

2. **Software:**
   - Arduino IDE
   - Composer (PHP Dependency Manager)
   - Web Server (like XAMPP, WAMP, or a live server)
   - PHP 7.x or higher

3. **Accounts:**
   - Twilio account for SMS alerts

### Step-by-Step Setup

#### 1. Prepare the Hardware

- Connect the DHT11 sensor to the ESP32:
  - `VCC` to `3.3V` on ESP32
  - `GND` to `GND` on ESP32
  - `DATA` to GPIO 17 on ESP32

#### 2. Flash the ESP32

- Install the [Arduino IDE](https://www.arduino.cc/en/software).
- Install the ESP32 board library in Arduino IDE: Go to `File > Preferences`, and add this URL to `Additional Board Manager URLs`: `https://dl.espressif.com/dl/package_esp32_index.json`.
- Install the DHT sensor library in Arduino IDE: Go to `Sketch > Include Library > Manage Libraries`, search for `DHT sensor library`, and install it.
- Open the `ESP32_DHT11_Sensor.ino` file in Arduino IDE.
- Update the Wi-Fi credentials:
  ```cpp
  const char* ssid = "YOUR_SSID";
  const char* password = "YOUR_PASSWORD";
  ```
- Update the server URL:
  ```cpp
  const char* serverName = "http://YOUR_SERVER_IP/path/to/your/server.php";
  ```
- Upload the code to your ESP32 board.

#### 3. Set Up the Server

- Install a local web server like [XAMPP](https://www.apachefriends.org/index.html).
- Clone this repository to your `htdocs` (for XAMPP) or equivalent directory.
- Set up a MySQL database and create a table named `test` with columns `t` (temperature), `h` (humidity), and `time`.
- Edit the `config.php` file with your database credentials:
  ```php
  $servername = "localhost";
  $username = "YOUR_DB_USERNAME";
  $password = "YOUR_DB_PASSWORD";
  $dbname = "YOUR_DB_NAME";
  ```
- Install Composer and run:
  ```sh
  composer install
  ```
  This will install the necessary PHP dependencies.

#### 4. Configure Twilio for SMS Alerts

- Sign up at [Twilio](https://www.twilio.com/).
- Get your Twilio `Account SID`, `Auth Token`, and phone number.
- Update the Twilio credentials in the `server.php` file:
  ```php
  $sid = 'YOUR_TWILIO_SID';
  $token = 'YOUR_TWILIO_TOKEN';
  $twilio_number = 'YOUR_TWILIO_PHONE_NUMBER';
  ```

#### 5. Run the Web Dashboard

- Start your local web server (e.g., Apache with XAMPP).
- Access the dashboard at `http://localhost/path/to/your/index.php`.

### Usage

1. **Monitor Data:**
   - The dashboard will display the current temperature and humidity readings.
   - It will also show average temperature and humidity statistics.

2. **Receive Alerts:**
   - When the temperature or humidity exceeds the set thresholds, an SMS alert will be sent to the configured phone number.

### Folder Structure

- `ESP32/`: Contains the Arduino code for ESP32.
- `php/`: PHP scripts for server-side processing.
- `vendor/`: PHP dependencies installed by Composer (e.g., Twilio SDK, PHPMailer).
  
### Troubleshooting

- **ESP32 Not Connecting to Wi-Fi:** Double-check your SSID and password.
- **No Data on the Dashboard:** Ensure the ESP32 is connected to Wi-Fi and the server URL is correct.
- **SMS Alerts Not Working:** Verify your Twilio credentials and ensure your account is not in trial mode (or has sufficient credits).

## License

This project is licensed under the MIT License.

## Contributing

Feel free to submit issues or pull requests if you have improvements or suggestions.

