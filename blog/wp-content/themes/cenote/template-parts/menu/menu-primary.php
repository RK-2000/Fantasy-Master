<?php
/**
 * Displays primary menu
 *
 * @package Cenote
 */

?>
<nav id="site-navigation" class="main-navigation <?php cenote_navigation_class(); ?>">
	<?php
	wp_nav_menu( array(
		'theme_location' => 'tg-menu-primary',
		'menu_id'        => 'primary-menu',
	) );
	?>
</nav><!-- #site-navigation -->
