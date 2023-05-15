<?php
    // Inicio de sesión
    session_start();
    // Iniciar carrito si no existe
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

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
// Verificar si se recibió el formulario de agregar producto
if (isset($_POST['agregar_producto'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Insertar el nuevo producto en la base de datos
    $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $nombre, $precio, $stock);
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
    <h2>Agregar nuevo producto</h2>

    <form action="agregar_producto.php" method="post">
        <div class="editar_producto">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="editar_producto">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" min="0" id="precio" name="precio" required>
        </div>

        <div class="editar_producto">
            <label for="stock">Stock:</label>
            <input type="number" min="0" id="stock" name="stock" required>
        </div>

        <div class="editar_producto">
            <button type="submit" name="agregar_producto" class="boton">Agregar</button>
            <a href="admin.php" class="boton">Cancelar</a>
        </div>
    </form>
</main>

<footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    </body>