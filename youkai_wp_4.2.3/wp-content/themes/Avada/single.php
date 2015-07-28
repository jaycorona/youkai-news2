<?php
function single_css() {
?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/single.css" type="text/css">
<?php
}
add_filter('wp_head', 'single_css');
?>
<?php get_header(); ?>
	<?php
	if(get_post_meta($post->ID, 'pyre_full_width', true) == 'yes') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
	}
	elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'default') {
		if($data['default_sidebar_pos'] == 'Left') {
			$content_css = 'float:right;';
			$sidebar_css = 'float:left;';
		} elseif($data['default_sidebar_pos'] == 'Right') {
			$content_css = 'float:left;';
			$sidebar_css = 'float:right;';
		}
	}
	?>
	<!-- facebook sdk -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- facebook sdk -->
	<!-- twitter sdk -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<!-- twitter sdk -->
	<div id="content" style="<?php echo $content_css; ?>">
		<?php while (have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
			<?php
			global $data;
			?>
			<div class="center single_top_pad">
				<div class="cat_img">
					<div class="left">
						<?php show_category_icon(); ?>
					</div>
					<div class="rght_date">
						<div class="social_but">
							<?php
							$sns_fb = get_field('sns_fb');
							if ($sns_fb == 1) {?>
								<div class="fb_con">
								<div class="fb-like" data-href="<?php echo the_permalink();?>" data-width="55" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
								</div>
							<?php } ?>
							<?php $sns_tw = get_field('sns_tw');
							if ($sns_tw == 1) {?>
								<div class="tweet_con">
								<a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="horizontal" data-hashtags="yokaiworld">ツイート</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
								</div>
							<?php } ?>
							<?php 
							$sns_li = get_field('sns_li');
							if ($sns_li == 1) { ?>
								<div class="line_con">
								<span style = "vertical-align: bottom; width: 98px;height: 31px;">
									<script type="text/javascript" src="//media.line.naver.jp/js/line-button.js?v=20131101" ></script>
									<script type="text/javascript">
										new jp.naver.line.media.LineButton({"pc":true,"lang":"ja","type":"a"});
									</script>
								</span>
								</div>
							<?php } ?>
						</div>
						<p class="single-line-meta"><?php //if(!$data['post_meta_date']): ?><?php the_date('Y.m.d'); ?><?php //endif; ?></p>
					</div>
				</div>
				<?php if($data['blog_post_title']): ?>
					<?php $post_type = get_custom_field('youkai_post_type');?>
				<div class="<?php echo "single_post_border_".$post_type;?>">
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php endif; ?>
				<div class="post-content">
					<p id="youkai_content_2">
						<?php echo the_field('youkai_content_2'); ?>
					</p>
				</div>
				</div>
			</div>
			
		</div>
		<?php endwhile; ?>
		<?php if ( is_archive()){ themefusion_pagination($pages = '', $range = 2);}?>
	</div>
	

	
	<?php show_youkai_side_bar(false); ?>
	
<?php get_footer(); ?>