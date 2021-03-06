<?php
/**
* The header template.
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package polinaholdbrook
*/

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<title>Polina Holdbrook</title>
	<meta name="description" content="Página web de @polina_holdbrook">
	<meta name="keywords" content="arte, encuadernación, cuadernos, comisiones artísticas, comisiones, anime, manga" />
	<link rel="icon" type="image/png" href="<?php echo esc_attr( get_stylesheet_directory_uri() . '/img/favicon.png' ); ?>" />

	<!-- ícono Instagram -->
	<script src="https://kit.fontawesome.com/9bad42af95.js" crossorigin="anonymous"></script>
	<?php wp_site_icon(); ?>
	<?php wp_head(); ?>
</head>
<body <?php polinaholdbrook_print_body_class(); ?>>
	<header>
		<div class="inner-container">
			<a href="<?php bloginfo( 'url' ); ?>" class="site-icon">
				<img src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/img/perfil.png' ); ?>">
			</a>
			<?php polinaholdbrook_print_menu( 'header' ); ?>
			<!-- <div id="hamburger-menu-container">
				<button id="hamburger-menu-toggler">+ / -</button>
				<?php polinaholdbrook_print_menu( 'hamburger' ); ?>
			</div> -->
		</div>
	</header>
	<main>
