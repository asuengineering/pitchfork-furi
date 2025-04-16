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

</footer>

<div id="asu-react-footer"></div>

<?php wp_footer(); ?>

</body>

</html>

