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

// Obtener el producto de la base de datos
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se encontró el producto en la base de datos
if ($resultado->num_rows == 0) {
    header('Location: admin.php'); // Redirigir a la página de administración si no se encontró el producto
    exit();
}

$producto = $resultado->fetch_assoc();

// Verificar si se recibió el formulario de edición
if (isset($_POST['editar_producto'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Actualizar el producto en la base de datos
    $sql = "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddi", $nombre, $precio, $stock, $id);
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
    <h2>Editar producto</h2>

    <form action="editar_producto.php?id=<?php echo $producto['id']; ?>" method="post">
        <div class="editar_producto">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
        </div>

        <div class="editar_producto">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" min="0" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
        </div>

        <div class="editar_producto">
            <label for="stock">Stock:</label>
            <input type="number" min="0" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>
        </div>

        <div class="editar_producto">
            <button type="submit" name="editar_producto" class="boton">Actualizar</button>
            <a href="admin.php" class="boton">Cancelar</a>
        </div>
    </form>
</main>

<footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    </body>