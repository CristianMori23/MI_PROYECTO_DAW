<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_tee_lab";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $mensaje = $_POST["mensaje"];

    // Insertar datos en la base de datos
    $sql = "INSERT INTO contactos (nombre, telefono, correo, mensaje) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $telefono, $correo, $mensaje);
    $stmt->execute();

    // Redirigir a una página de agradecimiento
    header("Location: gracias.html");
    exit();
}

$conn->close();

?>
