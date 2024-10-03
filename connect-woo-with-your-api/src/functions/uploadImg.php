<?php
if ( ! defined( 'ABSPATH' ) ) exit;
 
function CWWYA_upload_image($image_url, $attach_to_post = 0, $add_to_media = true )  {
	
	$remote_image = fopen($image_url, 'r');

	if ( !$remote_image ) return false;
	
	$meta = stream_get_meta_data( $remote_image );
	
	
	$image_meta = false;
	$image_filetype = false;
	
	if ( $meta && !empty($meta['wrapper_data']) ) {
		foreach( $meta['wrapper_data'] as $v ) {
			if ( preg_match('/Content\-Type: ?((image)\/?(jpe?g|png|gif|bmp))/i', $v, $matches ) ) {
				$image_meta = $matches[1];
				$image_filetype = $matches[3];
			}
		}
	}
	
	// Resource did not provide an image.
	if ( !$image_meta ) return false;
	
	$v = basename($image_url);
	if ( $v && strlen($v) > 6 ) {
		// Create a filename from the URL's file, if it is long enough
		$path = $v;
	}else{
		// Short filenames should use the path from the URL (not domain)
		$url_parsed = wp_parse_url( $image_url );
		$path = isset($url_parsed['path']) ? $url_parsed['path'] : $image_url;
	}
	
	$path = preg_replace('/(https?:|\/|www\.|\.[a-zA-Z]{2,4}$)/i', '', $path );
	$filename_no_ext = sanitize_title_with_dashes( $path, '', 'save' );
	
	$extension = $image_filetype;
	$filename = $filename_no_ext . "." . $extension;
	
	// Simulate uploading a file through $_FILES. We need a temporary file for this.
	$stream_content = stream_get_contents( $remote_image );
	
	$tmp = tmpfile();
	$tmp_path = stream_get_meta_data( $tmp )['uri'];
	fwrite( $tmp, $stream_content );
	fseek( $tmp, 0 ); // If we don't do this, WordPress thinks the file is empty
	
	$fake_FILE = array(
		'name'     => $filename,
		'type'     => 'image/' . $extension,
		'tmp_name' => $tmp_path,
		'error'    => UPLOAD_ERR_OK,
		'size'     => strlen( $stream_content ),
	);
	
	// Trick is_uploaded_file() by adding it to the superglobal
	$_FILES[basename( $tmp_path )] = $fake_FILE;
	
	// For wp_handle_upload to work:
	include_once ABSPATH . 'wp-admin/includes/media.php';
	include_once ABSPATH . 'wp-admin/includes/file.php';
	include_once ABSPATH . 'wp-admin/includes/image.php';
	
	$result = wp_handle_upload( $fake_FILE, array(
		'test_form' => false,
		'action'    => 'local',
	) );
	
	fclose( $tmp ); // Close tmp file
	@unlink( $tmp_path ); // Delete the tmp file. Closing it should also delete it, so hide any warnings with @
	unset( $_FILES[basename( $tmp_path )] ); // Clean up our $_FILES mess.
	
	fclose( $remote_image ); // Close the opened image resource
	
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