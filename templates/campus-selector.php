<?php
/**
 * RockPress Campus Selector Widget Template
 *
 * @package RockPress
 */

?>
<div class="rockpress-campus-selector">
	<?php foreach ( $data as $campus ) : ?>
		<div class="rockpress-campus-selector-campus dashicons-before dashicons-location">
			<a href="<?php echo esc_attr( $campus['url'] ); ?>"><?php echo esc_html( $campus['name'] ); ?></a>
		</div>
	<?php endforeach; ?>
</div>
