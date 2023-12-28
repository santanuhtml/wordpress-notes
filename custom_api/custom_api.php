<?php 
/*
Plugin Name: Custom API
Plugin URI: 
Description: Custom API Plugin
Author: Santanu Chowdhury
Author URI: santanuchowdhury04@gmail.com
Version: 1.0
*/

add_action('rest_api_init', 'custom_api');

function custom_api(){
	register_rest_route('capi/v1', 'posts', [
		'methods' => 'GET',
		'callback' => 'capi_posts',
	]);
	register_rest_route('capi/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => 'capi_post',
	));

	//Register for Custom Post
	register_rest_route('capi/v1', 'products', [
		'methods' => 'GET',
		'callback' => 'capi_products',
	]);
};

// post list
function capi_posts(){
	$args = [
		'numberposts' => 9999,
		'post_type' => 'post' //page, custom post like products...
	];
	$posts = get_posts($args);
	$data = [];
	$i = 0;
	foreach($posts as $post){
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
		$data[$i]['content'] = $post->post_content;
		$data[$i]['slug'] = $post->post_name;
		$data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
		$data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
		$data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post->ID, 'large');
		$i++;
	}
	return $data;
};

//single post with slug
function capi_post($slug){
	$args = [
		'name' => $slug['slug'],
		'post_type' => 'post' //page, custom post like products...
	];
	$post = get_posts($args);
	//get_field("name_of_the_field", $post[0]->ID); //For CUSTOM POST
	$data[$i]['id'] = $post[0]->ID;
	$data[$i]['title'] = $post[0]->post_title;
	$data[$i]['content'] = $post[0]->post_content;
	$data[$i]['slug'] = $post[0]->post_name;
	$data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
	$data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
	$data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');
	return $data;
}

//custom post list (with CFS)
function capi_products($params){
	$price = json_decode($params->get_param('product_price')); //CFS Field name
	//return $price;
	function queryArgument($param, $key) {
        if(is_object($param)) {
            if($param->lt && $param->gt) {
                return [
                    [
                        'key' => $key,
                        'value' => [$param->gt, $param->lt],
                        'type'  => 'NUMERIC',
                        'compare' => 'BETWEEN'
                    ]
                ];
            }

            if($param->lt) {
                return [
                    [
                        'key' => $key,
                        'value' => $param->lt,
                        'type'  => 'NUMERIC',
                        'compare' => '<'
                    ]
                ];
            }

            if($param->gt) {
                return [
                    [
                        'key' => $key,
                        'value' => $param->gt,
                        'type'  => 'NUMERIC',
                        'compare' => '>'
                    ]
                ];
            }
        }


        if($param) {
            return [
                [
                    'key' => $key,
                    'value' => $param,
                    'type'  => 'NUMERIC'
                ]
            ];
        }

        return null;
    }


	$args = [
		'posts_per_page' => 9999,
		'post_type' => 'products',
		'meta_query' =>  queryArgument($price, 'product_price')
	];
	$posts = new WP_Query($args);

	$data = [];
	$i = 0;
	foreach($posts->posts as $post){
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
		$data[$i]['price'] = intval(CFS()->get('product_price',  $post->ID));
		$data[$i]['description'] = CFS()->get('product_description',  $post->ID);
		$i++;
	}
	return $data;
};
?>