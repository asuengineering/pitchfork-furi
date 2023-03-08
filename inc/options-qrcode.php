<?php
/**
 * QR code settings and utility functions.
 *  - Add an options panel for the ability to set the text and size of the code.
 *  - Include options for button size, color, etc.
 *
 * @package uds-wordpress-furi
 */

require get_stylesheet_directory() . '/vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'QR code settings',
		'menu_title'	=> 'QR Code Settings',
		'menu_slug' 	=> 'qr-code-settings',
		'capability'	=> 'edit_posts',
        'parent_slug'   => 'edit.php?post_type=participant',
		'redirect'		=> false,
        'update_button' => __('Update', 'acf'),
        'updated_message' => __('QR Code options updated.', 'acf'),
	));

}

function qr_code_modal_window( $participant_id ) {

    $qr_display     = get_field('qr_options_display', 'option');
    $qr_message     = get_field('qr_options_message', 'option');
    $qr_size        = get_field('qr_options_code_size', 'option');
    $qr_btn_size    = get_field('qr_options_button_size', 'option');
    $qr_btn_color   = get_field('qr_options_button_color', 'option');
    $qr_btn_label    = get_field('qr_options_button_text', 'option');
    $qr_tracking    = get_field('qr_options_utm_tracking', 'option');

    $options = new QROptions([
        'version'      => QRCode::VERSION_AUTO,
        'outputType'   => QRCode::OUTPUT_IMAGE_JPG,
        'eccLevel'   => QRCode::ECC_L,
        'scale'        => $qr_size,
        'imageBase64'  => true,
    ]);

    // Invoke a fresh QRCode instance
    $qrcode = new QRCode($options);

    // Format the produced link.
    $permalink = get_the_permalink( $participant_id );
    $data = str_replace('http://furi.test/', 'https://furi.engineering.asu.edu/', $permalink); // Local development bonus.
    $data .= $qr_tracking;

    // Build the button.
    $trigger_classes = array('openModalButton', 'btn', $qr_btn_color, $qr_btn_size );
    $modal = '<button class="' . implode(' ', $trigger_classes ) . '">' . $qr_btn_label . '</button>';

    $modal .= '<div class="uds-modal">
                <div class="uds-modal-container">
                    <button class="uds-modal-close-btn closeModalButton">
                        <i class="fas fa-times fa-stack-1x"></i><span class="sr-only">Close</span>
                    </button>
                    <div class="qr-code-wrap">
                        <img src="' . esc_attr( $qrcode->render($data) ) . '" alt="QR code for the current page"/>' . $qr_message . '
                    </div>
                </div>
            </div>';

    if ( $qr_display ) {
        return $modal;
    } else {
        return;
    }

}

