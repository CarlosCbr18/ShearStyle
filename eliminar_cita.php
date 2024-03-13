<?php
$servername = "localhost";
$username = "pw";
$password = "pw";

// Crear conexión
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$bd = mysqli_select_db($conn, 'peluqueria');
if(!$bd) {
    echo"Error al seleccionar la base de datos.";
}

$id_cita = $_GET['id_cita'];
$eliminar = "DELETE FROM cita WHERE ID = $id_cita";
$result = mysqli_query($conn, $eliminar);
if ($result) {
    header('Location: index_peluquero.php');
} else {
    echo "Error al eliminar la cita.";
}
?>