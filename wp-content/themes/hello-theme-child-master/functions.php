<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);
	wp_enqueue_style('slick-css', get_stylesheet_directory_uri() . '/libs/slick/slick.css');
	wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/libs/slick/slick.min.js');
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

function child_pages_menu_shortcode()
{
	$page_id = get_the_ID();
	$query = new WP_Query();
	$all_wp_pages = $query->query([
		'post_type' => 'page',
		'posts_per_page' => -1,
	]);
	$child_pages = get_page_children($page_id, $all_wp_pages);
	$children = get_pages(array('child_of' => $page_id));

	if (is_page() && count($children) > 0) :
?>
		<div class="game-slot">
			<div class="game-slot__text">
				<?php echo __('Slot', 'hello-elementor-child') ?>
				<div class="game-slot__menu">
					<ul>
						<?php foreach ($child_pages as $page) : ?>
							<li>
								<a href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php
	endif;
}

add_shortcode('child_pages_menu', 'child_pages_menu_shortcode');

function child_pages_banner_shortcode()
{
	$page_id = get_the_ID();
	$query = new WP_Query();
	$all_wp_pages = $query->query([
		'post_type' => 'page',
		'posts_per_page' => -1,
	]);
	$parent = wp_get_post_parent_id(get_the_ID());

	if (!empty($parent)) {
		$child_pages = get_page_children($parent, $all_wp_pages);
	} else {
		$child_pages = get_page_children($page_id, $all_wp_pages);
	}
	?>
	<style>
		.child-pages-slider .slick-arrow {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			z-index: 10;
			background-color: #fff;
			color: #000;
			border-color: #000;
		}

		.child-pages-slider .slick-arrow:hover {
			color: #000;
		}

		.child-pages-slider .slick-prev {
			left: 5px;
		}

		.child-pages-slider .slick-next {
			right: 5px;
		}

		.child-pages-slider__slide {
			display: block;
			height: 350px;
			padding: 20px;
		}

		.child-pages-slider__title {
			font-weight: bold;
			font-size: 32px;
		}

		.child-pages-slider__excerpt {
			font-weight: bold;
			font-size: 24px;
		}

		.child-pages-slider .slick-dots {
			display: flex;
			gap: 5px;
			list-style-type: none;
			margin: 0;
			padding: 0;
			position: absolute;
			left: 50%;
			transform: translateX(-50%);
			bottom: 10px;
		}

		.child-pages-slider .slick-dots li button {
			display: none;
		}

		.child-pages-slider .slick-dots li {
			width: 12px;
			height: 12px;
			border-radius: 100px;
			background-color: #777777;
		}

		.child-pages-slider .slick-dots li.slick-active {
			background-color: #222222;
		}

		.child-pages-slider {
			border-radius: 20px;
			overflow: hidden;
		}
	</style>
	<div class="child-pages-slider">
		<?php foreach ($child_pages as $page) : ?>
			<a href="<?php echo get_permalink($page->ID) ?>" class="child-pages-slider__slide" style="background-image: url(<?php echo get_the_post_thumbnail_url($page) ?>);">
				<?php if (!empty($page->post_title)) : ?>
					<p class="child-pages-slider__title"><?php echo $page->post_title; ?></p>
				<?php
				endif;
				if (!empty($page->post_excerpt)) :
				?>
					<p class="child-pages-slider__excerpt"><?php echo $page->post_excerpt; ?></p>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
	<script>
		jQuery(document).ready(function() {
			jQuery(".child-pages-slider").slick({
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				dots: true,
				prevArrow: '<button type="button" class="slick-prev"><</button>',
				nextArrow: '<button type="button" class="slick-next">></button>',
			})
		})
	</script>
<?php
}

add_shortcode('child_pages_banner', 'child_pages_banner_shortcode');
