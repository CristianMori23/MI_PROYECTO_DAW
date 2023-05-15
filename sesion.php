<?php
session_start();

$nombre_usuario = "";

if (isset($_SESSION["usuario"])) {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_tee_lab";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el nombre del usuario a partir de su id
    $sql = "SELECT nombre FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["usuario"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $nombre_usuario = $usuario["nombre"];
        $_SESSION["nombre_usuario"] = $nombre_usuario; // Almacenar en la sesión
    }

    $conn->close();
}
?>
