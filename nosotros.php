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


    </nav>

    <main class="contenedor">
        <h1>Nosotros</h1>

        <div class="nosotros">
            <div class="nosotros__contenido">
                <p>Donec purus mi, pretium ut turpis nec, imperdiet ultrices erat. In felis odio, iaculis in dui at, vehicula ullamcorper turpis. Fusce consectetur enim ante,
                     nec sodales dolor bibendum quis. Fusce dui massa, pharetra vel posuere at, fermentum eget ante. Nunc convallis efficitur finibus. Duis quis justo varius,
                      consectetur ipsum sit amet, cursus urna. Praesent non bibendum nulla. Nulla pulvinar ex odio, ac rutrum sem lobortis id. Curabitur lobortis, eros vitae 
                      consectetur auctor, odio augue ullamcorper arcu, in dignissim arcu est sit amet turpis. Nunc ac libero tempor, pellentesque tellus sed, faucibus velit. 
                      Proin tincidunt magna non ex tempor, vel blandit purus venenatis. Donec at dignissim dui, et pretium velit. </p>

                <p>Praesent efficitur id tortor ut porta. Aliquam tempus erat vitae massa eleifend fermentum. Sed metus justo, lobortis lacinia aliquet in, ornare a nisl. Maecenas 
                ac lectus sapien. Suspendisse et risus aliquet, vestibulum augue eget, rutrum nulla. Nullam ornare volutpat commodo. In mollis scelerisque consectetur. Donec porta 
                nisi sed faucibus volutpat.  .</p>
                

            </div>
            <img class=nosotros__imagen src="img/nosotros.jpg" alt="nosotros imagen">

        
        </div>
    </main>
    <section class="contenedor comprar">
        <h1 class="comprar__titulo">¿Por qué comprar con nosotros?</h1>

        <div class="bloques">
            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_1.png" alt="por qué comprar">
                <h3 class="bloque_titulo">El mejor precio</h3>
                <p>consectetur ipsum sit amet, cursus urna. Praesent non bibendum nulla. Nulla pulvinar ex odio, ac rutrum sem lobortis id. Curabitur lobortis, eros vitae 
                    consectetur auctor</p>
            </div> <!--bloque-->

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_2.png" alt="por qué comprar">
                <h3 class="bloque_titulo">Personalizable</h3>
                <p>consectetur ipsum sit amet, cursus urna. Praesent non bibendum nulla. Nulla pulvinar ex odio, ac rutrum sem lobortis id. Curabitur lobortis, eros vitae 
                    consectetur auctor</p>
            </div> <!--bloque-->

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_3.png" alt="por qué comprar">
                <h3 class="bloque_titulo">Envío gratis</h3>
                <p>consectetur ipsum sit amet, cursus urna. Praesent non bibendum nulla. Nulla pulvinar ex odio, ac rutrum sem lobortis id. Curabitur lobortis, eros vitae 
                    consectetur auctor</p>
            </div> <!--bloque-->

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_4.png" alt="por qué comprar">
                <h3 class="bloque_titulo">La mejor cálidad</h3>
                <p>consectetur ipsum sit amet, cursus urna. Praesent non bibendum nulla. Nulla pulvinar ex odio, ac rutrum sem lobortis id. Curabitur lobortis, eros vitae 
                    consectetur auctor</p>
            </div> <!--bloque-->

        </div> <!--bloques-->
    </section>

    <footer class="footer">
        <p class="footer__texto">Tee Lab - Todos los derechos Reservados 2023</p>
    </footer>

</body>
</html>