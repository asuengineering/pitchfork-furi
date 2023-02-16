<?php
/**
 * Registers post to posts connections.
 *
 * @package uds-wordpress-theme
 */

/**
 * Posts 2 Posts Connection Info
 */
function my_connection_types() {
	p2p_register_connection_type(
		array(
			'name' => 'participants_to_projects',
			'from' => 'furiproject',
			'to' => 'participant',
			'reciprocal' => true,
			'title' => array(
				'from' => __( 'FURI Participant', 'uds-wordpress-theme' ),
				'to' => __( 'Associated Projects', 'uds-wordpress-theme' ),
			),
			'admin_box' => array(
				'show' => 'any',
				'context' => 'advanced',
			),
			'admin_column' => 'any',
		)
	);
}
add_action( 'p2p_init', 'my_connection_types' );
