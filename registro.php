<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_tee_lab";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $password = $_POST["password"];

    // Verificar si el usuario ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        $_SESSION['error'] = "El usuario introducido ya está registrado";
        header("Location: errorregistro.html");
        exit();
    } else {
        // Crear el usuario en la base de datos
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, correo, telefono, direccion, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $apellidos, $correo, $telefono, $direccion, $hash);
        $stmt->execute();

        header("Location: gracias.html");
        exit();
    }

    $conn->close();
}
?>
