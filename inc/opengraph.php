<?php
/**
 * Open Graph / Twitter Card 元标签
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 输出 Open Graph 和 Twitter Card 元标签
 */
add_action( 'wp_head', 'guangwei_shishu_output_og_tags', 5 );

function guangwei_shishu_output_og_tags() {
	// 基本标签
	$og_tags = array(
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:locale'      => get_locale(),
		'og:type'        => 'website',
		'twitter:card'   => 'summary_large_image',
	);
	
	if ( is_singular() ) {
		// 文章页
		$og_tags['og:title'] = get_the_title();
		$og_tags['og:description'] = guangwei_shishu_get_og_description();
		$og_tags['og:url'] = get_permalink();
		$og_tags['og:type'] = 'article';
		$og_tags['article:published_time'] = get_the_date( 'c' );
		$og_tags['article:modified_time'] = get_the_modified_date( 'c' );
		$og_tags['article:author'] = get_the_author();
		
		// 文章图片
		$image_url = guangwei_shishu_get_og_image();
		if ( $image_url ) {
			$og_tags['og:image'] = $image_url;
			$og_tags['og:image:width'] = '1200';
			$og_tags['og:image:height'] = '630';
		}
		
		// 分类
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$og_tags['article:section'] = $categories[0]->name;
		}
		
		// 标签
		$tags = get_the_tags();
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$og_tags['article:tag'][] = $tag->name;
			}
		}
		
		// Twitter
		$og_tags['twitter:title'] = get_the_title();
		$og_tags['twitter:description'] = $og_tags['og:description'];
		if ( isset( $og_tags['og:image'] ) ) {
			$og_tags['twitter:image'] = $og_tags['og:image'];
		}
		
	} elseif ( is_category() || is_tag() || is_tax() ) {
		// 分类/标签页
		$og_tags['og:title'] = single_term_title( '', false );
		$og_tags['og:description'] = wp_strip_all_tags( term_description() );
		$og_tags['og:url'] = get_term_link( get_queried_object() );
		
	} elseif ( is_author() ) {
		// 作者页
		$og_tags['og:title'] = get_the_author();
		$og_tags['og:description'] = get_the_author_meta( 'description' );
		$og_tags['og:url'] = get_author_posts_url( get_the_author_meta( 'ID' ) );
		
	} else {
		// 首页/其他页面
		$og_tags['og:title'] = guangwei_shishu_get_home_title();
		$og_tags['og:description'] = guangwei_shishu_get_home_description();
		$og_tags['og:url'] = home_url( '/' );
		
		// 默认图片
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo_url ) {
				$og_tags['og:image'] = $logo_url;
			}
		}
	}
	
	// 输出元标签
	echo "\n<!-- Open Graph / Twitter Card -->\n";
	foreach ( $og_tags as $property => $content ) {
		if ( is_array( $content ) ) {
			foreach ( $content as $item ) {
				echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $item ) . '" />' . "\n";
			}
		} else {
			$attr = ( strpos( $property, 'twitter:' ) === 0 ) ? 'name' : 'property';
			echo '<meta ' . esc_attr( $attr ) . '="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
		}
	}
}

/**
 * 获取 OG 描述
 */
function guangwei_shishu_get_og_description() {
	$description = '';
	
	if ( has_excerpt() ) {
		$description = get_the_excerpt();
	} else {
		$content = wp_strip_all_tags( get_the_content() );
		$description = mb_substr( $content, 0, 200, 'UTF-8' );
	}
	
	return $description;
}

/**
 * 获取 OG 图片
 */
function guangwei_shishu_get_og_image() {
	$image_url = '';
	
	// 优先使用特色图片
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( null, 'full' );
	}
	
	// 从内容中提取第一张图片
	if ( empty( $image_url ) ) {
		$content = get_the_content();
		if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches ) ) {
			$image_url = $matches[1];
		}
	}
	
	return $image_url;
}

/**
 * 添加 meta description
 */
add_action( 'wp_head', 'guangwei_shishu_output_meta_description', 1 );

function guangwei_shishu_output_meta_description() {
	$description = '';
	
	if ( is_front_page() || is_home() ) {
		// 首页使用自定义 SEO 描述
		$description = guangwei_shishu_get_home_description();
	} else {
		$description = guangwei_shishu_get_meta_description();
	}
	
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
}

/**
 * 添加 meta keywords
 */
add_action( 'wp_head', 'guangwei_shishu_output_meta_keywords', 1 );

function guangwei_shishu_output_meta_keywords() {
	$keywords = array();
	
	if ( is_front_page() || is_home() ) {
		// 首页使用自定义 SEO 关键词
		$home_keywords = guangwei_shishu_get_home_keywords();
		$keywords = array_map( 'trim', explode( ',', $home_keywords ) );
	} elseif ( is_singular() ) {
		// 从分类获取
		$categories = get_the_category();
		foreach ( $categories as $category ) {
			$keywords[] = $category->name;
		}
		
		// 从标签获取
		$tags = get_the_tags();
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$keywords[] = $tag->name;
			}
		}
		
		// 从标题提取关键词
		$title = get_the_title();
		if ( preg_match( '/(定风波|蝶恋花|鹧鸪天|七绝|五律|七律|词|诗)/', $title, $matches ) ) {
			$keywords[] = $matches[1];
		}
	}
	
	// 默认关键词
	if ( empty( $keywords ) ) {
		$keywords = array( '诗词', '书法', '王光卫', '原创诗词', '中国传统' );
	}
	
	$keywords = array_unique( array_filter( $keywords ) );
	
	if ( ! empty( $keywords ) ) {
		echo '<meta name="keywords" content="' . esc_attr( implode( ', ', $keywords ) ) . '" />' . "\n";
	}
}

/**
 * 添加 robots meta
 */
add_action( 'wp_head', 'guangwei_shishu_output_robots_meta', 1 );

function guangwei_shishu_output_robots_meta() {
	$robots = array( 'index', 'follow' );
	
	if ( is_search() || is_404() || is_attachment() ) {
		$robots = array( 'noindex', 'follow' );
	}
	
	echo '<meta name="robots" content="' . esc_attr( implode( ', ', $robots ) ) . '" />' . "\n";
}

/**
 * 添加 canonical 链接
 */
add_action( 'wp_head', 'guangwei_shishu_output_canonical', 1 );

function guangwei_shishu_output_canonical() {
	$canonical = '';
	
	if ( is_singular() ) {
		$canonical = get_permalink();
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$canonical = get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$canonical = get_author_posts_url( get_the_author_meta( 'ID' ) );
	} elseif ( is_front_page() ) {
		$canonical = home_url( '/' );
	}
	
	if ( ! empty( $canonical ) && ! is_wp_error( $canonical ) ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
	}
}
