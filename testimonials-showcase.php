<?php
/*
Plugin Name: Testimonials Showcase
Description: A simple plugin to display customer testimonials.
Version: 1.0
Author: Majaveli
*/

function testimonials_showcase_init() {
    $args = array(
        'public' => true,
        'label'  => 'Testimonials',
        'supports' => array('title', 'editor', 'thumbnail')
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'testimonials_showcase_init');


function display_testimonials_shortcode() {
    $output = '';
    $query = new WP_Query(array('post_type' => 'testimonial'));
    $testimonial_count = 0;

    if ($query->have_posts()) {
        $output .= '<div class="testimonial-slider">';
        
        while ($query->have_posts()) {
            $query->the_post();
            $testimonial_count++;

            // Get ACF fields for each testimonial
            $customer_name = get_field('customer_name');
            $customer_position = get_field('customer_position');
            $customer_photo = get_field('customer_photo'); // Assuming this returns an image URL

            $output .= '<div class="testimonial">';

            $output .= '<p class="testimonial-content">' . get_the_content() . '</p>';
            
            // Display the customer photo if available
            if ($customer_photo) {
                $output .= '<img src="' . esc_url($customer_photo) . '" alt="' . esc_attr($customer_name) . '" class="testimonial-image">';
            }
            
            // Display the customer name and position
            if ($customer_name) {
               $output .= '<h3 class="testimonial-name">' . esc_html($customer_name) . '</h3>';
            }
            if ($customer_position) {
                $output .= '<p class="testimonial-position">' . esc_html($customer_position) . '</p>';
            }

            // Display the testimonial content
            /*$output .= '<p class="testimonial-content">' . get_the_content() . '</p>';*/
            $output .= '</div>';
        }
        $output .= '</div>'; // Close .testimonial-slider

        // Generate bullet indicators
        $output .= '<div class="testimonial-bullets">';
        for ($i = 0; $i < $testimonial_count; $i++) {
            $output .= '<span class="bullet" data-slide="' . $i . '"></span>';
        }
        $output .= '</div>';
    }
    wp_reset_postdata();
    $output .= '</div>'; // Close .testimonial-wrapper
    return $output;
}
add_shortcode('testimonials', 'display_testimonials_shortcode');


function testimonials_showcase_styles() {
   wp_enqueue_style('testimonials-showcase-style', plugins_url('/assets/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'testimonials_showcase_styles');


function testimonials_showcase_scripts() {
    // Register the script like this for a plugin:
    wp_register_script('testimonial-slider-script', plugins_url('/assets/js/testimonial-slider.js', __FILE__), array('jquery'), '1.0', true);

    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script('testimonial-slider-script');
}
add_action('wp_enqueue_scripts', 'testimonials_showcase_scripts');


function testimonials_enqueue_roboto_font() {
    wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
}
add_action('wp_enqueue_scripts', 'testimonials_enqueue_roboto_font');


//FUTURE Update: Implement Slick
/*
function testimonials_enqueue_slick() {
    // Enqueue Slick CSS
    wp_enqueue_style('slick-css', plugins_url('/slick/slick.css', __FILE__));
    wp_enqueue_style('slick-theme-css', plugins_url('/slick/slick-theme.css', __FILE__));

    // Enqueue Slick JS
    wp_enqueue_script('slick-js', plugins_url('/slick/slick.min.js', __FILE__), array('jquery'), '', true);

    // Enqueue your custom JS to initialize Slick
    wp_enqueue_script('testimonial-slick-initialize', plugins_url('/js/testimonial-slick-initialize.js', __FILE__), array('slick-js'), '', true);
}
add_action('wp_enqueue_scripts', 'testimonials_enqueue_slick');
*/