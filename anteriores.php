<?php
    require './includes/config/database.php';

    //ver errores mientra desarrollo
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    
    $db = conectarBD();
    // var_dump($db);

    // Configurar la zona horaria Guatemala
    date_default_timezone_set('America/Guatemala');
    $fecha = date('d/m/Y');


    //consulta ultimos tres oficios/registros usados
    // $consultaUltimosRegistros = "SELECT * FROM registros ORDER BY id DESC LIMIT 3";
    $consultaUltimosRegistros = "SELECT r.id, o.numero_oficio, u.nombre, u.apellido, r.fecha, r.descripcion
                                    FROM registros r
                                    JOIN usuarios u ON r.id_usuario = u.id
                                    JOIN oficios o ON r.id_oficio = o.id
                                    ORDER BY r.id DESC
                                    -- LIMIT 3
                                    ";
    $filasUltimosRegistros =  mysqli_query($db, $consultaUltimosRegistros);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./build/css/app.css">
    <title>Oficios</title>
</head>
<body>

    <header class="header">
        <div class="contenedor">
            <div class="contenido-header">
                <picture>
                    <source srcset="build/img/logo.avif" type="image/avif">
                    <img loading="lazy" src="build/img/logo.png" alt="logo municipal">
                </picture>
                <h1>Historial Oficios</h1>
                <picture>
                    <source srcset="build/img/logoDMJED.avif" type="image/avif">
                    <img loading="lazy" src="build/img/logoDMJED.png" alt="logo Juventud y Deporte">
                </picture>

            </div>
        </div>
    </header>

    <div class="fecha alineacion">

        <p>
            <?php echo $fecha  ?>
        </p>

        
        <a href="index.php" class="boton reciente">Regresar</a>

    </div>

    <main class="contenedor">
        <!-- <h2>Historial Oficios</h2> -->

        <table class="tabla" >
            <thead>
                <tr class="fila">
                    <th>Oficio</th>
                    <th>Colaborador</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
    
                <?php while($row = mysqli_fetch_assoc($filasUltimosRegistros)): ?>
    
                    <tr class="fila">
                        <td> <?php echo $row['numero_oficio'] ?> </td>
                        <td> <?php echo $row['nombre'] . " " . $row['apellido'] ?> </td>
                        <td> <?php echo $row['fecha'] ?> </td>
                        <td> <?php echo $row['descripcion'] ?> </td>
                        
                    </tr>

                <?php endwhile; ?>

            </tbody>
        </table>

    </main>



    <footer class="footer no-fijo">
        <p>Todos los derechos reservados <?php echo date('Y'); ?></p>

    </footer>

<script src="build/js/app.js"></script>

</body>
</html>