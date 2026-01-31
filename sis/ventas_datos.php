<?php 
//consulto los datos de la venta
$consulta_venta = $conexion->query("SELECT * FROM ventas_datos WHERE id = '$venta_id'");

if ($consulta_venta->num_rows == 0)
{
    
}
else
{
    while ($fila_venta = $consulta_venta->fetch_assoc())
    {
        $venta_id = $fila_venta['id'];
        $fecha = date('Y/m/d', strtotime($fila_venta['fecha']));
        $fecha_dia = date('d', strtotime($fila_venta['fecha']));
        $fecha_mes = date('m', strtotime($fila_venta['fecha']));
        $fecha_ano = date('y', strtotime($fila_venta['fecha']));
        $hora = date('H:i', strtotime($fila_venta['fecha']));
        $usuario_id = $fila_venta['usuario_id'];
        $ubicacion_id = $fila_venta['ubicacion_id'];
        $ubicacion = ucfirst($fila_venta['ubicacion']);
        $cliente_id = $fila_venta['cliente_id'];
        $tipo_pago = $fila_venta['tipo_pago'];        $tipo_pago_id = $fila_venta['tipo_pago_id'];
        
        // Consultar el tipo de pago para saber si es efectivo
        $tipo_pago_categoria = "";
        if (!empty($tipo_pago_id)) {
            $consulta_tipo_pago = $conexion->query("SELECT tipo FROM tipos_pagos WHERE id = '$tipo_pago_id'");
            if ($fila_tipo_pago = $consulta_tipo_pago->fetch_assoc()) {
                $tipo_pago_categoria = $fila_tipo_pago['tipo'];
            }
        }
                $total_bruto = $fila_venta['total_bruto'];
        $venta_descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $descuento_porcentaje = $fila_venta['descuento_porcentaje'];
        $descuento_valor = $fila_venta['descuento_valor'];
        $venta_propina = $fila_venta['propina'];
        $total_neto = $fila_venta['total_neto'];
        $estado = $fila_venta['estado'];
        $pago = $fila_venta['pago'];
        $observaciones = $fila_venta['observaciones'];
        $fecha_pago = date('Y/m/d', strtotime($fila_venta['fecha_pago']));
        $fecha_pago_dia = date('d', strtotime($fila_venta['fecha_pago']));
        $fecha_pago_mes = date('m', strtotime($fila_venta['fecha_pago']));
        $fecha_pago_ano = date('y', strtotime($fila_venta['fecha_pago']));

        if ($dinero == 0)
        {
            $dinero = $total_neto;
        }

        $cambio = (float)$dinero - (float)$total_neto;

        //consulto la ubicacion
        $consulta_ubicacion = $conexion->query("SELECT * FROM ubicaciones WHERE id = '$ubicacion_id'");

        if ($fila_ubicacion = $consulta_ubicacion->fetch_assoc())
        {
            $ubicacion_id = $fila_ubicacion['id'];
            $ubicacion_tipo = $fila_ubicacion['tipo'];
        }

        //Inicializar variables de cliente con valores por defecto
        $nombre = "";
        $telefono = "";
        $direccion = "";
        $documento = "";
        $documento_tipo = "";
        $correo_cliente = "";

        //consulto el cliente
        if ($ubicacion_tipo == "persona")
        {
            $consulta_cliente = $conexion->query("SELECT * FROM clientes WHERE id = '$cliente_id'");

            if ($fila_cliente = $consulta_cliente->fetch_assoc())
            {
                $cliente_id = $fila_cliente['id'];
                $nombre = ucwords($fila_cliente['nombre']);
                $telefono = $fila_cliente['telefono'];
                $direccion = ucwords($fila_cliente['direccion']);
                $documento_tipo = $fila_cliente['documento_tipo'];
                $documento = $fila_cliente['documento'];
                $correo_cliente = $fila_cliente['correo'];

                if (empty($nombre))
                {
                    $nombre = "";
                }
                else
                {
                    $nombre = "$nombre<br>";
                }

                if (empty($telefono))
                {
                    $telefono = "";
                }
                else
                {
                    $telefono = "Tel. $telefono";
                }

                if (empty($direccion))
                {
                    $direccion = "";
                }
                else
                {
                    $direccion = "$direccion<br>";
                }

                if (empty($documento))
                {
                    $documento = "";
                }
                else
                {
                    $documento = "$documento_tipo $documento<br>";
                }

                $ubicacion_texto = "<b>Cliente</b><br>
                                    $nombre
                                    $documento
                                    $direccion
                                    $telefono";
            }
            else
            {
                $ubicacion_texto = "<b>Ubicación</b> $ubicacion";
            }
        }
        else
        {
            $ubicacion_texto = "<b>Ubicación</b> $ubicacion";
        }

        //consulto el usuario que realizo la ultima modificacion
        $consulta_usuario = $conexion->query("SELECT * FROM usuarios WHERE id = '$usuario_id'");           

        if ($fila = $consulta_usuario->fetch_assoc()) 
        {
            $nombres = ucwords($fila['nombres']);
            $apellidos = ucwords($fila['apellidos']);

            $atendido_texto = "<b>Atendido por</b> $nombres $apellidos";
            $atendido_texto = "";
        }
    }
}
?>