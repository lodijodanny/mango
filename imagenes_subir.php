<?php
//si han cargado una imagen procedemos a subirla
if ((isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '')) == 'image')
{
    //subimos la imagen

    //creamos una estancia de objeto Upload a partir del campo archivo del formulario
    $handle = new Upload($_FILES['archivo']);

    //luego verificamos si el archivo fue subido apropiadamente en su carpeta temporal en el servidor
    if ($handle->uploaded)
    {
        //si, el archivo subio al servidor, se configuran algunas opciones para el formato de la imagen        
        $handle->allowed = array('image/jpeg','image/jpg','image/gif','image/png','image/bmp');
        $handle->file_max_size = '5242880';
        $handle->image_resize = true;
        $handle->image_ratio_y = true;
        $handle->image_x = 600;
        $handle->jpeg_size = 30000;
        $handle->image_convert = 'jpg';
        $handle->file_overwrite = true;
        $handle->file_auto_rename = true;
        $handle->file_new_name_body = $imagen_ref.'-'.$id.'-'.$ahora_img;

        //se copia de la carpeta temporal en el servidor a la carpeta destino
        $handle->Process($carpeta_destino);

        //verificamos que todo esté bien
        if ($handle->processed)
        {
            
        }
        else
        {
            //ocurrió un error al subir
            echo '<p class="result">';
            echo '  <b>La imagen no fue subida a la carpeta destino</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }

        //subo la imagen mini     
        $handle->allowed = array('image/jpeg','image/jpg','image/gif','image/png','image/bmp');
        $handle->file_max_size = '5242880';
        $handle->image_resize = true;
        $handle->image_ratio_y = true;
        $handle->image_x = 150;
        $handle->image_convert = 'jpg';
        $handle->file_overwrite = true;
        $handle->file_auto_rename = true;
        $handle->file_new_name_body = $imagen_ref.'-'.$id.'-'.$ahora_img.'-m';

        //se copia de la carpeta temporal en el servidor a la carpeta destino
        $handle->Process($carpeta_destino);

        //verificamos que todo esté bien
        if ($handle->processed)
        {
            
        }
        else
        {
            //ocurrió un error al subir
            echo '<p class="result">';
            echo '  <b>La imagen no fue subida a la carpeta destino</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }

        //borramos los archivos temporales
        $handle-> Clean();
    }
    else
    {
    //si estamos aca es por que la imagen no fue subida por alguna razón, el servidor no la recibió
    
    }
}
?>