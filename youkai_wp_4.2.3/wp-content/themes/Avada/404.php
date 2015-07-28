<?php
function _404_css() {
?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/404.css" type="text/css">
<?php
}
add_filter('wp_head', '_404_css');
?>
<?php get_header(); ?>
	<div id="content" class="full-width">
		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-content">
				<div class="title">
					<h2>ページがみつかりませんでした。</h2>
				</div>
				<div>
					<a href="<?php echo home_url();?>">トップへ戻る >> </a>
				</div>
			</div>
		</div>
	</div>