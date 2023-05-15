<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado y si su correo electrónico es igual a admin@teelab.com
if (isset($_SESSION["usuario"])){
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_tee_lab";
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Verificar la conexión a la base de datos
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del usuario a eliminar
    $id = $_GET["id"];

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // Redirigir al usuario a la página de administración
    header("Location: admin.php");
    exit();
} else {
    // Si el usuario no está autenticado o no es el administrador, mostrar un mensaje de error y redirigir al usuario a la página de inicio de sesión
    echo "No tienes permiso para realizar esta acción.";
    header("refresh:5;url=login.html");
    exit();
}
?>
