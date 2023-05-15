<!DOCTYPE html>
<html lang="es">
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

    // Obtener el nombre del usuario a partir de su id
    $sql = "SELECT nombre FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["usuario"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $nombre_usuario = $usuario["nombre"];
    }
// Obtener los productos de la base de datos
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);

// Guardar los productos en un arreglo
$productos = array();
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}
// Obtener los usuarios de la base de datos
$sql = "SELECT * FROM usuarios";
$resultado = $conn->query($sql);

// Guardar los usuarios en un arreglo
$usuarios = array();
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $usuarios[] = $row;
    }
}
// Obtener los pedidos de la base de datos
$sql = "SELECT * FROM pedidos";
$resultado = $conn->query($sql);

// Guardar los pedidos en un arreglo
$pedidos = array();
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $pedidos[] = $row;
    }
}
// Obtener los contactos de la base de datos
$sql = "SELECT * FROM contactos";
$resultado = $conn->query($sql);

// Guardar los contactos en un arreglo
$contactos = array();
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $contactos[] = $row;
    }
}
}

    $conn->close();


?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tee Lab</title>
    <link href= "https://fonts.googleapis.com/css2?family=Cinzel&family=Russo+One&family=Staatliches&display=swap" rel="stylesheet"">
    <link rel="stylesheet" href="css/style.css">
    <script src="carrito.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        <?php if ($nombre_usuario != ""): ?> <!-- Si hay un usuario logueado, muestra el nombre y opción de cerrar sesión. -->
            <a class="navegacion__enlace" href="#">
            <img src="img/icono_6.png" alt="Usuario" style="height: 32px; width: auto;">
             Hola, <?php echo $nombre_usuario; ?>
            </a>
            <a class="navegacion__enlace" href="logout.php" title="Cerrar sesión">
            <img src="img/icono_5.png" alt="Cerrar sesión" style="height: 24px; width: auto;">
            </a>
            <?php else: ?>
            <a class="navegacion__enlace" href="login.html" title="Iniciar sesión">
            <img src="img/icono_6.png" alt="Iniciar sesión" style="height: 24px; width: auto;">
            <span>Iniciar sesion</span>
            </a>

            <?php endif; ?>
          
        <a class="navegacion__enlace" href="admin.php">Panel Admin</a>
  
    </nav>
    <body>
 
    <main class="contenedor">
        <h2>Panel de Administración</h2>

        <h3>Productos</h3>
        <button class="boton" onclick="location.href='agregar_producto.php'">Agregar nuevo producto</button>

        <table class="tablaAdmin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['stock']; ?></td>
                    <td>
                    <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" data-id="<?php echo $producto['id']; ?>">Editar</a>
                    <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" data-id="<?php echo $producto['id']; ?>" class="eliminar-producto">Eliminar</a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Usuarios</h3>
        <button class="boton" onclick="location.href='agregar_usuario.php'">Agregar nuevo usuario</button>


<table class="tablaAdmin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo electrónico</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo $usuario['id']; ?></td>
            <td><?php echo $usuario['nombre']; ?></td>
            <td><?php echo $usuario['apellidos']; ?></td>
            <td><?php echo $usuario['correo']; ?></td>
            <td><?php echo $usuario['telefono']; ?></td>
            <td><?php echo $usuario['direccion']; ?></td>
            <td>
                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>">Editar</a>
                <a href="eliminar_usuario.php?id=<?php echo $usuario['id']; ?>" data-id="<?php echo $usuario['id']; ?>" class="eliminar-usuario">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Pedidos</h3>
<table class="tablaAdmin">
    <thead>
        <tr>
            <th>Pedido Nº</th>
            <th>Fecha</th>
            <th>Cliente Nº</th>
            <th>Usuario</th>
            <th>Productos</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?php echo $pedido['id_pedido']; ?></td>
            <td><?php echo $pedido['fecha_pedido']; ?></td>
            <td><?php echo $pedido['id_cliente']; ?></td>
            <td><?php echo $pedido['correo_cliente']; ?></td>
            <td><?php echo $pedido['id_producto']; ?></td>
            <td><?php echo $pedido['importe_total']; ?></td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Contactos</h3>

<table class="tablaAdmin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Mensaje</th>
            <th>Acciones</th>
        
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contactos as $contacto): ?>
        <tr>
            <td><?php echo $contacto['id']; ?></td>
            <td><?php echo $contacto['nombre']; ?></td>
            <td><?php echo $contacto['telefono']; ?></td>
            <td><?php echo $contacto['correo']; ?></td>
            <td><?php echo $contacto['mensaje']; ?></td>
            <td>
            <a href="eliminar_contacto.php?id=<?php echo $contacto['id']; ?>" data-id="<?php echo $contacto['id']; ?>" class="eliminar-contacto">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    </main>

    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    <script>
    // Obtener todos los botones de eliminar y agregar un evento click
    document.querySelectorAll('.eliminar-producto').forEach(function(boton) {
        boton.addEventListener('click', function(evento) {
            evento.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            var id = boton.getAttribute('data-id'); // Obtener el id del producto
            var confirmacion = confirm('¿Estás seguro de que quieres eliminar este producto?'); // Mostrar cuadro de diálogo de confirmación
            if (confirmacion) {
                window.location.href = 'eliminar_producto.php?id=' + id; // Redirigir a la página de eliminación
            }
        });
    });
    document.querySelectorAll('.eliminar-usuario').forEach(function(boton) {
        boton.addEventListener('click', function(evento) {
            evento.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            var id = boton.getAttribute('data-id'); // Obtener el id del usuario
            var confirmacion = confirm('¿Estás seguro de que quieres eliminar este usuario?'); // Mostrar cuadro de diálogo de confirmación
            if (confirmacion) {
                window.location.href = 'eliminar_usuario.php?id=' + id; // Redirigir a la página de eliminación
            }
        });
    });
    document.querySelectorAll('.eliminar-contacto').forEach(function(boton) {
        boton.addEventListener('click', function(evento) {
            evento.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            var id = boton.getAttribute('data-id'); // Obtener el id del contacto
            var confirmacion = confirm('¿Estás seguro de que quieres eliminar este mensaje?'); // Mostrar cuadro de diálogo de confirmación
            if (confirmacion) {
                window.location.href = 'eliminar_contacto.php?id=' + id; // Redirigir a la página de eliminación
            }
        });
    });

    // Obtener todos los botones de editar y agregar un evento click
    document.querySelectorAll('.editar-producto').forEach(function(boton) {
        boton.addEventListener('click', function(evento) {
            evento.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
            var id = boton.getAttribute('data-id'); // Obtener el id del producto
            window.location.href = 'editar_producto.php?id=' + id; // Redirigir a la página de edición
        });
    });
</script>

</body>