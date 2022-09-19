<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

$blog_config = array(
    'blog_link_title_1' => 'Blog',
    'blog_link_title_3' => 'Blog',
    'link_rewrite' => 'blog',
    'category_rewrite' => 'category',
    'detail_rewrite' => 'post',
    'meta_title_1' => 'Blog',
    'meta_title_3' => 'Blog',
    'meta_description_1' => '',
    'meta_description_3' => '',
    'meta_keywords_1' => '',
    'meta_keywords_3' => '',
    'indexation' => 0,
    'rss_limit_item' => 5,
    'rss_title_item' => 'RSS FEED',
    // 'latest_limit_items' => 20,
    'saveConfiguration' => '',
    'listing_show_categoryinfo' => 1,
    'listing_limit_items' => 3,
    'listing_show_title' => 1,
    'listing_show_description' => 1,
    'listing_show_readmore' => 1,
    'listing_show_image' => 1,
    'listing_show_author' => 0,
    'listing_show_category' => 0,
    'listing_show_created' => 1,
    'listing_show_hit' => 0,
    'listing_show_counter' => 0,
    'item_show_description' => 1,
    'item_show_image' => 1,
    'item_show_author' => 1,
    'item_show_category' => 1,
    'item_show_created' => 1,
    'item_show_hit' => 1,
    'item_show_counter' => 1,
    'social_code' => '',
    'google_captcha_status' => 0,
    'google_captcha_site_key' => '',
    'google_captcha_secret_key' => '',
    'item_show_listcomment' => 1,
    'item_show_formcomment' => 1,
    'item_comment_engine' => 'local',
    'item_posts_type' => 'type1',
    'show_in_blog' => '1',
    'show_in_post' => '1',
    'show_in_DisplayHome' => '1',
    'item_limit_comments' => '10',
    'item_diquis_account' => 'demo4antheme',
    'item_facebook_appid' => '100858303516',
    'item_facebook_width' => '600',
    'limit_recent_blog' => '2',
    'limit_DisplayHome_blog' => '3',
	'categories_DisplayHome_blog' => '',
);


AnblogConfig::updateConfigValue('cfg_global', serialize($blog_config));
