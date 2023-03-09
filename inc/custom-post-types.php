<?php
/**
 * Declare custom post types for the theme.
 * Yes, this is "supposed" to be in a plugin. I disagree. =)
 *
 * @package uds-wordpress-furi
 */

/**
 * CPT: FURI Project (furiproject)
 */
function asufse_register_furiproject_cpt() {

	$labels = array(
		'name'                  => _x( 'FURI Projects', 'Post Type General Name', 'uds-wordpress-theme' ),
		'singular_name'         => _x( 'Furi Project', 'Post Type Singular Name', 'uds-wordpress-theme' ),
		'menu_name'             => __( 'FURI Projects', 'uds-wordpress-theme' ),
		'name_admin_bar'        => __( 'FURI Projects', 'uds-wordpress-theme' ),
		'archives'              => __( 'Project Archives', 'uds-wordpress-theme' ),
		'attributes'            => __( 'Project Attributes', 'uds-wordpress-theme' ),
		'parent_item_colon'     => __( 'Parent Project:', 'uds-wordpress-theme' ),
		'all_items'             => __( 'All Projects', 'uds-wordpress-theme' ),
		'add_new_item'          => __( 'Add New Project', 'uds-wordpress-theme' ),
		'add_new'               => __( 'Add New', 'uds-wordpress-theme' ),
		'new_item'              => __( 'New Project', 'uds-wordpress-theme' ),
		'edit_item'             => __( 'Edit Project', 'uds-wordpress-theme' ),
		'update_item'           => __( 'Update Project', 'uds-wordpress-theme' ),
		'view_item'             => __( 'View Project', 'uds-wordpress-theme' ),
		'view_items'            => __( 'View Projects', 'uds-wordpress-theme' ),
		'search_items'          => __( 'Search Project', 'uds-wordpress-theme' ),
		'not_found'             => __( 'Not found', 'uds-wordpress-theme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'uds-wordpress-theme' ),
		'insert_into_item'      => __( 'Insert into project', 'uds-wordpress-theme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this project', 'uds-wordpress-theme' ),
		'items_list'            => __( 'Projects list', 'uds-wordpress-theme' ),
		'items_list_navigation' => __( 'Projects list navigation', 'uds-wordpress-theme' ),
		'filter_items_list'     => __( 'Filter projects list', 'uds-wordpress-theme' ),
	);
	$args = array(
		'label'                 => __( 'Furi Project', 'uds-wordpress-theme' ),
		'description'           => __( 'A project for the FURI symposium', 'uds-wordpress-theme' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'taxonomies'            => array( 'faculty_mentor', 'research_theme', 'symposium_date', 'presentation_type' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'furiproject', $args );
}
add_action( 'init', 'asufse_register_furiproject_cpt', 0 );

/**
 * TAX: Research Theme, for furiproject CPT
 */
function asufse_research_theme_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Research Themes', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Research Theme', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Research Themes', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'All Themes', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Theme', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Theme:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Theme Name', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Theme', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Theme', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Theme', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Theme', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate themes with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove themes', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Themes', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Themes', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No themes', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Themes list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Themes list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'research_theme', array( 'furiproject' ), $args );

}
add_action( 'init', 'asufse_research_theme_taxonomy', 0 );

/**
 * TAX: Symposium Date, for furiproject CPT
 */
function asufse_register_symposium_date_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Symposium Dates', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Symposium Date', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Symposium Date', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'Symposium Dates', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Date', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Date:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Date', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Date', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Date', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Date', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Date', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate dates with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove dates', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Dates', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Dates', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No dates', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Dates list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Dates list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'symposium_date', array( 'furiproject' ), $args );

}
add_action( 'init', 'asufse_register_symposium_date_taxonomy', 0 );

/**
 * TAX: Symposium Group, for furiproject CPT
 */
function asufse_register_symposium_group_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Symposium Groups', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Symposium Group', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Symposium Group', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'Symposium Groups', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Group', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Group:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Group', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Group', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Group', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Group', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Group', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Groups', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Groups', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No groups', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Groups list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Groups list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'symposium_group', array( 'furiproject' ), $args );

}
add_action( 'init', 'asufse_register_symposium_group_taxonomy', 0 );

/**
 * TAX: Presentation Type, for furiproject CPT
 */
function aufse_register_presentation_type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Presentation Types', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Presentation Type', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Presenter Type', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'Presenter Types', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Type', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Type:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Presenter Type', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Presenter', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Presenter', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Presenter', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Presenter', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate typed with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Presenter Types', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Presenter Types', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No types', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Presenter list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Presenter list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'presentation_type', array( 'furiproject' ), $args );

}
add_action( 'init', 'aufse_register_presentation_type_taxonomy', 0 );

/**
 * TAX: Faculty Mentor, for furiproject CPT
 */
function asufse_register_faculty_mentor_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Faculty Mentors', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Faculty Mentor', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Faculty Mentor', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'All Mentors', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Mentor', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Mentor:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Mentor Name', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Mentor', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Mentor', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Mentor', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Mentor', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate mentors with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove mentors', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Mentors', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Mentors', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No mentors', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Mentors list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Mentors list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'faculty_mentor', array( 'furiproject' ), $args );

}
add_action( 'init', 'asufse_register_faculty_mentor_taxonomy', 0 );

/**
 * TAX: Faculty Mentor, for furiproject CPT
 */
function asufse_register_sponsored_research_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Industry Sponsors', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Industry Sponsor', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Industry Sponsor', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'All Sponsors', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Sponsor', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Sponsor:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Sponsor Name', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Sponsor', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Sponsor', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Sponsor', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Sponsor', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate sponsors with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove sponsors', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Sponsors', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Sponsors', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No sponsors', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Sponsors list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Sponsors list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'industry_sponsor', array( 'furiproject' ), $args );

}
add_action( 'init', 'asufse_register_sponsored_research_taxonomy', 0 );

/**
 * CPT: Symposium Participants (participant)
 */
function asufse_register_participant_cpt() {
	$labels = array(
		'name'                  => _x( 'Participants', 'Post Type General Name', 'uds-wordpress-theme' ),
		'singular_name'         => _x( 'Participant', 'Post Type Singular Name', 'uds-wordpress-theme' ),
		'menu_name'             => __( 'Participants', 'uds-wordpress-theme' ),
		'name_admin_bar'        => __( 'Participant', 'uds-wordpress-theme' ),
		'archives'              => __( 'Participant Archives', 'uds-wordpress-theme' ),
		'attributes'            => __( 'Participant Attributes', 'uds-wordpress-theme' ),
		'parent_item_colon'     => __( 'Parent Participant:', 'uds-wordpress-theme' ),
		'all_items'             => __( 'All Participants', 'uds-wordpress-theme' ),
		'add_new_item'          => __( 'Add New Participant', 'uds-wordpress-theme' ),
		'add_new'               => __( 'Add New', 'uds-wordpress-theme' ),
		'new_item'              => __( 'New Participant', 'uds-wordpress-theme' ),
		'edit_item'             => __( 'Edit Participant', 'uds-wordpress-theme' ),
		'update_item'           => __( 'Update Participant', 'uds-wordpress-theme' ),
		'view_item'             => __( 'View Participant', 'uds-wordpress-theme' ),
		'view_items'            => __( 'View Participants', 'uds-wordpress-theme' ),
		'search_items'          => __( 'Search Participant', 'uds-wordpress-theme' ),
		'not_found'             => __( 'Not found', 'uds-wordpress-theme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'uds-wordpress-theme' ),
		'featured_image'        => __( 'Profile Photo', 'uds-wordpress-theme' ),
		'set_featured_image'    => __( 'Set profile photo', 'uds-wordpress-theme' ),
		'remove_featured_image' => __( 'Remove profile photo', 'uds-wordpress-theme' ),
		'use_featured_image'    => __( 'Use as profile photo', 'uds-wordpress-theme' ),
		'insert_into_item'      => __( 'Insert into partipant', 'uds-wordpress-theme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this participant', 'uds-wordpress-theme' ),
		'items_list'            => __( 'Participants list', 'uds-wordpress-theme' ),
		'items_list_navigation' => __( 'Participants list navigation', 'uds-wordpress-theme' ),
		'filter_items_list'     => __( 'Filter participants list', 'uds-wordpress-theme' ),
	);
	$args = array(
		'label'                 => __( 'Participant', 'uds-wordpress-theme' ),
		'description'           => __( 'A Person who participates in a FURI Symposium', 'uds-wordpress-theme' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'page-attributes', 'revisions' ),
		'taxonomies'            => array( 'degree_program', 'graduation_date' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'menu_icon'             => 'dashicons-universal-access',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'participant', $args );

}
add_action( 'init', 'asufse_register_participant_cpt', 0 );

/**
 * TAX: Degree Program, for participant CPT
 */
function asufse_register_degree_program_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Degree Programs', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Degree Program', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Degree Program', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'All Degree Programs', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Degree', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Degree:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Degree Name', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Degree', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Degree', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Degree', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Degree', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate degrees with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove degrees', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Degrees', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Degrees', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No degrees', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Degrees list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Degrees list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'degree_program', array( 'participant' ), $args );

}
add_action( 'init', 'asufse_register_degree_program_taxonomy', 0 );

/**
 * TAX: Graduation Date, for participant CPT
 */
function asufse_register_graduation_date_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Graduation Dates', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Graduation Date', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Graduation Date', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'Graduation Dates', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Date', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Date:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Date', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Date', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Date', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Date', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Date', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate dates with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove dates', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Dates', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Dates', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No dates', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Dates list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Dates list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'graduation_date', array( 'participant' ), $args );

}
add_action( 'init', 'asufse_register_graduation_date_taxonomy', 0 );


/**
 * TAX: Participant Details for participant CPT
 */
function asufse_register_participant_details_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Participant Details', 'Taxonomy General Name', 'uds-wordpress-theme' ),
		'singular_name'              => _x( 'Participant Detail', 'Taxonomy Singular Name', 'uds-wordpress-theme' ),
		'menu_name'                  => __( 'Participant Detail', 'uds-wordpress-theme' ),
		'all_items'                  => __( 'Participant Details', 'uds-wordpress-theme' ),
		'parent_item'                => __( 'Parent Detail', 'uds-wordpress-theme' ),
		'parent_item_colon'          => __( 'Parent Detail:', 'uds-wordpress-theme' ),
		'new_item_name'              => __( 'New Detail', 'uds-wordpress-theme' ),
		'add_new_item'               => __( 'Add New Detail', 'uds-wordpress-theme' ),
		'edit_item'                  => __( 'Edit Detail', 'uds-wordpress-theme' ),
		'update_item'                => __( 'Update Detail', 'uds-wordpress-theme' ),
		'view_item'                  => __( 'View Detail', 'uds-wordpress-theme' ),
		'separate_items_with_commas' => __( 'Separate details with commas', 'uds-wordpress-theme' ),
		'add_or_remove_items'        => __( 'Add or remove details', 'uds-wordpress-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'uds-wordpress-theme' ),
		'popular_items'              => __( 'Popular Details', 'uds-wordpress-theme' ),
		'search_items'               => __( 'Search Details', 'uds-wordpress-theme' ),
		'not_found'                  => __( 'Not Found', 'uds-wordpress-theme' ),
		'no_terms'                   => __( 'No details', 'uds-wordpress-theme' ),
		'items_list'                 => __( 'Details list', 'uds-wordpress-theme' ),
		'items_list_navigation'      => __( 'Details list navigation', 'uds-wordpress-theme' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'participant_details', array( 'participant' ), $args );

}
add_action( 'init', 'asufse_register_participant_details_taxonomy', 0 );

