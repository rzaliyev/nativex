<?php
/*

  This file is part of a child theme called Affiliate.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read
  https://developer.wordpress.org/themes/advanced-topics/child-themes/

*/

// This code loads the parent's stylesheet (leave it in place unless you know what you're doing)

function your_theme_enqueue_styles()
{
    $parent_style = 'parent-style';

    wp_enqueue_style($parent_style,
      get_template_directory_uri().'/style.css');

    wp_enqueue_style('child-style',
      get_stylesheet_directory_uri().'/style.css',
      [$parent_style],
      wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

/*  Add your own functions below this line
    ======================================== */

/*
 * Adding custom img sizes
 */

add_image_size('ad_large', 1080, 607, true);
add_image_size('ad_small', 225, 150, true);

/**
 * Register and enqueue scripts.
 */
function nativex_register_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('nativex-js', get_stylesheet_directory_uri().'/js/custom.js');
}
add_action('wp_enqueue_scripts', 'nativex_register_scripts');

// Adding campaign links to the REST API response
add_action('rest_api_init', function () {
    register_rest_field('post',
      'campaigns',
      [
          'get_callback' => function ($object, $field_name, $request) {
              global $post;
              $postUrl = get_permalink($post->id);

              // Getting the campaign fields
              $clicks = get_field('clicks', $post->id);
              $region = get_field('region', $post->id);
              $start_date = get_field('start_date', $post->id);
              $end_date = get_field('end_date', $post->id);

              return [
                'url' => [$postUrl],
                'clicks' => [$clicks],
                'region' => [$region],
                'start_date' => [$start_date],
                'end_date' => [$end_date],
            ];
          },
          'update_callback' => null,
          'schema' => null,
      ]
  );
});
