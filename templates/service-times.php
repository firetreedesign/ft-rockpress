<?php
/**
 * RockPress Service Times Widget Template
 *
 * @package RockPress
 */

?>
<div class="rockpress-service-times">
	<?php foreach ( $data as $day => $times ) : ?>
		<div class="rockpress-service-times-service">
			<div class="rockpress-service-times-day"><?php echo esc_html( $day ); ?></div>
			<?php foreach ( $times as $time ) : ?>
				<div class="rockpress-service-times-time"><span class="dashicons dashicons-clock"></span> <?php echo esc_html( (string) date( 'g:i a', strtotime( $time ) ) ); ?></div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
