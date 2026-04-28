<?php
/**
 * XML 站点地图
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 添加站点地图重写规则
 */
add_action( 'init', 'guangwei_shishu_sitemap_rewrite_rule' );

function guangwei_shishu_sitemap_rewrite_rule() {
	add_rewrite_rule( 'sitemap\.xml$', 'index.php?guangwei_sitemap=1', 'top' );
	add_rewrite_tag( '%guangwei_sitemap%', '([0-9]+)' );
}

/**
 * 生成站点地图
 */
add_action( 'template_redirect', 'guangwei_shishu_generate_sitemap' );

function guangwei_shishu_generate_sitemap() {
	if ( get_query_var( 'guangwei_sitemap' ) != 1 ) {
		return;
	}
	
	// 设置HTTP头
	header( 'Content-Type: application/xml; charset=UTF-8' );
	header( 'X-Robots-Tag: noindex, follow', true );
	
	// 输出XML
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<?xml-stylesheet type="text/xsl" href="' . esc_url( get_template_directory_uri() . '/assets/css/sitemap.xsl' ) . '"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	
	// 首页
	echo guangwei_shishu_sitemap_url( home_url( '/' ), '1.0', 'daily' );
	
	// 文章
	$posts = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'modified',
		'order'          => 'DESC',
	) );
	
	foreach ( $posts as $post ) {
		$priority = '0.8';
		$changefreq = 'weekly';
		
		// 根据文章日期调整优先级
		$post_date = strtotime( $post->post_date );
		$days_old = ( time() - $post_date ) / DAY_IN_SECONDS;
		
		if ( $days_old < 7 ) {
			$priority = '0.9';
			$changefreq = 'daily';
		} elseif ( $days_old > 365 ) {
			$priority = '0.6';
			$changefreq = 'monthly';
		}
		
		echo guangwei_shishu_sitemap_url(
			get_permalink( $post->ID ),
			$priority,
			$changefreq,
			get_post_modified_time( 'c', false, $post )
		);
	}
	
	// 页面
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );
	
	foreach ( $pages as $page ) {
		echo guangwei_shishu_sitemap_url(
			get_permalink( $page->ID ),
			'0.7',
			'monthly',
			get_post_modified_time( 'c', false, $page )
		);
	}
	
	// 分类
	$categories = get_categories( array( 'hide_empty' => true ) );
	foreach ( $categories as $category ) {
		echo guangwei_shishu_sitemap_url(
			get_category_link( $category->term_id ),
			'0.6',
			'weekly'
		);
	}
	
	// 标签
	$tags = get_tags( array( 'hide_empty' => true, 'number' => 100 ) );
	foreach ( $tags as $tag ) {
		echo guangwei_shishu_sitemap_url(
			get_tag_link( $tag->term_id ),
			'0.5',
			'weekly'
		);
	}
	
	// 作者页
	$authors = get_users( array( 'who' => 'authors' ) );
	foreach ( $authors as $author ) {
		$post_count = count_user_posts( $author->ID );
		if ( $post_count > 0 ) {
			echo guangwei_shishu_sitemap_url(
				get_author_posts_url( $author->ID ),
				'0.5',
				'weekly'
			);
		}
	}
	
	echo '</urlset>';
	exit;
}

/**
 * 生成单个URL条目
 */
function guangwei_shishu_sitemap_url( $url, $priority = '0.5', $changefreq = 'weekly', $lastmod = '' ) {
	$output = "\t<url>\n";
	$output .= "\t\t<loc>" . esc_url( $url ) . "</loc>\n";
	
	if ( ! empty( $lastmod ) ) {
		$output .= "\t\t<lastmod>" . esc_html( $lastmod ) . "</lastmod>\n";
	}
	
	$output .= "\t\t<changefreq>" . esc_html( $changefreq ) . "</changefreq>\n";
	$output .= "\t\t<priority>" . esc_html( $priority ) . "</priority>\n";
	$output .= "\t</url>\n";
	
	return $output;
}

/**
 * 在robots.txt中添加站点地图
 */
add_filter( 'robots_txt', 'guangwei_shishu_add_sitemap_to_robots', 10, 2 );

function guangwei_shishu_add_sitemap_to_robots( $output, $public ) {
	if ( $public ) {
		$output .= "Sitemap: " . home_url( '/sitemap.xml' ) . "\n";
	}
	return $output;
}

/**
 * 在head中添加站点地图链接
 */
add_action( 'wp_head', 'guangwei_shishu_add_sitemap_link', 1 );

function guangwei_shishu_add_sitemap_link() {
	echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url( home_url( '/sitemap.xml' ) ) . '" />' . "\n";
}

/**
 * 文章更新时自动刷新站点地图（通过更新选项触发）
 */
add_action( 'save_post', 'guangwei_shishu_ping_search_engines', 10, 3 );

function guangwei_shishu_ping_search_engines( $post_id, $post, $update ) {
	// 只处理已发布的文章
	if ( $post->post_status !== 'publish' ) {
		return;
	}
	
	// 避免自动保存
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// 记录最后更新时间
	update_option( 'guangwei_shishu_sitemap_lastmod', time() );
	
	// 每小时最多ping一次
	$last_ping = get_option( 'guangwei_shishu_last_ping', 0 );
	if ( time() - $last_ping < HOUR_IN_SECONDS ) {
		return;
	}
	
	// Ping搜索引擎（国内服务器可注释掉以下代码）
	$sitemap_url = urlencode( home_url( '/sitemap.xml' ) );
	
	// Google - 国内访问可能受限
	// wp_remote_get( 'https://www.google.com/ping?sitemap=' . $sitemap_url, array( 'timeout' => 10 ) );
	
	// Bing - 国内可访问
	wp_remote_get( 'https://www.bing.com/ping?sitemap=' . $sitemap_url, array( 'timeout' => 10 ) );
	
	// 百度 Ping（国内主要搜索引擎）
	wp_remote_post( 'http://ping.baidu.com/ping/RPC2', array(
		'timeout' => 10,
		'body'    => '<?xml version="1.0" encoding="UTF-8"?><methodCall><methodName>weblogUpdates.extendedPing</methodName><params><param><value>' . esc_html( get_bloginfo( 'name' ) ) . '</value></param><param><value>' . esc_url( home_url( '/' ) ) . '</value></param><param><value>' . esc_url( home_url( '/sitemap.xml' ) ) . '</value></param></params></methodCall>',
		'headers' => array( 'Content-Type' => 'text/xml' ),
	) );
	
	update_option( 'guangwei_shishu_last_ping', time() );
}
