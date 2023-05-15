<!DOCTYPE html>
<html lang="es">
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
     
    
        $conn->close();
    }
    ?>

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
            <div class="navegacion__carrito">
        <a class="navegacion__enlace" href="carrito.php" id="carrito">
            <img src="img/carrito.svg" alt="Carrito" style="height: 30px; width: auto;">
            <span id="carrito-contador">(0)</span>
        </a>
        <div class="carrito-dropdown" id="carrito-dropdown">
            <!-- El contenido del carrito se agregará aquí -->
        </div>
    </div>
    </nav>
 
    <main class="contenedor">
        <h1>Escoge tu camiseta y personalizalá!</h1>

            <div class="grid">
            <?php $camisetas = [ //Inicio el array asociativo
            1 => ['nombre' => 'Roly Atomic', 'precio' => '20€'],
            2 => ['nombre' => 'Sol Regent', 'precio' => '20€'],
            3 => ['nombre' => 'Fruit Value', 'precio' => '20€'],
            4 => ['nombre' => 'Gildan Ring', 'precio' => '20€'],
            5 => ['nombre' => 'Sol Epic', 'precio' => '20€'],
            6 => ['nombre' => 'Roly Braco', 'precio' => '20€'],
            7 => ['nombre' => 'Roly Samoyedo', 'precio' => '20€'],
            8 => ['nombre' => 'Roly Jamaica', 'precio' => '20€'],
            9 => ['nombre' => 'Sol Odyssey', 'precio' => '20€'],
            10 => ['nombre' => 'Sol Monarch', 'precio' => '20€'],
            11 => ['nombre' => 'Fruit Value ML', 'precio' => '20€'],
            12 => ['nombre' => 'Roly Pointer', 'precio' => '20€'],
            13 => ['nombre' => 'Fruit Atleta', 'precio' => '20€'],
            14 => ['nombre' => 'Roly Extreme ML', 'precio' => '20€'],
        ]; ?>

        <?php foreach ($camisetas as $id => $info): ?>
    <div class="producto">
        <a>
        <img class="producto__imagen" src="img/<?php echo $id; ?>.jpg" alt="imagen camiseta"
             onmouseover="zoomIn(event)" onmouseout="zoomOut(event)">
        <div class="producto__informacion">
            <p class="producto__nombre"><?php echo $info['nombre']; ?></p>
            <p class="producto__precio"><?php echo $info['precio']; ?></p>
    </div>
    <div class="talla">
            <label for="talla-<?php echo $id; ?>" class="label-talla">Talla:</label>
            <select id="talla-<?php echo $id; ?>" class="seleccionar-talla">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
        </div>
        </a>
        <button class="boton-agregar" onclick="agregarAlCarrito('<?php echo $id; ?>', '<?php echo $info['nombre']; ?>', document.getElementById('talla-<?php echo $id; ?>').value, '<?php echo $info['precio']; ?>')">Agregar al carrito</button>
    </div><!--producto-->
        <?php endforeach; ?>
           
            <div class="grafico grafico1"></div>
            <div class="grafico grafico2"></div>
        </div>
    </main>
    <script>
    // función para hacer zoom a las imágenes
    function zoomIn(event) {
        var element = event.currentTarget;
        var imgWidth = element.clientWidth;
        element.style.transform = "scale(1.2)";
        element.style.transition = "transform 0.3s";
    }

    function zoomOut(event) {
        var element = event.currentTarget;
        element.style.transform = "scale(1)";
        element.style.transition = "transform 0.3s";
    }
    // llamar a la funcion actualizarContadorCarrito
    document.addEventListener("DOMContentLoaded", actualizarContadorCarrito);
    document.addEventListener("DOMContentLoaded", mostrarCarrito);
    </script>
    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    

</body>
</html>