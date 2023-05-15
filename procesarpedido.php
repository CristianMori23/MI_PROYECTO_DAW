<?php
// Inicia la sesión
session_start();
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
// Inicializar variables para almacenar información del usuario
$nombre_usuario = "";
$correo_cliente = "";
$id_cliente = 0;
// Verificar si hay una sesion de usuario iniciada
if (isset($_SESSION["usuario"])) {
    // Consulta SQL para obtener nombre y correo del usuario a partir de su ID
    $sql = "SELECT nombre, correo FROM usuarios WHERE id = ?";
    // Preparar la consulta SQL
    $stmt = $conn->prepare($sql);
    //Vincular los parámetros de consulta SQL con las variables PHP
    $stmt->bind_param("i", $_SESSION["usuario"]);
    // Ejecutar la consulta SQL
    $stmt->execute();
    // Obtener los resultados de la consulta SQL
    $result = $stmt->get_result();
    // Si se encuentra el usuario,guardar sus datos en las variables correspondientes
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $nombre_usuario = $usuario["nombre"];
        $correo_cliente = $usuario["correo"];
        $id_cliente = $_SESSION["usuario"];
    }
}
// Consultar el importe total del pedido en la tabla "pedidos"
$importe_total = 0;
foreach ($_SESSION["carrito"] as $producto) {
    $importe_total += $producto["precio"] * $producto["cantidad"];
}

// Consulta SQL para insertar el pedido en la tabla
$sql = "INSERT INTO pedidos (fecha_pedido, id_cliente, correo_cliente,id_producto,talla_producto, imagen_producto, importe_total) VALUES (NOW(), ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("issdsd", $id_cliente, $correo_cliente, $id_producto, $talla_producto, $contenidoImagenEscapado, $importe_total);
// Ejecutar la consulta SQL
if ($stmt->execute()) {
    // Si el pedido se insertó correctamente, obtener los detalles del pedido en la tabla detalles_pedido
    $id_pedido = $conn->insert_id;

    foreach ($_SESSION["carrito"] as $producto) {
        $id_producto = $producto["id"];
        $cantidad = $producto["cantidad"];
        $subtotal = $producto["subtotal"];

        $sql_detalle = "INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)";

        $stmt_detalle = $conn->prepare($sql_detalle);
        $stmt_detalle->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $subtotal);

        if (!$stmt_detalle->execute()) {
            echo "Error al insertar detalle del pedido: " . $stmt_detalle->error;
            $stmt_detalle->close();
            $conn->close();
            exit();
        }

        $stmt_detalle->close();
    }
    // Eliminar los productos del carrito
    unset($_SESSION["carrito"]);

    header("Location: pedidorealizado.php");
} else {
    echo "Error al insertar el pedido: " . $stmt->error;
}
$conn->close();
?>
