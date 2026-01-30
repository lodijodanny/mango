<?php
//nombre de la sesion, inicio de la sesión y conexion con la base de datos
include ("sis/nombre_sesion.php");

//verifico si la sesión está creada y si no lo está lo envio al logueo
if (!isset($_SESSION['correo']))
{
    header("location:logueo.php");
}
?>

<?php
//variables de la sesion
include ("sis/variables_sesion.php");
?>

<?php
//capturo las variables que pasan por URL o formulario
if(isset($_POST['inventario_id'])) $inventario_id = $_POST['inventario_id']; elseif(isset($_GET['inventario_id'])) $inventario_id = $_GET['inventario_id']; else $inventario_id = null;

if(isset($_POST['cantidad_actual'])) $cantidad_actual = $_POST['cantidad_actual']; elseif(isset($_GET['cantidad_actual'])) $cantidad_actual = $_GET['cantidad_actual']; else $cantidad_actual = null;
if(isset($_POST['cantidad_modificada'])) $cantidad_modificada = $_POST['cantidad_modificada']; elseif(isset($_GET['cantidad_modificada'])) $cantidad_modificada = $_GET['cantidad_modificada']; else $cantidad_modificada = null;
if(isset($_POST['motivo'])) $motivo = $_POST['motivo']; elseif(isset($_GET['motivo'])) $motivo = $_GET['motivo']; else $motivo = null;
if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; elseif(isset($_GET['descripcion'])) $descripcion = $_GET['descripcion']; else $descripcion = null;

if(isset($_POST['descontar'])) $descontar = $_POST['descontar']; elseif(isset($_GET['descontar'])) $descontar = $_GET['descontar']; else $descontar = null;

if(isset($_POST['mensaje'])) $mensaje = $_POST['mensaje']; elseif(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje']; else $mensaje = null;
if(isset($_POST['body_snack'])) $body_snack = $_POST['body_snack']; elseif(isset($_GET['body_snack'])) $body_snack = $_GET['body_snack']; else $body_snack = null;
if(isset($_POST['mensaje_tema'])) $mensaje_tema = $_POST['mensaje_tema']; elseif(isset($_GET['mensaje_tema'])) $mensaje_tema = $_GET['mensaje_tema']; else $mensaje_tema = null;
?>

<?php
//genero la novedad y descuento la cantidad
if ($descontar == "si")
{
    $operacion = "resta";

    $nueva_cantidad = $cantidad_actual - $cantidad_modificada;

    $actualizar_inventario = $conexion->query("UPDATE inventario SET cantidad = '$nueva_cantidad' WHERE id = '$inventario_id'");    

    $insertar_novedad = $conexion->query("INSERT INTO inventario_novedades values ('', '$ahora', '$sesion_id', '$inventario_id', '$cantidad_actual', '$operacion', '$cantidad_modificada', '$nueva_cantidad', '$motivo', '$descripcion')");

    if ($insertar_novedad)
    {
        $mensaje = "Novedad creada";
        $body_snack = 'onLoad="Snackbar()"';
        $mensaje_tema = "aviso";
    }

    
}
?>

<?php
//consulto los datos del inventario
$consulta_inventario = $conexion->query("SELECT * FROM inventario WHERE id = '$inventario_id'");

if ($consulta_inventario->num_rows != 0)
{
    while ($fila_inventario = $consulta_inventario->fetch_assoc())
    {
        $inventario_id = $fila_inventario['id'];
        $componente_id = $fila_inventario['componente_id'];
        $cantidad = $fila_inventario['cantidad'];
        $minimo = $fila_inventario['minimo'];
        $maximo = $fila_inventario['maximo'];

        //si la cantidad es cero o negativa
        if ($cantidad <= 0)
        {
            $cantidad = 0;
        }

        //consulto el componente
        $consulta2 = $conexion->query("SELECT * FROM componentes WHERE id = $componente_id");

        if ($filas2 = $consulta2->fetch_assoc())
        {
            $componente = $filas2['componente'];
            $unidad = $filas2['unidad'];
        }
        else
        {
            $componente = "No se ha asignado un componente";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>ManGo!</title>    
    <?php
    //información del head
    include ("partes/head.php");
    //fin información del head
    ?>
</head>

<body <?php echo $body_snack; ?>>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="inventario_novedades.php?inventario_id=<?php echo "$inventario_id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Agregar novedad</h2>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <h2 class="rdm-lista--titulo-largo">Cantidad actual</h2>

    <section class="rdm-lista">

        <article class="rdm-lista--item-sencillo">
            <div class="rdm-lista--izquierda-sencillo">
                <div class="rdm-lista--contenedor">
                    <div class="rdm-lista--icono"><i class="zmdi zmdi-widgets zmdi-hc-2x"></i></div>
                </div>
                <div class="rdm-lista--contenedor">
                    <h2 class="rdm-lista--titulo"><?php echo ucfirst($componente); ?></h2>
                    <h2 class="rdm-lista--texto-secundario"><?php echo number_format($cantidad, 0, ",", "."); ?> <?php echo ucfirst($unidad); ?></h2>
                </div>
            </div>
        </article>

    </section>

    <h2 class="rdm-lista--titulo-largo">Cantidad a descontar</h2>

    <section class="rdm-formulario">

        <form action="inventario_novedades.php" method="post">
            <input type="hidden" name="inventario_id" value="<?php echo "$inventario_id"; ?>" />
            <input type="hidden" name="cantidad_actual" value="<?php echo "$cantidad"; ?>" />

            <p class="rdm-formularios--label"><label for="cantidad_modificada">Cantidad*</label></p>
            <p><input type="number" id="cantidad_modificada" name="cantidad_modificada" value="<?php echo "$cantidad_modificada"; ?>" spellcheck="false" required autofocus /></p>
            <p class="rdm-formularios--ayuda">Cantidad a descontar</p>

            <p class="rdm-formularios--label"><label for="motivo">Motivo*</label></p>
            <p><select id="motivo" name="motivo" required>
                <option value="<?php echo "$motivo"; ?>"><?php echo ucfirst($motivo) ?></option>
                <option value=""></option>
                <option value="ajuste">Ajuste</option>
                <option value="deterioro">Deterioro</option>
                <option value="hurto">Hurto</option>
                <option value="Fecha de vencimiento">Fecha de vencimiento</option>
            </select></p>
            <p class="rdm-formularios--ayuda">¿Por qué se hará el descuento?</p>

            <p class="rdm-formularios--label"><label for="descripcion">Descripción*</label></p>
            <p><textarea id="descripcion" name="descripcion" required><?php echo "$descripcion"; ?></textarea></p>
            <p class="rdm-formularios--ayuda">Escribe una descripción </p>
            
            <button type="submit" class="rdm-boton--fab" name="descontar" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>


    
</main>

<div id="rdm-snackbar--contenedor">
    <div class="rdm-snackbar--fila">
        <div class="rdm-snackbar--primario-<?php echo $mensaje_tema; ?>">
            <h2 class="rdm-snackbar--titulo"><?php echo "$mensaje"; ?></h2>
        </div>
    </div>
</div>

<footer></footer>

<script>
    function updateTextInput(val) {
          document.getElementById('cantidad_modificada').value=val; 
        }
</script>

</body>
</html>