<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION["usuario"])) {
    header("Location: index.html");
    exit();
}
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

    $correo = $_POST["correo"];
    $password = $_POST["password"]; 

    // Verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        $error = "El usuario no existe";
        header("Location: errorusuario.html");
        exit();
    } else {
        // Verificar si la contraseña es correcta
        if (password_verify($password, $usuario["password"])) { 
            $_SESSION["usuario"] = $usuario["id"];
            if ($usuario["es_admin"] == 1 ) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta";
            header("Location: errorpassword.html");
            exit();
        }
    }

    $conn->close();
}
?>
