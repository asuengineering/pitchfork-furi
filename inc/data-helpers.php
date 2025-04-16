<?php
/**
 * Helping functions for various symposium detail outputs.
 *
 * @package uds-wordpress-furi
 */

/**
 * Get list of term IDs with term meta 'furi_symposium_session_display' = 'yes'
 *
 * @return $term_ids
 */
function get_active_symposium_terms() {
	$term_args = array( 'taxonomy' => 'symposium_date' );
	$terms = get_terms( $term_args );

	$term_ids = array();

	foreach ( $terms as $term ) {
		$key = get_term_meta( $term->term_id, 'furi_symposium_session_display', true );

		if ( $key == true ) {
			// Push the ID into the array.
			$term_ids[] = $term->term_id;
		}
	};

	return $term_ids;
}

/**
 * Get list of term names with term meta 'furi_symposium_session_display' = 'yes'
 *
 * @return $term_names
 */
function get_active_symposium_names() {
	$term_args = array( 'taxonomy' => 'symposium_date' );
	$terms = get_terms( $term_args );

	$term_names = '';

	foreach ( $terms as $term ) {
		$key = get_term_meta( $term->term_id, 'furi_symposium_session_display', true );

		if ( $key == true ) {
			// Add term name to string.
			$term_names = $term_names . $term->name . ' ';
		}
	};

	return $term_names;
}

/**
 * Construct a set of CSS classes associated with a furiproject's research category
 *
 * @return $css_class
 */
function get_research_theme_class_names( $projectID ) {
	$css_class = '';

	$terms = get_the_terms( $projectID, 'research_theme' );

	if ( ! empty ( $terms )) {

		foreach ( $terms as $term ) {
			$css_class .= 'theme-' . $term->slug . '-bg ';
			$css_class .= 'theme-' . $term->slug . '-text ';
		}

	}

	return trim( $css_class );
}

/**
 * Determine if a link should be displayed to join a Zoom session.
 * If so, which link should be displayed?
 * Output is either empty or a fully marked up button.
 *
 * @return $button;
 */
function get_symposium_status_url( $projectID ) {

	$button = '';

	// Get the list of terms associated with this project.
	$terms = get_the_terms( $projectID, 'symposium_date' );
	$term_ids = array();

	$display_conference_flag = false;
	foreach ( $terms as $term ) {
		$display_conference = get_field( 'furi_symposium_conference_display', $term );
		if ( $display_conference == true ) {
			$display_conference_flag = true;
		}
	}

	if ( $display_conference_flag == true ) {
		// Game on. We should produce either a button or some text.

		$button = '<a class="btn btn-gold btn-conference" href="';

		// URL setting from individual project. Overrides setting applied at the group level.
		$project_url = get_field( '_furiproject_conference_url', $projectID );

		// Symposium group details.
		// If there is more than one selected, just use the first value in the array.
		$groups = get_the_terms( $projectID, 'symposium_group' );
		$display_option = get_field( 'symposiumgroup_display_options', $groups[0]);

		if ( ! empty( $project_url ) ) {
			// Use the included project URL as the href.
			$button .= $project_url;
			$button .= '" target="_blank">Join the session<span class="fas fa-external-link-alt"></span></a>';
			return $button;
		}

		if ( empty( $groups ) ) {
			// No group selected, don't display anything.
			return;
		} else {
			// Which kind of information are we displaying?
			if ('button' == $display_option) {

				$display_url = esc_url(get_field( 'symposiumgroup_button_url', $groups[0]));
				$display_label = esc_html(get_field( 'symposiumgroup_button_label', $groups[0]));
				$display_external = get_field( 'symposiumgroup_button_external', $groups[0]);
				if ($display_external) {
					$icon = '<span class="fas fa-external-link-alt"></span>';
				} else {
					$icon = '';
				}

				$button .= $display_url . '" target="_blank">'. $display_label . $icon . '</a>';
				return $button;
			} else {
				// ('text' == $display_option)
				$display_message = get_field('symposiumgroup_text', $groups[0]);
				return $display_message;
			}
		}
	} else {
		// Display conference flag was false.
		return;
	}
}

/**
 * Symposium Overview Function: Build Select Boxes from taxonomy data.
 * See: https://css-tricks.com/getting-wordpress-term-results-that-are-relative-to-a-different-taxonomy/
 */
function get_all_project_tax_terms( $args, $taxonomy, $label ) {
	$selectbox = '';
	$projectids = get_posts( $args );

	$terms = wp_get_object_terms( $projectids, $taxonomy, 'orderby=name&order=ASC&hide_empty=0' );

	// $terms = get_terms( $taxonomy, 'order=ASC&hide_empty=0' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$selectbox .= '<select id="filter-' . $taxonomy . '" class="filter form-select" title="Select a ' . $label . '">';
		foreach ( $terms as $term ) {
			$selectbox .= '<option value=".' . $term->slug . '">' . $term->name . '</option>';
		}
		$selectbox .= '</select>';
	}
	return $selectbox;
}

/**
 * Mostly the same function as above but starts with IDs and not query args.
 */
function get_all_participant_tax_terms( $ids, $taxonomy, $label ) {

	$terms = wp_get_object_terms( $ids, $taxonomy, 'order=ASC' );
	$selectbox = '';
	// $terms = get_terms( $taxonomy, 'order=ASC&hide_empty=0' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$selectbox .= '<select id="filter-' . $taxonomy . '" class="filter form-select" title="Select a ' . $label . '">';
		foreach ( $terms as $term ) {
			$selectbox .= '<option value=".' . $term->slug . '">' . $term->name . '</option>';
		}
		$selectbox .= '</select>';
	}
	return $selectbox;
}

/**
 * Produces radio buttons for any included research theme.
 */
function get_research_theme_radios( $args, $taxonomy, $label ) {

	$selectbox = '';
	$radio = '';
	$projectids = get_posts( $args );

	$terms = wp_get_object_terms( $projectids, $taxonomy, 'orderby=name&order=ASC&hide_empty=0' );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$radio .= '<div class="form-check form-check-inline">';
		$radio .= '<input class="form-check-input" type="radio" name="researchThemeRadio" id="theme-research_theme" value="" checked>';
		$radio .= '<label class="form-check-label-disabled" for="theme-research_theme" data-toggle="tooltip" data-placement="top" title="All research themes">';
		$radio .= '<img class="research-theme-icon" src="' . get_stylesheet_directory_uri() . '/img/Select-ALL-icon.png" alt="Select all icon, enabled" />';
		$radio .= '</label>';
		$radio .= '<label class="form-check-label-enabled" for="theme-research_theme" data-toggle="tooltip" data-placement="top" title="All research themes">';
		$radio .= '<img class="research-theme-icon" src="' . get_stylesheet_directory_uri() . '/img/Select-ALL-icon-ACTIVE.png" alt="Select all icon, enabled"  />';
		$radio .= '</label>';
		$radio .= '</div>';
		foreach ( $terms as $term ) {

			$themeicon = get_field( 'researchtheme_icon', $term );
			$themeiconEnabled = get_field( 'researchtheme_icon_enabled', $term );

			$radio .= '<div class="form-check form-check-inline">';
			$radio .= '<input class="form-check-input" type="radio" name="researchThemeRadio" id="theme-' . $term->slug . '" value=".' . $term->slug . '">';
			$radio .= '<label class="form-check-label-disabled" for="theme-' . $term->slug . '" data-toggle="tooltip" data-placement="top" title="' . $term->name . '">';
			$radio .= '<img class="research-theme-icon" src="' . esc_url( $themeicon['url'] ) . '" alt="' . esc_attr( $themeicon['alt'] ) . '" />';
			$radio .= '</label>';
			$radio .= '<label class="form-check-label-enabled" for="theme-' . $term->slug . '" data-toggle="tooltip" data-placement="top" title="' . $term->name . '">';
			$radio .= '<img class="research-theme-icon" src="' . esc_url( $themeiconEnabled['url'] ) . '" alt="' . esc_attr( $themeiconEnabled['alt'] ) . '" />';
			$radio .= '</label>';
			$radio .= '</div>';
		}
		// $selectbox .= '</select>';
	}
	return $radio;
}

/**
 * Produces radio buttons for any included project category.
 */
function get_project_type_radios( $args, $taxonomy, $label ) {

	$selectbox = '';
	$projectids = get_posts( $args );

	$terms = wp_get_object_terms( $projectids, $taxonomy, 'orderby=name&order=ASC&hide_empty=0' );
	$radio = '';

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$radio .= '<div class="form-check">';
		$radio .= '<input class="form-check-input" type="radio" name="presentationTypeRadio" id="presentation-project_type" value="" checked>';
		$radio .= '<label class="form-check-label" for="presentation-project_type">All programs</label>';
		$radio .= '</div>';
		foreach ( $terms as $term ) {
			$radio .= '<div class="form-check">';
			$radio .= '<input class="form-check-input" type="radio" name="presentationTypeRadio" id="presentation-' . $term->slug . '" value=".' . $term->slug . '">';
			$radio .= '<label class="form-check-label" for="presentation-' . $term->slug . '">' . $term->name . '</label>';
			$radio .= '</div>';
		}
		// $selectbox .= '</select>';
	}
	return $radio;
}

/**
 * Symposium Overview Function: Build filter item classes from taxonomy info
 */

function asufse_symposium_tax_filteritem_classes() {

	// Get post by post ID.
	if ( ! $post = get_post() ) {
		return '';
	}

	// Get post type by post.
	$post_type = $post->post_type;

	// Get post type taxonomies.
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	$out = array();

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

		// Get the terms related to post.
		$terms = get_the_terms( $post->ID, $taxonomy_slug );

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$out[] = sprintf(
					'%1$s ',
					esc_html( $term->slug )
				);
			}
		}
	}
	return implode( '', $out );
}

/**
 * Build participant name.
 */
function furi_participant_name( $personID ) {
	$first = get_field( '_participant_first_name', $personID );
	$middle = get_field( '_participant_middle_name', $personID );
	$last = get_field( '_participant_last_name', $personID );
	$suffix = get_field( '_participant_suffix', $personID );

	// Append spaces if not blank. Assume there's always a last name.
	if ( ! empty( $first ) ) {
		$first .= ' ';
	}

	if ( ! empty( $middle ) ) {
		$middle .= ' ';
	}

	if ( ! empty( $suffix ) ) {
		$suffix .= ' ';
	}

	return $first . $middle . $last . ' ' . $suffix;
}
