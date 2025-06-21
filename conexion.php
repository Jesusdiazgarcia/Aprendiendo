<?php

function get_env_var($key, $default = null) {
    if ($value = getenv($key)) {
        return $value;
    }
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    return $default;
}

// Check for any Railway-specific variable to detect the environment
if (get_env_var('RAILWAY_PUBLIC_DOMAIN')) {
    // Railway environment
    $hostname = get_env_var('MYSQL_HOST', 'localhost');
    $username = get_env_var('MYSQL_USER', 'root');
    $password = get_env_var('MYSQL_PASSWORD', '');
    $database = get_env_var('MYSQL_DATABASE', 'fcstore');
    $port = get_env_var('MYSQL_PORT', '3306');
} else {
    // Local environment
    $hostname = "localhost";
    $username = "Fcstore";
    $password = "Jesusram1";
    $database = "fcstore";
    $port = "3306";
}

$conn = new mysqli($hostname, $username, $password, $database, (int)$port);

if ($conn->connect_error) {
    // Do not expose credentials in the error message
    die('Error de Conexión (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8");
?>