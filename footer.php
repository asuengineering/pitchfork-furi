<?php
/**
 * Altered template for displaying the footer.
 * Attempting to place count-up totals within the desktop version.
 * (Hidden on mobile.)
 *
 * @package pitchfork-furi
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<footer id="asu-footer">

	<div class="wrapper" id="wrapper-endorsed-footer">
		<?php uds_wp_render_footer_branding_row(); ?>
	</div> <!-- wrapper-endorsed-footer -->

	<?php do_action( 'uds_wp_before_global_footer_columns' ); ?>

	<div class="wrapper" id="wrapper-footer-columns">
		<?php
		// New footer action row function, located in functions.php
		pitchfork_furi_render_footer_action_row();
		?>
	</div>

	<div class="wrapper" id="wrapper-footer-innovation">
		<div class="container" id="footer-innovation">
			<div class="row">
				<div class="col">
					<div class="d-flex footer-innovation-links">
						<a class="img-link" href="https://www.asu.edu/rankings">
						<img src="<?php echo get_template_directory_uri(); ?>/src/img/university-logo/GlobalFooter-No1InnovationLockup_2023.png" alt="Number one in the U.S. for innovation. ASU ahead of MIT and Stanford . - U.S. News and World Report, 8 years, 2016-2023">

					</a>
						<nav class="nav" aria-label="University Services">
							<a class="nav-link" href="https://asu.edu/map/">Maps and Locations</a>
							<a class="nav-link" href="https://asu.edu/asujobs">Jobs</a>
							<a class="nav-link" href="https://isearch.asu.edu/">Directory</a>
							<a class="nav-link" href="https://asu.edu/contactasu/">Contact ASU</a>
							<a class="nav-link" href="https://my.asu.edu/">My ASU</a>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- wrapper-footer-innovation -->

	<div class="wrapper" id="wrapper-footer-colophon">
		<div class="container" id="footer-colophon">
			<div class="row">
				<div class="col">
					<nav class="nav colophon" aria-label="University Legal and Compliance">
						<a class="nav-link" href="https://asu.edu/copyright/">Copyright and Trademark</a>
						<a class="nav-link" href="https://asu.edu/accessibility/">Accessibility</a>
						<a class="nav-link" href="https://asu.edu/privacy/">Privacy</a>
						<a class="nav-link" href="https://asu.edu/tou/">Terms of Use</a>
						<a class="nav-link" href="https://asu.edu/emergency/">Emergency</a>
						<a class="nav-link" href="https://eoss.asu.edu/health/announcements/coronavirus">COVID-19 Information</a>
					</nav>
				</div>
			</div>
		</div>
	</div> <!-- wrapper-footer-colophon -->
</footer>

<div id="cookie-consent-container"></div>

<?php wp_footer(); ?>

</body>

</html>

