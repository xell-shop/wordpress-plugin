<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_upload_image($image_url, $attach_to_post = 0, $add_to_media = true )  {
    // Verificar si la URL de la imagen es válida
    if ( ! filter_var( $image_url, FILTER_VALIDATE_URL ) ) {
        return false;
    }

    // Incluir los archivos necesarios para manejar la subida
    include_once ABSPATH . 'wp-admin/includes/media.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/image.php';

    // Usar media_sideload_image para descargar y subir la imagen
    $image_id = media_sideload_image( $image_url, $attach_to_post, null, 'id' );

    // Verificar si la subida fue exitosa
    if ( is_wp_error( $image_id ) ) {
        return false;
    }

    // Si se agregó a la biblioteca de medios, generar los metadatos
    if ( $add_to_media && $image_id ) {
        $attach_data = wp_generate_attachment_metadata( $image_id, get_attached_file( $image_id ) );
        wp_update_attachment_metadata( $image_id, $attach_data );
    }

    // Retornar el ID de la imagen
    return [
        'attachment_id' => $image_id
    ];
}
