<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require '../util/conexion_tienda.php' ?>

    <title>Document</title>
</head>

<body>

    <!-- Verificamos si la solicitud es de tipo post, esto indica que se envio el formulario con los datos usuario y contraseña -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];           /* Obtenemos los valores */
        $contrasena = $_POST["contrasena"];


        /* Se hace una consulta para obtener todos los registros de la tabla usuario y lo almacenamos*/
        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = $conexion->query($sql);

        /* Aqui estoy comprobando que si la consulta no devuelve filas significa que el usuario no existe y entonces salta un error*/
        if ($resultado->num_rows === 0) { ?>
            <div class="alert alert-danger" role="alert">
                <script>
                    alert("USUARIO NO EXISTE");
                </script>
            </div>

    <?php
            /* Si el usuario existe cogemos la contraseña cifrada de la base de datos y la comparamos con la contraseña introducida*/
        } else {
            while ($fila = $resultado->fetch_assoc()) {
                $contrasena_cifrada = $fila["contrasena"];
                $rol_temporal = $fila["rol"];
            }
            $acceso_valido = password_verify($contrasena, $contrasena_cifrada);
            /* Si el acceso es valido iniciamos sesion y si no nos saltara una alerta*/
            if ($acceso_valido) {
                echo "NOS HEMOS LOGEADO CON EXITO";
                session_start();
                $_SESSION["usuario"] = $usuario;
                $_SESSION["rol"] = $rol_temporal;
                header('location: pagPrincipal.php');
            } else {
                echo '<script>alert("LA CONTRASEÑA ESTA MAL");</script>';
            }
        }
    }
    ?>

    <!-- Esto es un formulario para introducir usuario y contraseña -->
    <div class="container">
        <h1>Inicia Sesion</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <input class="btn btn-primary" type="submit" value="Iniciar">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>