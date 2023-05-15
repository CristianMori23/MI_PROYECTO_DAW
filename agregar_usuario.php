<?php
    // Inicio de sesión
    session_start();

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

        // Verificar si se recibió el formulario de agregar usuario
        if (isset($_POST['agregar_usuario'])) {
            // Obtener los datos del formulario
             // Obtener los datos del formulario
             $nombre = $_POST['nombre'];
             $apellidos = $_POST['apellido'];
             $correo = $_POST['correo'];
             $telefono = $_POST['telefono'];
             $direccion = $_POST['direccion'];
             $password = $_POST['contrasena'];
             $es_admin = $_POST['rol'] == 'admin' ? 1 : 0;
        // Verificar si el correo electrónico ya existe en la base de datos
            $sql = "SELECT * FROM usuarios WHERE correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
             echo "Ya existe un usuario con ese correo electrónico.";
          } else {
             // Insertar el nuevo usuario en la base de datos
             $sql = "INSERT INTO usuarios (nombre, apellidos, correo, telefono, direccion, password, es_admin) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nombre, $apellidos, $correo, $telefono, $direccion, $password, $es_admin);
             $stmt->execute();

          header('Location: admin.php'); // Redirigir a la página de administración
         exit();
          }
        }
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tee Lab</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Russo+One&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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
        <h2>Agregar nuevo usuario</h2>
        <form action="agregar_usuario.php" method="post">
        <div class="editar_usuario">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="editar_usuario">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>

        <div class="editar_usuario">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="editar_usuario">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>

        <div class="editar_usuario">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">
        </div>

        <div class="editar_usuario">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion">
        </div>

        <div class="editar_usuario">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <option value="cliente">Cliente</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

            <div class="formulario__campo">
                <button type="submit" name="agregar_usuario" class="boton">Agregar Usuario</button>
                <a href="admin.php" class="boton boton-secundario">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
</body>
</html>