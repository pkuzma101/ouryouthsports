<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package framer
 */
?>
<div class="col-1-3"><div class="wrap-col sidebar">
        

<?php
	if ( is_active_sidebar( 'sidebar-1' ) ):
		dynamic_sidebar( 'sidebar-1' );
	endif;
?>   


</div>
</div></div>
