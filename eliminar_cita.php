<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

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
$id_cliente = $_GET['id_cliente'];

$cita = mysqli_query($conn, "SELECT * FROM cita WHERE ID = $id_cita");
$datos_cita = mysqli_fetch_assoc($cita);
$fecha_cita = date('d-m-Y', strtotime($datos_cita['Fecha'])); //obtenemos los datos de fecha y hora
$hora_cita = $datos_cita['hora'];

$eliminar = "DELETE FROM cita WHERE ID = $id_cita";
$result = mysqli_query($conn, $eliminar);
if ($result) {
    try {
        $cliente = mysqli_query($conn,"SELECT email,nombre FROM cliente WHERE ID = $id_cliente");
        $datos_cliente = mysqli_fetch_assoc($cliente);
        // Configurar el servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shearstylesalonpw@outlook.com'; // Tu dirección de correo Gmail
        $mail->Password = 'contrasena123'; // Tu contraseña de correo Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        // Configurar remitente y destinatario
        $mail->setFrom('shearstylesalonpw@outlook.com', 'ShearStyle Salon');
        $mail->addAddress($datos_cliente['email'], $datos_cliente['nombre']);
    
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Cancelacion de cita en ShearStyle Salon';
        $mail->Body    = 'Se ha cancelado su cita del '.$fecha_cita.' a las '.$hora_cita.', sentimos las molestias.';
    
        // Enviar el correo
        $mail->send();
        echo 'El correo se ha enviado correctamente.';
    } catch (Exception $e) {
        echo 'Hubo un error al enviar el correo: ', $mail->ErrorInfo;
    }
    header('Location: index_peluquero.php');
} else {
    echo "Error al eliminar la cita.";
}
?>