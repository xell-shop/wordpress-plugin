<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_upload_image($image_url, $attach_to_post = 0, $add_to_media = true )  {
    // Obtener el contenido remoto usando wp_remote_get
    $response = wp_remote_get( $image_url );

    // Verificar si hubo un error en la solicitud
    if ( is_wp_error( $response ) ) {
        return false;
    }

    // Obtener el código de respuesta HTTP
    $response_code = wp_remote_retrieve_response_code( $response );

    // Verificar si la solicitud fue exitosa (código 200)
    if ( 200 !== $response_code ) {
        return false;
    }

    // Obtener el contenido del cuerpo de la respuesta
    $remote_image = wp_remote_retrieve_body( $response );

    // Verificar si el contenido está vacío
    if ( empty( $remote_image ) ) {
        return false;
    }

    // Obtener el tipo de contenido de la imagen
    $headers = wp_remote_retrieve_headers( $response );
    $image_meta = false;
    $image_filetype = false;

    if ( ! empty( $headers['content-type'] ) && preg_match('/^image\/(jpe?g|png|gif|bmp)$/i', $headers['content-type'], $matches ) ) {
        $image_meta = $headers['content-type'];
        $image_filetype = $matches[1];
    }

    // Si no es una imagen válida, salir
    if ( !$image_meta ) return false;

    // Generar el nombre del archivo
    $v = basename($image_url);
    if ( $v && strlen($v) > 6 ) {
        // Crear un nombre de archivo a partir de la URL
        $path = $v;
    } else {
        $url_parsed = wp_parse_url( $image_url );
        $path = isset($url_parsed['path']) ? $url_parsed['path'] : $image_url;
    }

    $path = preg_replace('/(https?:|\/|www\.|\.[a-zA-Z]{2,4}$)/i', '', $path );
    $filename_no_ext = sanitize_title_with_dashes( $path, '', 'save' );

    $extension = $image_filetype;
    $filename = $filename_no_ext . "." . $extension;

    // Crear un archivo temporal para simular la carga
    $tmp = tmpfile();
    $tmp_path = stream_get_meta_data( $tmp )['uri'];
    fwrite( $tmp, $remote_image );
    fseek( $tmp, 0 ); // Si no hacemos esto, WordPress piensa que el archivo está vacío

    // Crear un array simulado para $_FILES
    $fake_FILE = array(
        'name'     => $filename,
        'type'     => 'image/' . $extension,
        'tmp_name' => $tmp_path,
        'error'    => UPLOAD_ERR_OK,
        'size'     => strlen( $remote_image ),
    );

    // Añadir el archivo simulado a $_FILES
    $_FILES[basename( $tmp_path )] = $fake_FILE;

    // Incluir archivos necesarios para manejar la subida
    include_once ABSPATH . 'wp-admin/includes/media.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/image.php';

    // Manejar la subida del archivo
    $result = wp_handle_upload( $fake_FILE, array(
        'test_form' => false,
        'action'    => 'local',
    ));

    // Cerrar y eliminar el archivo temporal
    // fclose( $tmp );
    wp_delete_file( $tmp_path );
    unset( $_FILES[basename( $tmp_path )] );

    // Procesar el resultado de la subida
    $result['attachment_id'] = 0;

    if ( empty( $result['error'] ) && $add_to_media ) {
        $args = array(
            'post_title'     => $filename_no_ext,
            'post_content'   => '',
            'post_status'    => 'publish',
            'post_mime_type' => $result['type'],
        );

        $result['attachment_id'] = wp_insert_attachment( $args, $result['file'], $attach_to_post );

        $attach_data = wp_generate_attachment_metadata( $result['attachment_id'], $result['file'] );
        wp_update_attachment_metadata( $result['attachment_id'], $attach_data );

        if ( is_wp_error( $result['attachment_id'] ) ) {
            $result['attachment_id'] = 0;
        }
    }

    return $result;
}
