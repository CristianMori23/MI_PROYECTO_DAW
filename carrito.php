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
    
        $conn->close();
    }
    $total_carrito = 0; // inicializa la variable
    foreach ($_SESSION["carrito"] as $producto) {
      $total_carrito += $producto["precio"] * $producto["cantidad"];
    }
    ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tee Lab</title>
    <link href= "https://fonts.googleapis.com/css2?family=Cinzel&family=Russo+One&family=Staatliches&display=swap" rel="stylesheet"">
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
        <a class="navegacion__enlace" href="#" id="carrito">
            <img src="img/carrito.svg" alt="Carrito" style="height: 30px; width: auto;">
            <span id="carrito-contador">(0)</span>
        </a>
        <div class="carrito-dropdown" id="carrito-dropdown">
            <!-- El contenido del carrito se agregará aquí -->
        </div>
    </div>
    </nav>
    <main class="contenedor">
        <h1>Tu carrito de compras</h1>
        <table id="tabla-carrito" class="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <td></td>
                </tr>
            </thead>
            <tbody id="carrito-productos">
                <!-- Aquí se agregarán las filas con los productos del carrito -->
            </tbody>
        </table>
        <div id="adjuntar-imagen">
            <h2>Adjunta la imagen para tu camiseta:</h2>
            <input type="file" id="imagen-input">Adjuntar</input>
            
            <div id="imagen-preview"></div>
        </div>
        <div class="carrito-total">
            <p>Total: <span id="carrito-total">0€</span></p>
        </div>
        <?php if ($nombre_usuario == ""): ?>
         <p>Debes iniciar sesión para poder pagar.</p>
        <a class="navegacion__enlace" href="login.html">Iniciar sesión</button>
        <?php else: ?>
        <form method="POST" action="pasareladepago.php">
        <input type="hidden" name="carrito" value='<?php echo json_encode($_SESSION["carrito"]); ?>' />
        <button type="submit" id="pagar-btn">Pagar pedido</button>
</div>
         </form>
<?php endif; ?>
    </main>
    
    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    
    <script src="carrito.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", actualizarContadorCarrito);
    document.addEventListener("DOMContentLoaded", mostrarCarrito);

        const imagenInput = document.getElementById('imagen-input');
        const adjuntarBtn = document.getElementById('adjuntar-btn');
        const imagenPreview = document.getElementById('imagen-preview');

        adjuntarBtn.addEventListener('click', adjuntarImagen);

        function adjuntarImagen() {
            const imagen = imagenInput.files[0];

            if (imagen) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imagenURL = e.target.result;

                    imagenPreview.innerHTML = `<img src="${imagenURL}" alt="Vista previa de la imagen">`;
                };

                reader.readAs
                reader.readAsDataURL(imagen);
            }
        }
    </script>
</body>
</html>