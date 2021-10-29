<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package polinaholdbrook
 */

get_header();

if ( have_posts() ) {
	the_archive_title( '<h1 class="page-title">', '</h1>' );
	the_archive_description( '<div class="archive-description">', '</div>' );

	while ( have_posts() ) {
		the_post();

		polinaholdbrook_print_post();
	}

	the_posts_navigation();
}

get_sidebar();
get_footer();
