<?php
// Inicio de sesión
session_start();

$nombre_usuario = "";

// Comprobar si hay una sesión de usuario iniciada
if (isset($_SESSION["usuario"])) {
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

    // Verificar si se recibió el parámetro 'id'
    if (!isset($_GET['id'])) {
        header('Location: admin.php'); // Redirigir a la página de administración si no se recibió el parámetro 'id'
        exit();
    }

    $id = $_GET['id'];

    // Obtener el usuario de la base de datos
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró el usuario en la base de datos
    if ($resultado->num_rows == 0) {
        header('Location: admin.php'); // Redirigir a la página de administración si no se encontró el usuario
        exit();
    }

    $usuario = $resultado->fetch_assoc();

    // Verificar si se recibió el formulario de edición
    if (isset($_POST['editar_usuario'])) {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

        // Actualizar el usuario en la base de datos
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ?, telefono = ?, direccion = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $apellidos, $correo, $telefono, $direccion, $id);
        $stmt->execute();

        header('Location: admin.php'); // Redirigir a la página de administración
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tee Lab</title>
    <link href= "https://fonts.googleapis.com/css2?family=Cinzel&family=Russo+One&family=Staatliches&display=swap" rel="stylesheet"">
    <link rel="stylesheet" href="css/style.css">
    <script src="carrito.js"></script>
</head>
<body>
    <header class="header">
        <a href="index.php">
            <img class="header__logo" src="img/logoconnombre.png" alt="Logotipo">
        </a>
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace" href="index.php">Tienda</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
        <a class="navegacion__enlace" href="contacto.php">Contacto</a>
        <a class="navegacion__enlace" href="admin.php">Panel Admin</a>
    </nav>

    <main class="contenedor">
        <h2>Editar usuario</h2>

        <form action="editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="post">
            <div class="editar_usuario">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            </div>

            <div class="editar_usuario">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo $usuario['apellidos']; ?>" required>
            </div>

            <div class="editar_usuario">
                <label for="email">Email:</label>
                <input type="email" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>
            </div>
            <div class="editar_usuario">
                <label for="email">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo $usuario['direccion']; ?>" required>
            </div>

            <div class="editar_usuario">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" value="<?php echo $usuario['password']; ?>" required>
            </div>

            <div class="editar_usuario">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" required>
            </div>

            <div class="editar_usuario">
                <button type="submit" name="editar_usuario" class="boton">Actualizar</button>
                <a href="admin.php" class="boton">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
</body>
</html>
