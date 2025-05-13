<?php
$hostname = "localhost";
$username = "Fcstore";
$password = "Jesusram1";
$database = "fcstore";
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn ->connect_error) {
die('Error de Conexión (' . $conn->connect_errno . ') ' . $conn->connect_error);
}
?>