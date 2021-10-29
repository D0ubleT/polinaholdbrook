<?php
/**
 * The template for displaying the blog.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package polinaholdbrook
 */

get_header();
?>

<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>

<section id="posts">
<?php

while ( have_posts() ) {
	the_post();

	polinaholdbrook_print_post();

}

the_posts_navigation();

?>
</section>

<?php

get_sidebar();
get_footer();
