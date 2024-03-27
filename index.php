<?php
    require './includes/config/database.php';


    $db = conectarBD();

    // Configurar la zona horaria Guatemala
    date_default_timezone_set('America/Guatemala');
    $fechaInsert = date('Y/m/d');
    $fecha = date('d/m/Y');


    //consulta usuairos registrados
    $consultaUsuarios = 'SELECT * FROM usuarios';
    $filasUsuarios = mysqli_query($db, $consultaUsuarios);

    //consulta numero oficio
    $consultaOficios = "SELECT * FROM oficios WHERE estado = 'disponible' ORDER BY id ASC LIMIT 1";
    $filasOficios = mysqli_query($db, $consultaOficios);

    $oficio = mysqli_fetch_assoc($filasOficios);

    //consulta ultimos tres oficios/registros usados
    $consultaUltimosRegistros = "SELECT r.id, o.numero_oficio, u.nombre, u.apellido, r.fecha
                                    FROM registros r
                                    JOIN usuarios u ON r.id_usuario = u.id
                                    JOIN oficios o ON r.id_oficio = o.id
                                    ORDER BY r.id DESC
                                    LIMIT 3
                                    ";
    $filasUltimosRegistros =  mysqli_query($db, $consultaUltimosRegistros);

    // Arreglo con mensajes de errores
    $errores = [];
    $id_usuario = '';
    $descripcion = '';
    $id_oficio = '';

    // Ejecutar el codigo despues de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        
        //variable = $_POST['name de input']
        $id_usuario = $_POST['usuario'];
        $descripcion = $_POST['descripcion'];
        
        // Validamos los campos
        if(!$id_usuario){
            $errores[] = "Selecciona tu nombre";
        }
        
        

        if(empty($errores)){
            // Insertar en base de datos
            // importante iniciar comillas dobles en el query

            $id_oficio = $oficio['id'];

            $query = " INSERT INTO registros (id_oficio, id_usuario, fecha, descripcion)
                            VALUES ('$id_oficio', '$id_usuario', '$fechaInsert', '$descripcion')";

            // echo $query;
            $updateOficio = "UPDATE oficios SET estado = 'usado' WHERE id = $id_oficio";

            $resultado = mysqli_query($db, $query);
            $update = mysqli_query($db, $updateOficio);

            if($resultado && $update){
                header("Location: index.php");
                exit;
            }

        }

    }

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
    <div class="contenedor-general">
        <header class="header">
            <div class="contenedor">
                <div class="contenido-header">
                    <picture>
                        <source srcset="build/img/logo.avif" type="image/avif">
                        <img loading="lazy" src="build/img/logo.png" alt="logo municipal">
                    </picture>
                    <h1>Oficios</h1>
                    <picture>
                        <source srcset="build/img/logoDMJED.avif" type="image/avif">
                        <img loading="lazy" src="build/img/logoDMJED.png" alt="logo Juventud y Deporte">
                    </picture>

                </div>
            </div>
        </header>

        <div class="fecha">

            <p>
                <?php echo $fecha  ?>
            </p>

            <!-- <p>20/04/2024</p> -->
        </div>

        <?php foreach($errores as $error):  ?>

            <div class="alerta error">
                <?php echo $error; ?>
                
            </div>

        <?php endforeach; ?>

        <main class="contenedor">
            <div class="grid">

                <form class="form" method="POST" action="index.php">
                    <div class="contenedor-correlativo">
                        <div class="correlativo">
                            
                            <p>
                                <?php   

                                    echo $oficio['numero_oficio'];
                                    
                                ?>
                            </p>

                              
                        </div>

                        <!-- <a class="boton usar" href="#">Usar</a> -->
                        <input type="submit" value="Usar" class="boton usar">
                        

                    </div>

                    <div class="datos-usuario">
                        <h2>Registro</h2>
                            
                        <select name="usuario" class="select" >
                            <option value="" class="centrar">--Seleccione--</option>

                            <?php while ($row = mysqli_fetch_assoc($filasUsuarios)) :  ?>
                                <option value="<?php echo $row['id']; ?>">
                            
                                    <?php echo $row['nombre'] . " " . $row ['apellido'] ; ?>
                        
                                </option>
                    
                            <?php endwhile; ?>

                        </select>

                        <textarea id="descripcion" name="descripcion" class="textarea" placeholder="Descripción, opcional"></textarea>

                    </div>


                </form>

                <div class="contenedor-datos-recientes">
                    <h2>Recientes</h2>
                    <div class="datos-recientes">
                            
                        <?php while ($row = mysqli_fetch_assoc($filasUltimosRegistros)) : ?>
                            <p >
                                <?php echo $row['numero_oficio'] . " - " . $row ['nombre'] . " - " . $row['fecha'] ; ?>
                            </p>
                            

                        <?php endwhile; ?>
                    
                    </div>

                    <a href="anteriores.php" class="boton">Ver anteriores</a>
    
                </div>

            </div>
        </main>

    </div>   

    <footer class="footer">
        <p>Todos los derechos reservados <?php echo date('Y'); ?></p>
    </footer>
    
    <script src="build/js/app.js"></script>
</body>
</html>