<?php
/**
 * 光卫诗书主题功能文件
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 * @link https://www.guangweiblog.com
 * @link https://github.com/weinotes/guangwei-shishu
 */

// 阻止直接访问
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 主题版本
 */
define( 'GUANGWEI_SHISHU_VERSION', '1.5.0' );

/**
 * 加载 SEO 优化文件
 */
require_once get_template_directory() . '/inc/schema.php';
require_once get_template_directory() . '/inc/opengraph.php';
require_once get_template_directory() . '/inc/sitemap.php';
require_once get_template_directory() . '/inc/admin-seo.php';

/**
 * 主题设置
 */
add_action(
	'after_setup_theme',
	function () {
		// 加载文本域
		load_theme_textdomain( 'guangwei-shishu', get_template_directory() . '/languages' );

		// 添加主题支持
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'custom-line-height' );
		add_theme_support( 'custom-spacing' );
		add_theme_support( 'custom-units', array( 'px', 'em', 'rem', 'vh', 'vw', '%' ) );

		// 自定义 Logo 支持
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 120,
				'width'       => 120,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		// 编辑器样式
		add_editor_style( 'style.css' );
	}
);

/**
 * 加载主题资源 - 完全本地化，无CDN依赖
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		// 主题样式（字体通过本地CSS @font-face加载）
		wp_enqueue_style(
			'guangwei-shishu-style',
			get_template_directory_uri() . '/style.css',
			array(),
			GUANGWEI_SHISHU_VERSION
		);

		// 主题脚本
		wp_enqueue_script(
			'guangwei-shishu-script',
			get_template_directory_uri() . '/assets/js/theme.js',
			array(),
			GUANGWEI_SHISHU_VERSION,
			true
		);
	}
);

/**
 * 编辑器样式 - 完全本地化
 */
add_action(
	'enqueue_block_editor_assets',
	function () {
		// 编辑器使用与前端相同的本地样式
		add_editor_style( 'style.css' );
	}
);

/**
 * 自定义登录页面样式 - 中国传统风格（完全本地化）
 */
add_action(
	'login_enqueue_scripts',
	function () {
		echo '<style>
			body.login {
				background: linear-gradient(135deg, #f7f3e9 0%, #e8e0d0 100%);
				font-family: "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "WenQuanYi Micro Hei", sans-serif;
			}
			.login h1 a {
				background-image: none;
				text-indent: 0;
				font-size: 28px;
				font-weight: 600;
				color: #2c2c2c;
				width: auto;
				height: auto;
				font-family: "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", sans-serif;
			}
			.login form {
				background: #ffffff;
				border: 1px solid #d4c8b8;
				border-radius: 8px;
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
			}
			.login label {
				color: #4a4a4a;
				font-weight: 500;
			}
			.login input[type="text"],
			.login input[type="password"] {
				background: #fafafa;
				border: 1px solid #d4c8b8;
				border-radius: 4px;
				color: #2c2c2c;
				padding: 8px 12px;
			}
			.login input[type="text"]:focus,
			.login input[type="password"]:focus {
				border-color: #3d5a80;
				box-shadow: 0 0 0 2px rgba(61, 90, 128, 0.2);
				outline: none;
			}
			.wp-core-ui .button-primary {
				background: #b83a52;
				border-color: #b83a52;
				color: #ffffff;
				font-weight: 500;
				border-radius: 4px;
				padding: 8px 20px;
				transition: all 0.3s ease;
			}
			.wp-core-ui .button-primary:hover {
				background: #c95a6e;
				border-color: #c95a6e;
				transform: translateY(-1px);
				box-shadow: 0 4px 12px rgba(184, 58, 82, 0.3);
			}
			.login #nav a,
			.login #backtoblog a {
				color: #3d5a80;
				transition: color 0.3s ease;
			}
			.login #nav a:hover,
			.login #backtoblog a:hover {
				color: #b83a52;
			}
			.login .message,
			.login .success {
				background: #ffffff;
				border-left: 4px solid #3d5a80;
			}
			.login #login_error {
				background: #ffffff;
				border-left: 4px solid #b83a52;
			}
		</style>';
	}
);

/**
 * 修改登录页面标题
 */
add_filter(
	'login_headertext',
	function () {
		return __( '王光卫诗书词', 'guangwei-shishu' );
	}
);

/**
 * 修改登录页面链接
 */
add_filter(
	'login_headerurl',
	function () {
		return home_url( '/' );
	}
);

/**
 * 移除分类和标签前缀
 */
add_action( 'init', 'guangwei_shishu_remove_taxonomy_prefix', 1 );
function guangwei_shishu_remove_taxonomy_prefix() {
	global $wp_rewrite;
	if ( $wp_rewrite ) {
		$wp_rewrite->set_category_base( '' );
		$wp_rewrite->set_tag_base( '' );
	}
}

/**
 * 移除分类链接前缀
 */
add_filter( 'category_link', 'guangwei_shishu_custom_category_link', 10, 2 );
function guangwei_shishu_custom_category_link( $link, $term_id ) {
	return str_replace( '/category/', '/', $link );
}

/**
 * 移除标签链接前缀
 */
add_filter( 'tag_link', 'guangwei_shishu_custom_tag_link', 10, 2 );
function guangwei_shishu_custom_tag_link( $link, $term_id ) {
	return str_replace( '/tag/', '/', $link );
}

/**
 * 添加自定义重写规则
 */
add_filter( 'generate_rewrite_rules', 'guangwei_shishu_add_custom_rewrite_rules' );
function guangwei_shishu_add_custom_rewrite_rules( $wp_rewrite ) {
	$new_rules = array();
	
	// 分类规则
	$categories = get_categories( array( 'hide_empty' => false ) );
	foreach ( $categories as $category ) {
		$slug = $category->slug;
		if ( $category->parent ) {
			$slug = get_category_parents( $category->parent, false, '/', true ) . $slug;
		}
		$new_rules[ $slug . '/?$' ] = 'index.php?category_name=' . $slug;
		$new_rules[ $slug . '/page/([0-9]+)/?$' ] = 'index.php?category_name=' . $slug . '&paged=$matches[1]';
	}
	
	// 标签规则
	$tags = get_tags( array( 'hide_empty' => false ) );
	foreach ( $tags as $tag ) {
		$slug = $tag->slug;
		if ( isset( $new_rules[ $slug . '/?$' ] ) ) {
			continue;
		}
		$new_rules[ $slug . '/?$' ] = 'index.php?tag=' . $slug;
		$new_rules[ $slug . '/page/([0-9]+)/?$' ] = 'index.php?tag=' . $slug . '&paged=$matches[1]';
	}
	
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	return $wp_rewrite;
}

/**
 * 刷新固定链接
 */
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

/**
 * 301重定向旧URL
 */
add_action( 'template_redirect', 'guangwei_shishu_redirect_old_urls' );
function guangwei_shishu_redirect_old_urls() {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	
	// /category/xxx 重定向
	if ( preg_match( '#^/category/([^/]+)/?(.*)$#', $request_uri, $matches ) ) {
		$category_slug = sanitize_text_field( $matches[1] );
		$category = get_category_by_slug( $category_slug );
		if ( $category ) {
			$redirect_url = get_category_link( $category->term_id );
			if ( $redirect_url && ! is_wp_error( $redirect_url ) ) {
				wp_redirect( $redirect_url, 301 );
				exit;
			}
		}
	}
	
	// /tag/xxx 重定向
	if ( preg_match( '#^/tag/([^/]+)/?(.*)$#', $request_uri, $matches ) ) {
		$tag_slug = sanitize_text_field( $matches[1] );
		$tag = get_term_by( 'slug', $tag_slug, 'post_tag' );
		if ( $tag ) {
			$redirect_url = get_tag_link( $tag->term_id );
			if ( $redirect_url && ! is_wp_error( $redirect_url ) ) {
				wp_redirect( $redirect_url, 301 );
				exit;
			}
		}
	}
}

/**
 * 为图片添加懒加载
 */
add_filter(
	'wp_get_attachment_image_attributes',
	function ( $attr, $attachment, $size ) {
		$attr['loading'] = 'lazy';
		$attr['decoding'] = 'async';
		return $attr;
	},
	10,
	3
);

/**
 * 自定义阅读时间
 */
function guangwei_shishu_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	
	$content = strip_tags( $post->post_content );
	$word_count = mb_strlen( $content, 'UTF-8' );
	$reading_time = ceil( $word_count / 300 );
	
	return sprintf(
		/* translators: %d: reading time in minutes */
		__( '约 %d 分钟阅读', 'guangwei-shishu' ),
		$reading_time
	);
}

/**
 * 自定义摘要长度
 */
add_filter(
	'excerpt_length',
	function () {
		return 120;
	}
);

add_filter(
	'excerpt_more',
	function () {
		return ' …';
	}
);

/**
 * 禁用WordPress默认画廊样式
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * 添加自定义区块样式
 */
add_action(
	'init',
	function () {
		// 诗词样式
		register_block_style(
			'core/group',
			array(
				'name'  => 'poetry-container',
				'label' => __( '诗词容器', 'guangwei-shishu' ),
			)
		);
		
		// 书法样式
		register_block_style(
			'core/image',
			array(
				'name'  => 'calligraphy-frame',
				'label' => __( '书法装裱', 'guangwei-shishu' ),
			)
		);
	}
);

/**
 * 自定义区块模式
 */
add_action(
	'init',
	function () {
		// 诗词排版模式
		register_block_pattern(
			'guangwei-shishu/poetry-layout',
			array(
				'title'       => __( '诗词排版', 'guangwei-shishu' ),
				'description' => __( '传统诗词排版样式', 'guangwei-shishu' ),
				'categories'  => array( 'text' ),
				'content'     => '<!-- wp:group {"className":"poetry-container","layout":{"type":"constrained"}} -->
				<div class="wp-block-group poetry-container">
				<!-- wp:heading {"textAlign":"center","level":1,"className":"poetry-title"} -->
				<h1 class="wp-block-heading has-text-align-center poetry-title">词牌名</h1>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","className":"poetry-meta"} -->
				<p class="has-text-align-center poetry-meta">文/作者</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"align":"center","className":"poetry-content"} -->
				<p class="has-text-align-center poetry-content">诗词正文</p>
				<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->',
			)
		);
	}
);

/**
 * 添加body类
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_singular( 'post' ) ) {
			$classes[] = 'single-poetry';
		}
		return $classes;
	}
);

/**
 * 自定义评论表单
 */
add_filter(
	'comment_form_defaults',
	function ( $defaults ) {
		$defaults['title_reply'] = __( '发表评论', 'guangwei-shishu' );
		$defaults['label_submit'] = __( '提交评论', 'guangwei-shishu' );
		$defaults['comment_notes_before'] = '<p class="comment-notes">' . __( '您的邮箱地址不会被公开。', 'guangwei-shishu' ) . '</p>';
		return $defaults;
	}
);

/**
 * 自定义搜索表单
 */
add_filter(
	'get_search_form',
	function ( $form ) {
		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<label>
				<span class="screen-reader-text">' . __( '搜索：', 'guangwei-shishu' ) . '</span>
				<input type="search" class="search-field" placeholder="' . esc_attr__( '搜索诗词…', 'guangwei-shishu' ) . '" value="' . get_search_query() . '" name="s" />
			</label>
			<button type="submit" class="search-submit">' . __( '搜索', 'guangwei-shishu' ) . '</button>
		</form>';
		return $form;
	}
);

/**
 * 添加页脚信息
 */
add_action(
	'wp_footer',
	function () {
		echo '<!-- 光卫诗书主题 v' . esc_html( GUANGWEI_SHISHU_VERSION ) . ' - https://github.com/weinotes/guangwei-shishu -->';
	},
	100
);
