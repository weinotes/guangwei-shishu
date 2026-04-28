<?php
/**
 * Schema.org 结构化数据
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 输出 Schema.org 结构化数据
 */
add_action( 'wp_head', 'guangwei_shishu_output_schema', 1 );

function guangwei_shishu_output_schema() {
	$schema = array();
	
	if ( is_front_page() || is_home() ) {
		// 首页 - WebSite
		$schema[] = guangwei_shishu_get_website_schema();
		$schema[] = guangwei_shishu_get_organization_schema();
	} elseif ( is_singular( 'post' ) ) {
		// 文章页 - Article
		$schema[] = guangwei_shishu_get_article_schema();
	} elseif ( is_author() ) {
		// 作者页 - ProfilePage
		$schema[] = guangwei_shishu_get_profile_schema();
	} elseif ( is_category() || is_tag() || is_tax() ) {
		// 分类/标签页 - CollectionPage
		$schema[] = guangwei_shishu_get_collection_schema();
	}
	
	// 面包屑导航
	if ( ! is_front_page() ) {
		$schema[] = guangwei_shishu_get_breadcrumb_schema();
	}
	
	// 输出JSON-LD
	if ( ! empty( $schema ) ) {
		echo "\n<!-- Schema.org 结构化数据 -->\n";
		foreach ( $schema as $item ) {
			if ( ! empty( $item ) ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $item, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
			}
		}
	}
}

/**
 * 获取 WebSite Schema
 */
function guangwei_shishu_get_website_schema() {
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'WebSite',
		'name'        => guangwei_shishu_get_home_title(),
		'url'         => home_url( '/' ),
		'description' => guangwei_shishu_get_home_description(),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * 获取 Organization Schema
 */
function guangwei_shishu_get_organization_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => guangwei_shishu_get_home_title(),
		'url'      => home_url( '/' ),
		'description' => guangwei_shishu_get_home_description(),
	);
	
	// Logo
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		if ( $logo_url ) {
			$schema['logo'] = $logo_url;
		}
	}
	
	// 社交媒体链接
	$social_links = array();
	if ( defined( 'GUANGWEI_WEIBO_URL' ) ) {
		$social_links[] = GUANGWEI_WEIBO_URL;
	}
	if ( defined( 'GUANGWEI_GITHUB_URL' ) ) {
		$social_links[] = GUANGWEI_GITHUB_URL;
	}
	if ( ! empty( $social_links ) ) {
		$schema['sameAs'] = $social_links;
	}
	
	return $schema;
}

/**
 * 获取 Article Schema
 */
function guangwei_shishu_get_article_schema() {
	$post = get_post();
	if ( ! $post ) {
		return array();
	}
	
	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'description'      => guangwei_shishu_get_meta_description(),
		'url'              => get_permalink(),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
			'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => guangwei_shishu_get_home_title(),
			'url'   => home_url( '/' ),
		),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink(),
		),
	);
	
	// 文章图片
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( null, 'full' );
		if ( $image_url ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'url'    => $image_url,
				'width'  => 1200,
				'height' => 630,
			);
		}
	}
	
	// 文章分类
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		$schema['articleSection'] = $categories[0]->name;
	}
	
	// 关键词
	$tags = get_the_tags();
	if ( ! empty( $tags ) ) {
		$tag_names = wp_list_pluck( $tags, 'name' );
		$schema['keywords'] = implode( ', ', $tag_names );
	}
	
	return $schema;
}

/**
 * 获取 ProfilePage Schema
 */
function guangwei_shishu_get_profile_schema() {
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'ProfilePage',
		'mainEntity' => array(
			'@type' => 'Person',
			'name'  => guangwei_shishu_get_author_name(),
			'url'   => home_url( '/' ),
			'description' => guangwei_shishu_get_author_bio(),
		),
	);
}

/**
 * 获取 CollectionPage Schema
 */
function guangwei_shishu_get_collection_schema() {
	$title = '';
	$description = '';
	
	if ( is_category() ) {
		$title = single_cat_title( '', false );
		$description = category_description();
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
		$description = tag_description();
	}
	
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'CollectionPage',
		'name'        => $title,
		'description' => wp_strip_all_tags( $description ),
		'url'         => get_term_link( get_queried_object() ),
	);
}

/**
 * 获取 BreadcrumbList Schema
 */
function guangwei_shishu_get_breadcrumb_schema() {
	$breadcrumbs = array(
		array(
			'@type' => 'ListItem',
			'position' => 1,
			'name' => __( '首页', 'guangwei-shishu' ),
			'item' => home_url( '/' ),
		),
	);
	
	$position = 2;
	
	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$breadcrumbs[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => $term->name,
			'item' => get_term_link( $term ),
		);
	} elseif ( is_singular( 'post' ) ) {
		// 添加分类
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$breadcrumbs[] = array(
				'@type' => 'ListItem',
				'position' => $position,
				'name' => $categories[0]->name,
				'item' => get_category_link( $categories[0]->term_id ),
			);
			$position++;
		}
		
		// 添加文章标题
		$breadcrumbs[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => get_the_title(),
			'item' => get_permalink(),
		);
	} elseif ( is_page() ) {
		$breadcrumbs[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => get_the_title(),
			'item' => get_permalink(),
		);
	} elseif ( is_author() ) {
		$breadcrumbs[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => get_the_author(),
			'item' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		);
	} elseif ( is_search() ) {
		$breadcrumbs[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => sprintf( __( '搜索：%s', 'guangwei-shishu' ), get_search_query() ),
			'item' => get_search_link(),
		);
	}
	
	return array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $breadcrumbs,
	);
}

/**
 * 获取 Meta Description
 */
function guangwei_shishu_get_meta_description() {
	$description = '';
	
	if ( is_singular() ) {
		// 文章摘要
		$description = get_the_excerpt();
		if ( empty( $description ) ) {
			// 从内容中提取
			$content = wp_strip_all_tags( get_the_content() );
			$description = mb_substr( $content, 0, 160, 'UTF-8' );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
		if ( empty( $description ) ) {
			$description = sprintf( __( '%s相关的诗词文章', 'guangwei-shishu' ), single_term_title( '', false ) );
		}
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description' );
		if ( empty( $description ) ) {
			$description = sprintf( __( '%s的诗词作品集', 'guangwei-shishu' ), get_the_author() );
		}
	} elseif ( is_search() ) {
		$description = sprintf( __( '%s的搜索结果', 'guangwei-shishu' ), get_search_query() );
	} else {
		$description = get_bloginfo( 'description' );
	}
	
	return wp_strip_all_tags( $description );
}
