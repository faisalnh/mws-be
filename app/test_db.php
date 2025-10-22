<?php
$host = "containers-us-west-12.railway.app";
$user = "root";
$pass = "xyeThIoxakGbibOvfHFMrhfUMbDCKlbO";
$db   = "railway";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully!";
