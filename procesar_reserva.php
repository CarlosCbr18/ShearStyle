<?php
session_start(); // Inicia la sesión

if (isset($_SESSION["ID"])) {
    $id = $_SESSION["ID"]; // Recupera el ID de la sesión
}
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$id_servicio = $_POST['servicio'];
// Conectar con la base de datos
$servername = "localhost";
$username = "pw";
$password = "pw";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$bd = mysqli_select_db($conn, 'peluqueria');
if(!$bd) {
    echo"Error al seleccionar la base de datos.";
}
$servicio = mysqli_query($conn,"SELECT Duracion FROM servicio WHERE ID = $id_servicio");

if (mysqli_num_rows($servicio) > 0) {
    $row = mysqli_fetch_assoc($servicio);
    $duracion = $row["Duracion"];
}

$hora_cita_fin = date('H:i:s', strtotime($hora . ' + ' . $duracion . ' minutes'));
echo $id.'-'.$fecha.'-'.$hora.'-'.$id_servicio.'-'.$duracion.'-'.$hora_cita_fin;

// Consulta para verificar si hay alguna cita que se superpone con la nueva cita
$sql = "SELECT *
        FROM cita
        WHERE id_pelu IN (
            SELECT id
            FROM peluquero
        )
        AND fecha = '$fecha_cita'
        AND (
            (hora >= '$hora_cita' AND hora < '$nueva_cita_fin')
            OR (hora < '$hora_cita' AND hora_fin > '$hora_cita')
        )";

$result = $conn->query($sql);

// Si no hay superposición de citas
if ($result->num_rows == 0) {
    // Asignar la cita al peluquero disponible
    // Aquí iría el código para insertar la cita en la base de datos
    // INSERT INTO cita (id_pelu, id_servicio, hora, fecha) VALUES ($id_peluquero, $id_servicio, '$hora_cita', '$fecha_cita')
    echo "La cita ha sido asignada correctamente.";
} else {
    // Hay superposición de citas, mostrar una alerta
    echo "Lo sentimos, ya hay una cita programada en esa hora.";
}
?>
?>