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
if(isset($_POST['id'])) $id = $_POST['id']; elseif(isset($_GET['id'])) $id = $_GET['id']; else $id = null;
?>

<?php
//consulto los permisos del usuario
$consulta_permisos = $conexion->query("SELECT * FROM usuarios_permisos WHERE id_usuario = '$id'");

if ($fila_permisos = $consulta_permisos->fetch_assoc()) 
{
    $id_permisos = $fila_permisos['id'];
    $fecha = date('d M', strtotime($fila_permisos['fecha']));
    $hora = date('h:i a', strtotime($fila_permisos['fecha']));
    $id_usuario = $fila_permisos['id_usuario'];
    $ajustes = $fila_permisos['ajustes'];
    $ventas = $fila_permisos['ventas'];
    $zonas_entregas = $fila_permisos['zonas_entregas'];
    $base = $fila_permisos['base'];
    $cierre = $fila_permisos['cierre'];
    $compras = $fila_permisos['compras'];
    $producciones = $fila_permisos['producciones'];
    $inventario = $fila_permisos['inventario'];
    $gastos = $fila_permisos['gastos'];
    $clientes = $fila_permisos['clientes'];
    $reportes = $fila_permisos['reportes'];    
}
else
{
    header("location:usuarios_ver.php");
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
<body>

<header class="rdm-toolbar--contenedor">
    <div class="rdm-toolbar--fila">
        <div class="rdm-toolbar--izquierda">
            <a href="usuarios_detalle.php?id=<?php echo "$id"; ?>"><div class="rdm-toolbar--icono"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i></div></a>
            <h2 class="rdm-toolbar--titulo">Permisos del usuario</h2>
        </div>
        <div class="rdm-toolbar--derecha">
            <a href="usuarios_eliminar.php?id=<?php echo "$id"; ?>&nombres=<?php echo "$nombres"; ?>&apellidos=<?php echo "$apellidos"; ?>"><div class="rdm-lista--icono"><i class="zmdi zmdi-delete zmdi-hc-2x"></i></div></a>
        </div>
    </div>
</header>

<main class="rdm--contenedor-toolbar">

    <section class="rdm-formulario">

        <form action="usuarios_detalle.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="image" />
            <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
            
            <p class="rdm-formularios--label"><label for="ajustes">Ajustes</label></p>
            <p><select id="ajustes" name="ajustes" required>
                <option value="<?php echo "$ajustes"; ?>"><?php echo safe_ucfirst($ajustes) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Acceso a los ajustes</p>

            <p class="rdm-formularios--label"><label for="ajustes">Ventas</label></p>
            <p><select id="ventas" name="ventas" required>
                <option value="<?php echo "$ventas"; ?>"><?php echo safe_ucfirst($ventas) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Hacer o continuar una venta</p>

            <p class="rdm-formularios--label"><label for="ajustes">Zonas de entrega</label></p>
            <p><select id="zonas_entregas" name="zonas_entregas" required>
                <option value="<?php echo "$zonas_entregas"; ?>"><?php echo safe_ucfirst($zonas_entregas) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Mostrar zonas de entrega</p>

            <p class="rdm-formularios--label"><label for="ajustes">Base</label></p>
            <p><select id="base" name="base" required>
                <option value="<?php echo "$base"; ?>"><?php echo safe_ucfirst($base) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Ingresar la base de la jornada</p>

            <p class="rdm-formularios--label"><label for="ajustes">Cierre</label></p>
            <p><select id="cierre" name="cierre" required>
                <option value="<?php echo "$cierre"; ?>"><?php echo safe_ucfirst($cierre) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Ingresar el cierre de la jornada</p>

            <p class="rdm-formularios--label"><label for="ajustes">Compras</label></p>
            <p><select id="compras" name="compras" required>
                <option value="<?php echo "$compras"; ?>"><?php echo safe_ucfirst($compras) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Hacer o continuar una compra</p>

            <p class="rdm-formularios--label"><label for="ajustes">Producciones</label></p>
            <p><select id="producciones" name="producciones" required>
                <option value="<?php echo "$producciones"; ?>"><?php echo safe_ucfirst($producciones) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Hacer o continuar una producción</p>

            <p class="rdm-formularios--label"><label for="ajustes">Inventarios</label></p>
            <p><select id="inventario" name="inventario" required>
                <option value="<?php echo "$inventario"; ?>"><?php echo safe_ucfirst($inventario) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Ver inventario y recibir despachos</p>

            <p class="rdm-formularios--label"><label for="ajustes">Gastos</label></p>
            <p><select id="gastos" name="gastos" required>
                <option value="<?php echo "$gastos"; ?>"><?php echo safe_ucfirst($gastos) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Agregar y consultar gastos</p>

            <p class="rdm-formularios--label"><label for="ajustes">Clientes</label></p>
            <p><select id="clientes" name="clientes" required>
                <option value="<?php echo "$clientes"; ?>"><?php echo safe_ucfirst($clientes) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Agregar y consultar clientes</p>

            <p class="rdm-formularios--label"><label for="ajustes">Reportes</label></p>
            <p><select id="reportes" name="reportes" required>
                <option value="<?php echo "$reportes"; ?>"><?php echo safe_ucfirst($reportes) ?></option>
                <option value="no">No</option>
                <option value="si">Si</option>
            </select></p>
            <p class="rdm-formularios--ayuda">Consultar los datos de mi negocio</p>            
            
            <button type="submit" class="rdm-boton--fab" name="editar_permisos" value="si"><i class="zmdi zmdi-check zmdi-hc-2x"></i></button>
        </form>

    </section>

<footer></footer>

</body>
</html>