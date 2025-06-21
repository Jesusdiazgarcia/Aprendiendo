<?php
// Configuración para Railway (producción) o local
if (getenv('RAILWAY_ENVIRONMENT')) {
    // Variables de entorno de Railway
    $hostname = getenv('MYSQL_HOST') ?: 'localhost';
    $username = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: '';
    $database = getenv('MYSQL_DATABASE') ?: 'fcstore';
    $port = getenv('MYSQL_PORT') ?: '3306';
} else {
    // Configuración local
    $hostname = "localhost";
    $username = "Fcstore";
    $password = "Jesusram1";
    $database = "fcstore";
    $port = "3306";
}

$conn = new mysqli($hostname, $username, $password, $database, $port);

if ($conn->connect_error) {
    die('Error de Conexión (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8");
?>