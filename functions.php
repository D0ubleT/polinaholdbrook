<?php
/**
 * Polina Holdbrook Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Polina_Theme
 */

if ( ! defined( 'polinaholdbrook_VERSION' ) ) {
	define( 'polinaholdbrook_VERSION', '0.0.1' );
}

add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style( 'polinaholdbrook', get_stylesheet_directory_uri() . '/style.min.css', array(), polinaholdbrook_VERSION );
		wp_enqueue_script( 'polinaholdbrook', get_stylesheet_directory_uri() . '/js/main.js', array(), polinaholdbrook_VERSION, true );
	}
);

add_action(
	'init',
	function() {
		// all actions related to emojis.
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		// filter to remove TinyMCE emojis.
		add_filter(
			'tiny_mce_plugins',
			function( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				} else {
					return array();
				}
			}
		);
	}
);

add_action(
	'after_setup_theme',
	function() {
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'polinaholdbrook', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		register_nav_menus(
			array(
				'header'    => esc_html__( 'Header Menu', 'polinaholdbrook' ),
				'hamburger' => esc_html__( 'Hamburger Menu (Mobile)', 'polinaholdbrook' ),
				'footer'    => esc_html__( 'Footer Menu', 'polinaholdbrook' ),
			)
		);
	}
);

/*
 * Remove filter in order to prevent the unwanted addition of p tags in the frontend.
 */
add_action(
	'template_redirect',
	function () {
		remove_filter( 'the_content', 'wpautop' );
	}
);

add_action(
	'widgets_init',
	function() {
		register_sidebar(
			array(
				'name'          => 'Polina Holdbrook',
				'id'            => 'polinaholdbrook',
				'class'         => '',
				'before_widget' => '<div class="widget polinaholdbrook">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			)
		);
	}
);

/**
 * Disabling Gutenberg.
 */
$disable_gutenberg = false;

if ( $disable_gutenberg ) {
	add_filter( 'use_block_editor_for_post', '__return_false' );
}


/**
 * Returns a menu.
 *
 * @param string $location The menu location.
 *
 * @return array The menu.
 */
function polinaholdbrook_get_menu( $location ) {
	$menu = null;

	if ( $location ) {
		$locations = get_nav_menu_locations();

		if ( $locations &&
			isset( $locations[ $location ] ) ) {
			$object = wp_get_nav_menu_object( $locations[ $location ] );

			if ( $object ) {
				$items = wp_get_nav_menu_items( $object->term_id );
				$menu  = array();

				if ( ! empty( $items ) ) {
					foreach ( $items as $item ) {
						$menu_item = array();

						$menu_item['item_id'] = $item->ID;
						$menu_item['title']   = $item->title;
						$menu_item['url']     = $item->url;
						$menu_item['target']  = $item->target;
						$menu_item['parent']  = $item->menu_item_parent;
						$menu_item['classes'] = $item->classes;
						$menu_item['submenu'] = array();

						if ( isset( $menu[ $menu_item['parent'] ] ) ) {
							$menu[ $menu_item['parent'] ]['submenu'][ $item->ID ] = $menu_item;
						} else {
							$menu[ $item->ID ] = $menu_item;
						}
					}
				}
			}
		}
	}

	return $menu;
}

/**
 * Returns post excerpt.
 *
 * @param WP_Post $post      Post object.
 * @param int     $num_words Optional. Maximum number of words to return.
 *                           Default 55.
 *
 * @return string Post excerpt.
 */
function polinaholdbrook_get_excerpt( $post, $num_words = 55 ) {
	if ( ! empty( $post->post_excerpt ) ) {
		$excerpt = $post->post_excerpt;
	} else {
		$excerpt = strip_shortcodes( $post->post_content );
		$excerpt = wp_strip_all_tags( $excerpt );
	}

	$excerpt = wp_trim_words( $excerpt, $num_words );

	return $excerpt;
}

/**
 * Prints the menu body class.
 */
function polinaholdbrook_print_body_class() {
	global $wp_query;

	$classes = array();

	if ( is_home() ) {
		$classes[] = 'home';
	}

	if ( is_category() ) {
		$cat = $wp_query->get_queried_object();

		if ( isset( $cat->term_id ) ) {
			$classes[] = 'category-' . sanitize_html_class( $cat->slug );
		}
	}

	if ( is_archive() ) {
		$classes[] = 'archive';

		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );

			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}

			$classes[] = 'archive-' . sanitize_html_class( $post_type );
		}
	}

	if ( is_page() || is_singular( 'page_es_es' ) ) {
		global $post;

		$classes[] = 'page page-' . sanitize_html_class( $post->post_name );
	}

	if ( is_single() ) {
		global $post;

		$classes[] = 'single';

		if ( isset( $post->post_type ) ) {
			$classes[] = 'single-' . sanitize_html_class( $post->post_type, $post->ID );
		}
	}

	if ( is_search() ) {
		$classes[] = 'search-results';
	}

	if ( is_404() ) {
		$classes[] = 'page error404';
	}

	echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
}

/**
 * The following function takes an id for argument, such as 'main' or 'footer'
 * and outputs them in html, in <ul> and <li> tags.
 *
 * @param int $menu_id The ID identifying the menu.
 */
function polinaholdbrook_print_menu( $menu_id ) {
	$menu   = polinaholdbrook_get_menu( $menu_id );
	$output = '';

	if ( $menu ) {
		$output  = '<nav id="menu-' . esc_attr( $menu_id ) . '">';
		$output .= polinaholdbrook_print_menu_object( $menu );
	}

	$output .= '</nav>';

	echo wp_kses_post( $output );
}

/**
 * The following function takes a menu array for argument, and recursively
 * concatenates the menus and submenus in a string of html code organized by
 * <ul> and <li> tags.
 *
 * @param array $menu The menu to print.
 *
 * @return string The HTML representing the menu.
 */
function polinaholdbrook_print_menu_object( $menu ) {
	$output = '<ul>';

	foreach ( $menu as $item ) {
		$output .= '<li';

		if ( ! empty( $item['classes'] ) ) {
			$output .= ' class="' . esc_attr( implode( ' ', $item['classes'] ) ) . '"';
		}

		$output .= '>';

		if ( ! empty( $item['url'] ) ) {
			$output .= '<a href="' . esc_url( $item['url'] ) . '"';

			if ( ! empty( $item['target'] ) ) {
				$output .= ' target="' . esc_attr( $item['target'] ) . '"';
			}

			$output .= '>' . esc_html( $item['title'] ) . '</a>';
		} else {
			$output .= esc_html( $item['title'] );
		}

		if ( ! empty( $item['submenu'] ) ) {
			$output .= polinaholdbrook_print_menu_object( $item['submenu'] );
		}

		$output .= '</li>';
	}

	$output .= '</ul>';

	return $output;
}

/**
 * Prints a post.
 *
 * @param int   $post_id Optional. The post to print. If null it will be print
 *                       the current post. Default null.
 * @param array $atts {
 *  Optional. Extra arguments to be sent to the function.
 *
 *  @type string  $thumbnail_size Which thumbnail size to use. Default large.
 *  @type boolean $output         Whether to output the content or not. Default
 *                                true.
 * }
 *
 * @return void|string The HTML representing the post if $atts['output'] is
 *                     true.
 */
function polinaholdbrook_print_post( $post_id = null, $atts = array() ) {
	$html = '';

	$atts = shortcode_atts(
		array(
			'thumbnail_size' => 'large',
			'output'         => true,
		),
		$atts
	);

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$post = get_post( $post_id );

	if ( $post ) {
		$image = get_the_post_thumbnail( $post_id, $atts['thumbnail_size'] );
		$link  = get_permalink( $post_id );
		$title = $post->post_title;
		$date  = get_the_date( get_option( 'date_format' ), $post_id );

		$html .= '<article id="article-' . esc_attr( $post_id ) . '">';

		if ( ! empty( $image ) ) {
			if ( ! is_single() ) {
				$html .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . wp_kses_post( $image ) . '</a>';
			} else {
				if ( get_post_type( $post_id ) === 'post' ) {
					$html .= wp_kses_post( $image );
				}
			}
		}

		if ( ! is_single() ) {
			$html .= '<h2><a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $title ) . '</a></h2>';
		}

		if ( get_post_type( $post_id ) === 'post' ) {
			$html .= '<div class="toolbar">';
			$html .= '<p> ' . esc_html( $date ) . '</p>';
			$html .= '</div>';
		}

		if ( is_singular( array( 'post' ) ) ) {
			$html .= '<div class="content">';

			$html .= wp_kses_post( apply_filters( 'the_content', $post->post_content ) );

			$html .= '</div>';
			$html .= '<div class="tags">';

			the_tags( '', '' );
			$html .= '</div>';
		} else {
			if ( $post->post_excerpt ) {
				$html .= '<div class="content">';

				$html .= polinaholdbrook_print_excerpt( $post, 100, false );

				$html .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '" class="read-more">';
				$html .= esc_html__( 'More', 'polinaholdbrook' ) . '</a>';
				$html .= '</div>';
			} elseif ( $post->post_content ) {
				$html .= '<div class="content">';

				$html .= wp_kses_post( apply_filters( 'the_content', $post->post_content ) );

				$html .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '" class="read-more">';
				$html .= esc_html__( 'More', 'polinaholdbrook' ) . '</a>';
				$html .= '</div>';
			}
		}

		$html .= '</article>';
	}

	if ( $atts['output'] ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}
}

/**
 * Prints current post excerpt.
 *
 * @param WP_Post $post      Post object.
 * @param int     $num_words Optional. Maximum number of words to return.
 *                           Default 100.
 * @param boolean $output    Optional. Whether to output the content or return
 *                           it. Default true.
 *
 * @return void|string Post excerpt if $output is true.
 */
function polinaholdbrook_print_excerpt( $post, $num_words = 100, $output = true ) {
	if ( $output ) {
		echo esc_html( polinaholdbrook_get_excerpt( $post, $num_words ) );
	} else {
		return esc_html( polinaholdbrook_get_excerpt( $post, $num_words ) );
	}
}
