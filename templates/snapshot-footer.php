<?php
/**
 * A template for displaying a snapshot right above the global footer.
 *
 * @package uds-wordpress-theme
 */

$participants = wp_count_posts( 'participant' )->publish;
$mentors = wp_count_terms( 'faculty_mentor');
$symposia = wp_count_terms( 'symposium_date');
$focusareas = wp_count_terms( 'research_theme', 'hide_empty=true' );

// Pull query data for all participants, loop and total for individual terms.
$projects = array();
$presentations = get_terms(
	array(
		'taxonomy' => 'presentation_type',
		'hide_empty' => true,
	)
);

foreach ($presentations as $presentation) {
	$args = array(
		'post_type' => 'furiproject',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'presentation_type',
				'field'    => 'slug',
				'terms'    => $presentation->slug,
			),
		),
	);
	$countprojects = new WP_Query($args);
	$count = $countprojects->found_posts;
	$projects[$presentation->name] = $count;
}
?>
<div class="col-lg-9">
	<!-- <h3><span class="highlight-white">FURI Totals</span></h3> -->
	<div id="snapshot-footer">
		<div class="stat-package">
			<h3><span>Total</span>Students</h3>
			<div class="counter" data-count="<?php echo esc_html( $participants ); ?>">0</div>
		</div>
		<div class="stat-package">
			<h3><span>Faculty</span>Mentors</h3>
			<div class="counter" data-count="<?php echo esc_html( $mentors ); ?>">0</div>
		</div>
		<div class="stat-package">
			<h3><span>Symposia</span>Archived</h3>
			<div class="counter" data-count="<?php echo esc_html( $symposia ); ?>">0</div>
		</div>
		<div class="stat-package focus-areas">
			<h3><span>Focus</span>Areas</h3>
			<div class="counter" data-count="<?php echo esc_html( $focusareas ); ?>">0</div>
		</div>
		<div class="stat-package">
			<h3><span>FURI</span>Projects</h3>
			<div class="counter" data-count="<?php echo esc_html( $projects['FURI'] ); ?>">0</div>
		</div>
		<div class="stat-package">
			<h3><span>MORE</span>Projects</h3>
			<div class="counter" data-count="<?php echo esc_html( $projects['MORE'] ); ?>">0</div>
		</div>
		<!-- <div class="stat-package">
			<h3><span>KEEN</span>Projects</h3>
			<div class="counter" data-count="<?php echo esc_html( $projects['KEEN'] ); ?>">0</div>
		</div> -->
		<div class="stat-package">
			<h3><span>GCSP</span>Projects</h3>
			<div class="counter" data-count="<?php echo esc_html( $projects['GCSP'] ); ?>">0</div>
		</div>
	</div>
</div>
