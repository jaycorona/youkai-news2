<?php
function index_css() {
?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/index_style.css" type="text/css">
<?php
}
add_filter('wp_head', 'index_css');
?>

<?php 
$cat_slug = ($_REQUEST['cat_slug']) ? $_REQUEST['cat_slug'] : '';
?>

<?php get_header(); ?>
	<?php
	if($data['blog_full_width']) {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
	} elseif($data['blog_sidebar_position'] == 'Left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif($data['blog_sidebar_position'] == 'Right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	}

	$container_class = '';
	if($data['blog_layout'] == 'Large Alternate') {
		$post_class = 'large-alternate';
	} elseif($data['blog_layout'] == 'Medium Alternate') {
		$post_class = 'medium-alternate';
	} elseif($data['blog_layout'] == 'Grid') {
		$post_class = 'grid-post';
		$container_class = 'grid-layout';
		if($data['blog_full_width']) {
			$container_class = 'grid-layout grid-full-layout';
		}
	} elseif($data['blog_layout'] == 'Timeline') {
		$post_class = 'timeline-post';
		$container_class = 'timeline-layout';
		if(!$data['blog_full_width']) {
			$container_class = 'timeline-layout timeline-sidebar-layout';
		}
	}
	?>
	<div id="content" >
		<?php if($data['blog_layout'] == 'Timeline'): ?>
		<div class="timeline-icon"><i class="icon-comments-alt"></i></div>
		<?php endif; ?>
		
		
		<div id="posts-container">
		
			<!-- public posts  -->
			<!---
			<div class="<?php echo $container_class; ?>" id="public_site_container" >
					<img class="post_head_image" src="<?php bloginfo('template_url'); ?>/images/img/home/youkai_world_post_head1.png" alt="公式サイト更新" />
				<div id="public_site_block"></div>
				
				<div id="show_more_public">
					<img id="show_more_public_img" src="<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header.png" alt="妖怪ワールドの記事をもっと読む" onmouseover="this.src='<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header_hover.png';" onmouseout="this.src='<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header.png';" />
					<div id="loadingleft" style="display: none;">
						<img src="<?php bloginfo('template_url'); ?>/images/img/home/loading.gif" alt="loading" />
					</div>
				</div>
				<br />
			</div> ---->
			
			
			<!-- youkai world posts  -->
			<div class="<?php echo $container_class; ?>" id="youkai_world_container" >
					<img class="post_head_image"  src="<?php bloginfo('template_url'); ?>/images/img/home/youkai_world_post_head2.png" alt="妖怪ワールド更新" />
				<div id="youkai_world_block"></div>
				
				<div id="show_more_youkai">
					<img id="show_more_youkai_img" src="<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header.png" alt="妖怪ワールドの記事をもっと読む" onmouseover="this.src='<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header_hover.png';" onmouseout="this.src='<?php bloginfo('template_url'); ?>/images/img/home/lower_green_header.png';" />
					<div id="loadingright" style="display: none;">
						<img src="<?php bloginfo('template_url'); ?>/images/img/home/loading.gif" alt="loading" />
					</div>
				</div>
				<br />
			</div>
			
		</div>
		
		
		<div id="loading">
			<!-- <img src="<?php bloginfo('template_url'); ?>/images/img/home/loading.gif" alt="loading" /> -->
		</div>
		
	</div>
	<?php wp_reset_query(); ?>
	
	<?php if(is_front_page()) : ?>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
		<div id="sticker">
			<?php
				if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog Sidebar')):
				endif;
			?>
		</div>
	</div>
	<?php else: ?>
		<?php show_youkai_side_bar(true); ?>
	<?php endif; ?>
<?php get_footer(); ?>

<script type="text/javascript">

var POST_TYPE_PUBLIC_SITE  = 1;
var POST_TYPE_YOUKAI_WORLD = 2;
var YOUKAI_GET_LIMIT = 7;

var MORE_FLAG = {};
var OFFSET_LIST = {};

var show_loading_count = 0;


var category_name = '<?php echo $cat_slug ?>';


jQuery(window).load(function(){

	init();
	
	//
	// STICKER SIDE BAR
	//
	//jQuery("#sticker").sticky({ topSpacing:90, bottomSpacing:180 });
	//
	// get post
	//
	load_init();

	show_loading_count ++;
	show_youkai_post_list('#public_site_block', POST_TYPE_PUBLIC_SITE);

	show_loading_count ++;
	show_youkai_post_list('#youkai_world_block', POST_TYPE_YOUKAI_WORLD);
	
	
	// when click left
	jQuery('#show_more_public').click(function(){
	if (MORE_FLAG[POST_TYPE_PUBLIC_SITE]){
		load_start('public');
		
		show_loading_count ++;
		show_youkai_post_list('#public_site_block', POST_TYPE_PUBLIC_SITE);
	}else{
		alert('no more post');
	}
	});

	// when click right
	jQuery('#show_more_youkai').click(function(){
	if (MORE_FLAG[POST_TYPE_YOUKAI_WORLD]){
		load_start('yukai');
		
		show_loading_count ++;
		show_youkai_post_list('#youkai_world_block', POST_TYPE_YOUKAI_WORLD);
	}else{
		alert('no more post');
	}
	});
	
	
});


function init() {
	MORE_FLAG[POST_TYPE_PUBLIC_SITE] = true;
	MORE_FLAG[POST_TYPE_YOUKAI_WORLD] = true;

	OFFSET_LIST[POST_TYPE_PUBLIC_SITE] = 0;
	OFFSET_LIST[POST_TYPE_YOUKAI_WORLD] = 0;
}


function show_youkai_post_list(result_target, type) {
	if (MORE_FLAG[type] == false) { return; }
	
	var senddata =  {
		action   : "get_youkai_post_list",
		youkai_post_type : type,
		offset   : OFFSET_LIST[type],
		limit    : YOUKAI_GET_LIMIT,
	};

	if (category_name) {
		senddata['category_name'] = category_name;
	}

	OFFSET_LIST[type] += YOUKAI_GET_LIMIT;
	
	var receive_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	jQuery.get(receive_url, senddata,
		function(data){
			
			if ( data == 'NO_DATA') {
				data = '<p><ruby><rb>最後</rb><rp>(</rp><rt>さいご</rt><rp>)</rp></ruby>の<ruby><rb>記事</rb><rp>(</rp><rt>きじ</rt><rp>)</rp></ruby>です。</p>';
				MORE_FLAG[type] = false;
			}
			jQuery(result_target).append(data);

			show_loading_count --;

			if (show_loading_count <= 0) {
				load_end();
			}
		}
	);
}

function load_init(){
	jQuery('#loading').hide();
	jQuery('#loadingleft').show();
	jQuery('#loadingright').show();
}
function load_start(type) {
	if(type == 'public'){
		jQuery('#loadingleft').show();
		jQuery('#show_more_public_img').hide();
	}
	if(type == 'yukai'){
		jQuery('#loadingright').show();
		jQuery('#show_more_youkai_img').hide();
	}
	jQuery('#show_more').hide();
}
function load_end() {
	jQuery('#loading').hide();
	jQuery('#loadingleft').hide();
	jQuery('#loadingright').hide();
	jQuery('#show_more').show();
	if(MORE_FLAG[POST_TYPE_PUBLIC_SITE]){
		jQuery('#show_more_public_img').show();
	}
	if(MORE_FLAG[POST_TYPE_YOUKAI_WORLD]){
		jQuery('#show_more_youkai_img').show();
	}
	
	show_loading_count = 0;
}

</script>