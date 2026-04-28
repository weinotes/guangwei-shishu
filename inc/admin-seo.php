<?php
/**
 * SEO 设置页面
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 添加 SEO 设置菜单
 */
add_action( 'admin_menu', 'guangwei_shishu_seo_menu' );

function guangwei_shishu_seo_menu() {
	add_options_page(
		__( 'SEO 设置', 'guangwei-shishu' ),
		__( 'SEO 设置', 'guangwei-shishu' ),
		'manage_options',
		'guangwei-shishu-seo',
		'guangwei_shishu_seo_page'
	);
}

/**
 * SEO 设置页面内容
 */
function guangwei_shishu_seo_page() {
	// 保存设置
	if ( isset( $_POST['guangwei_shishu_seo_save'] ) && check_admin_referer( 'guangwei_shishu_seo_nonce' ) ) {
		update_option( 'guangwei_shishu_home_title', sanitize_text_field( $_POST['home_title'] ) );
		update_option( 'guangwei_shishu_home_description', sanitize_textarea_field( $_POST['home_description'] ) );
		update_option( 'guangwei_shishu_home_keywords', sanitize_text_field( $_POST['home_keywords'] ) );
		update_option( 'guangwei_shishu_author_name', sanitize_text_field( $_POST['author_name'] ) );
		update_option( 'guangwei_shishu_author_bio', sanitize_textarea_field( $_POST['author_bio'] ) );
		echo '<div class="notice notice-success"><p>' . __( '设置已保存', 'guangwei-shishu' ) . '</p></div>';
	}

	// 获取当前设置
	$home_title = get_option( 'guangwei_shishu_home_title', '' );
	$home_description = get_option( 'guangwei_shishu_home_description', '' );
	$home_keywords = get_option( 'guangwei_shishu_home_keywords', '' );
	$author_name = get_option( 'guangwei_shishu_author_name', '王光卫' );
	$author_bio = get_option( 'guangwei_shishu_author_bio', '' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'guangwei_shishu_seo_nonce' ); ?>
			
			<table class="form-table">
				<tr>
					<th scope="row"><label for="home_title"><?php _e( '首页标题', 'guangwei-shishu' ); ?></label></th>
					<td>
						<input type="text" name="home_title" id="home_title" value="<?php echo esc_attr( $home_title ); ?>" class="regular-text" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<p class="description"><?php _e( '留空则使用站点标题', 'guangwei-shishu' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="home_description"><?php _e( '首页描述 (Meta Description)', 'guangwei-shishu' ); ?></label></th>
					<td>
						<textarea name="home_description" id="home_description" rows="4" class="large-text" placeholder="王光卫原创诗词书法作品集。以笔墨抒怀，以诗词言志，传承中华传统文化精髓，分享诗意人生感悟。"><?php echo esc_textarea( $home_description ); ?></textarea>
						<p class="description"><?php _e( '建议 150 字以内，用于搜索引擎展示', 'guangwei-shishu' ); ?> (<span id="desc-count">0</span> <?php _e( '字', 'guangwei-shishu' ); ?>)</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="home_keywords"><?php _e( '首页关键词 (Meta Keywords)', 'guangwei-shishu' ); ?></label></th>
					<td>
						<input type="text" name="home_keywords" id="home_keywords" value="<?php echo esc_attr( $home_keywords ); ?>" class="regular-text" placeholder="王光卫,诗词,书法,原创,传统文化">
						<p class="description"><?php _e( '多个关键词用英文逗号分隔', 'guangwei-shishu' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="author_name"><?php _e( '作者名称', 'guangwei-shishu' ); ?></label></th>
					<td>
						<input type="text" name="author_name" id="author_name" value="<?php echo esc_attr( $author_name ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="author_bio"><?php _e( '作者简介', 'guangwei-shishu' ); ?></label></th>
					<td>
						<textarea name="author_bio" id="author_bio" rows="4" class="large-text" placeholder="诗词书法爱好者，以笔墨记录生活，以文字传递情感。"><?php echo esc_textarea( $author_bio ); ?></textarea>
						<p class="description"><?php _e( '用于作者页面和结构化数据', 'guangwei-shishu' ); ?></p>
					</td>
				</tr>
			</table>
			
			<?php submit_button( __( '保存设置', 'guangwei-shishu' ), 'primary', 'guangwei_shishu_seo_save' ); ?>
		</form>
		
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			var descField = document.getElementById('home_description');
			var countSpan = document.getElementById('desc-count');
			
			function updateCount() {
				countSpan.textContent = descField.value.length;
			}
			
			descField.addEventListener('input', updateCount);
			updateCount();
		});
		</script>
	</div>
	<?php
}

/**
 * 获取首页 SEO 标题
 */
function guangwei_shishu_get_home_title() {
	$custom_title = get_option( 'guangwei_shishu_home_title' );
	return $custom_title ? $custom_title : get_bloginfo( 'name' );
}

/**
 * 获取首页 SEO 描述
 */
function guangwei_shishu_get_home_description() {
	$custom_desc = get_option( 'guangwei_shishu_home_description' );
	return $custom_desc ? $custom_desc : get_bloginfo( 'description' );
}

/**
 * 获取首页 SEO 关键词
 */
function guangwei_shishu_get_home_keywords() {
	return get_option( 'guangwei_shishu_home_keywords', '王光卫,诗词,书法,原创,传统文化' );
}

/**
 * 获取作者名称
 */
function guangwei_shishu_get_author_name() {
	return get_option( 'guangwei_shishu_author_name', '王光卫' );
}

/**
 * 获取作者简介
 */
function guangwei_shishu_get_author_bio() {
	return get_option( 'guangwei_shishu_author_bio', '' );
}
