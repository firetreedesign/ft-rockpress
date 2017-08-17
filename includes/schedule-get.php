<?php
function rockpress_schedule_get( $args ) {
	RockPress()->rock->get( $args );
}
add_action( 'rockpress_schedule_get', 'rockpress_schedule_get', 10, 1 );
