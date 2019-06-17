<?php
/**
 * Displays footer information like copyright
 *
 * @package Cenote
 */

?>
<div class="site-info col-md-10 offset-md-1">
	<?php
		/* translators: 1: Current Year, 2: Blog Name 3: Theme Developer 4: WordPress. */
		printf( esc_html__( 'Copyright © Brainy Bucks Games Pvt. Ltd. All Rights Reserved.', 'cenote' ), esc_attr( date( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ), '<a href="https://themegrill.com/themes/cenote"></a>', '<a href="https://wordpress.org"></a>' );

	?>
	<p class="mt-2"> FSL11 is not affiliated in any way to and claims no association, FSL11 acknowledges that the ICC, BCCI, IPL and its franchises/teams.Own all proprietary names and marks relating to the relevant tournament or competition. Residents of the states of Assam, Odisha and Telangana, and where otherwise prohibited by law are not eligible to enter FSL11 leagues.</p>

</div><!-- .site-info -->
