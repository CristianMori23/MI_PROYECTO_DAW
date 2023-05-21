<!DOCTYPE html>
<html lang="es">
<?php
// Inicio de sesión
session_start();

$nombre_usuario = "";

$_SESSION['carrito'] = json_decode($_POST['carrito']);

//Si se quiere subir una imagen
$imagen = isset($_FILES['imagen-input']);
if ($imagen) {
    //Recogemos el archivo enviado por el formulario
    $archivo = $_FILES['imagen-input']['name'];
    //Si el archivo contiene algo y es diferente de vacio
    if (isset($archivo) && $archivo != "") {
       //Obtenemos algunos datos necesarios sobre el archivo
       $tipo = $_FILES['imagen-input']['type'];
       $tamano = $_FILES['imagen-input']['size'];
       $temp = $_FILES['imagen-input']['tmp_name'];
       //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
    //   if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {         echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
    //      - Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>';
    //   }
    //   else {
         //Si la imagen es correcta en tamaño y tipo
         //Se intenta subir al servidor
         $_SESSION['imagen'] = '/images/'.floor(microtime(true) * 1000).'-'.$archivo;
         
         if (move_uploaded_file($temp, $_SESSION['imagen'])) {
             //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
             chmod($_SESSION['imagen'], 0777);
             //Mostramos el mensaje de que se ha subido co éxito
            //  echo '<div><b>SE ha subido correctamente la imagen.</b></div>';
             //Mostramos la imagen subida
            //  echo '<p><img src="images/'.$archivo.'"></p>';
         }
         else {
            //Si no se ha podido subir la imagen, mostramos un mensaje de error
            echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
         }
    //    }
    }
 }

// $_SESSION['imagen'] = $_POST['imagen-input'];

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

function calcularImporteTotal()
{

}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tee Lab - Pasarela de pago</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Russo+One&family=Staatliches&display=swap"
        rel="stylesheet">
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
        <?php if ($nombre_usuario != ""): ?>
            <!-- Si hay un usuario logueado, muestra el nombre y opción de cerrar sesión. -->
            <a class="navegacion__enlace" href="#">
                <img src="img/icono_6.png" alt="Usuario" style="height: 32px; width: auto;">
                Hola,
                <?php echo $nombre_usuario; ?>
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

    </nav>

    <section>
        <h1>Pago del pedido</h1>
        <form class="formulario-pago" action="procesarpedido.php" method="POST">
            <legend>Introduce los datos de tu tarjeta</legend>
            <label for="titular">Titular de la tarjeta:</label>
            <input type="text" id="titular" name="titular" required>

            <label for="numero">Número de tarjeta:</label>
            <div class="numero-tarjeta">
                <input type="text" id="numero1" name="numero1" maxlength="4" required>
                <input type="text" id="numero2" name="numero2" maxlength="4" required>
                <input type="text" id="numero3" name="numero3" maxlength="4" required>
                <input type="text" id="numero4" name="numero4" maxlength="4" required>
            </div>

            <label for="fecha-caducidad">Fecha de caducidad:</label>
            <div class="fecha-caducidad">
                <input type="text" id="mes-caducidad" name="mes-caducidad" placeholder="MM" maxlength="2" required>
                <input type="text" id="ano-caducidad" name="ano-caducidad" placeholder="YY" maxlength="2" required>
            </div>

            <label for="codigo">Código de seguridad:</label>
            <input type="text" id="codigo" name="codigo" maxlength="3" required>

            <input type="hidden" name="productos" value='<?php echo json_encode($_SESSION["carrito"]); ?>'>
            <input type="hidden" name="importe_total" value='<?php echo calcularImporteTotal(); ?>'>


            <button type="submit">Pagar</button>

        </form>

        </div>
        </div>

    </section>

    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>
    <script src="carrito.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", actualizarContadorCarrito);
        document.addEventListener("DOMContentLoaded", mostrarCarrito);
    </script>

</body>

</html>