<?php
/**
 * _s functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */
if (!function_exists('_s_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function _s_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on _s, use a find and replace
         * to change '_s' to the name of your theme in all the template files.
         */
        load_theme_textdomain('_s', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', '_s'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         * See https://developer.wordpress.org/themes/functionality/post-formats/
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('_s_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
    }

endif;
add_action('after_setup_theme', '_s_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
    $GLOBALS['content_width'] = apply_filters('_s_content_width', 640);
}

add_action('after_setup_theme', '_s_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _s_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', '_s'),
        'id' => 'sidebar-1',
        'description' => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', '_s_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
    wp_enqueue_style('_s-style', get_stylesheet_uri());

    wp_enqueue_script('_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true);

    wp_enqueue_script('_s-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', '_s_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



add_action('init', 'create_post_type');

/**
 * add new post type of restaurants
 * 
 */
function create_post_type() {
    $labels = array(
        'name' => 'Restaurants',
        'singular_name' => 'Restaurants',
        'slug' => 'restaurants',
        'menu_name' => 'Restaurants',
        'parent_item_colon' => 'Parent Restaurants',
        'all_items' => 'All Restaurants',
        'view_item' => 'View Restaurants',
        'add_new_item' => 'Add New Restaurants',
        'add_new' => 'Add New',
        'edit_item' => 'Edit Restaurants',
        'update_item' => 'Update Restaurants',
        'search_items' => 'Search Restaurants',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash',
    );
    register_post_type('restaurants', array(
        'public' => true,
        'taxonomies' => array(
            'restaurants_type',
            'food_type'
        ),
        'supports' => array('title', 'comments', 'editor', 'thumbnail'),
        'label' => 'Restaurants',
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
            )
    );
}

add_action('init', 'reg_taxonomy');

/**
 * register two new texonomy to post type restaurants
 */
function reg_taxonomy() {
    register_taxonomy('restaurants_type', 'restaurants', array(
        'show_ui' => true,
        'show_admin_column' => true,
        'label' => 'Restaurant Type'
            )
    );
    register_taxonomy('food_type', 'restaurants'
            , array(
        'show_ui' => true,
        'show_admin_column' => true,
        'label' => 'Food Type'
            )
    );
}

add_action('load-post.php', 'postmeta');
add_action('load-post-new.php', 'postmeta');

/**
 * add and saves new meta boxes
 */
function postmeta() {
    add_action('add_meta_boxes', 'add_address');
    add_action('save_post', 'save_address');
    add_action('add_meta_boxes', 'add_contactno');
    add_action('save_post', 'save_contactno');
    add_action('add_meta_boxes', 'add_email');
    add_action('save_post', 'save_email');
}

/**
 * Saves or Update address postmeta 
 * 
 * @param int $post_id  
 */
function save_address($post_id) {
    if (isset($_POST['restaurant_address'])) {
        $address = $_POST['restaurant_address'];
        update_post_meta($post_id, '_restaurant_address', $address);
    }
}
/**
 * Saves or update contact number of restaurant
 * @param int $post_id
 */
function save_contactno($post_id) {
    if (isset($_POST['restaurant_contactno'])) {
        $contactno = $_POST['restaurant_contactno'];
        update_post_meta($post_id, '_restaurant_contactno', $contactno);
    }
}

/**
 * save or update email id of restaurant
 * @param int $post_id
 */
function save_email($post_id) {
    if (isset($_POST['restaurant_email'])) {
        $email = $_POST['restaurant_email'];
        update_post_meta($post_id, '_restaurant_email', $email);
    }
}

/**
 * add new meta box of address for restaurants post type
 */
function add_address() {
    add_meta_box(
            'restaurants-address', esc_html__('Address', 'Address'), 'add_address_meta_box', 'restaurants', 'side', 'default'
    );
}

/**
 * display/add meta box on restaurants post
 */
function add_address_meta_box($post) {
    $restaurant_add = "";
    $val = get_post_meta($post->ID, '_restaurant_address', true);
    if ($val != NULL && !empty($val)) {
        $restaurant_add = $val;
    }
    echo "<input type='textarea' id='address' value='" . $restaurant_add . "' name='restaurant_address' />";
}

add_action('load-post.php', 'Timing_metapost');
add_action('load-post-new.php', 'Timing_metapost');

/**
 * add and save timing meta post for restaurant post type
 */
function Timing_metapost() {
    add_action('add_meta_boxes', 'add_timing');
    add_action('save_post', 'save_timing');
}

/**
 * add meta box for timing
 */
function add_timing() {
    add_meta_box(
            'restaurants-timing', esc_html__('Timing & Working Days', 'Timing & Working Days'), 'add_timing_meta_box', 'restaurants', 'side', 'default'
    );
}

/**
 * add timing meta box on restaurant post display
 * @param int $post
 */
function add_timing_meta_box($post) {
    ?>
    <form name="restaurant_timing">
        <table style="font-size: 12px;margin:auto">
            <tr style="text-align: center;font-size: 12px; font-weight: bold">
                <td>Day</td>
                <td>From</td>
                <td>To</td>
            </tr>
            <?php
            $time = get_post_meta($post->ID, '_timing', true);

            $days = array("mon" => "Monday", "tue" => "Tuesday", "wed" => "Wednesday", "thu" => "Thursday", "fri" => "Friday", "sat" => "Saturday", "sun" => "Sunday");
            foreach ($days as $key => $day) {
                $am = $pm = "";
                if (!empty($time) && is_array($time)) {
                    if ($time[0][$key][0] != NULL) {
                        $am = $time[0][$key][0];
                    }
                    if ($time[0][$key][1] != NULL) {
                        $pm = $time[0][$key][1];
                    }
                }
                echo "<tr><td name=" . $day . ">" . $day . "</td>";
                echo "<td><input type='text' name='time[" . $key . "][]' size='3' value='" . $am . "'>AM</td>";
                echo "<td><input type='text' name='time[" . $key . "][]' size='3' value='" . $pm . "'>PM</td></tr>";
            }
            ?>
        </table>
    </form>
    <?php
}

/**
 * save timing postmeta for restaurant
 * @param int $post_id
 */
function save_timing($post_id) {
    if (isset($_POST['time'])) {
        $time = array($_POST['time']);
        update_post_meta($post_id, '_timing', $time);
        $close_days = array();
        $i = 0;
        foreach ($time[0] as $key => $day) {
            if ($day[0] == NULL && $day[1] == NULL) {
                $close_days[$i++] = ($key);
            }
        }
        update_post_meta($post_id, '_close_days', $close_days);
    }
}

add_action('wp_enqueue_scripts', 'add_css_js');

/**
 * enqueue css for restaurant post type
 */
function add_css_js() {
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'ajax_object', admin_url('admin-ajax.php'));

    wp_enqueue_style("restaurants_css", get_template_directory_uri() . '/restaurant.css');
    wp_enqueue_style("Slick_css", get_template_directory_uri() . '/slick/slick.css');
    wp_enqueue_style("Slick_theme_css", get_template_directory_uri() . '/slick/slick-theme.css');

    wp_register_script('slick-js1', get_template_directory_uri() . '/slick/slick.min.js');
    wp_enqueue_script('slick-js1');

    wp_register_script('jquery-migrate-js', get_template_directory_uri() . '/js/jquery-migrate-1.2.1.min.js');
    wp_enqueue_script('jquery-migrate-js');
}

/**
 * Review field add, save review and display review
 */
add_filter('comment_form_defaults', 'default_fields');

function default_fields() {
    $default ['comment_field'] = '<p class="comment-form-comment"><label for="Review">' . _x('Review', 'noun') . '</label> <br />'
            . '<textarea id="review_area" name="comment" cols="20" rows="5" width=50% aria-required="true" required="required"></textarea></p>';
    $default ['title_reply'] = __('Review Us');
    $default ['label_submit'] = __('Post Review');
    return $default;
}

add_filter('comment_form_default_fields', 'custom_fields');

/**
 * add fields to review
 * @return string
 */
function custom_fields() {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] = '<p class="comment-form-author">' .
            '<label for="author">' . __('Name') . '</label>' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) .
            '" size="30" ' . $aria_req . ' /></p>';

    $fields['email'] = '<p class="comment-form-email">' .
            '<label for="email">' . __('Email') . '</label>' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
            '" size="30" ' . $aria_req . ' /></p>';

    return $fields;
}

add_action('comment_form_logged_in_after', 'additional_fields');
add_action('comment_form_after_fields', 'additional_fields');
/**
 * Add field of rating in review
 */
function additional_fields() {
    echo '<p class="comment-form-rating">' .
    '<label for="rating">' . __('Rating') . '<span class="required">*</span></label>
  <span class="commentratingbox">';

    for ($i = 1; $i <= 5; $i++)
        echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="' . $i . '"/> ' . $i . '</span>';

    echo'</span></p>';
}

add_action('comment_post', 'save_comment_meta_data');

/**
 * Save the comment meta data along with comment
 * @param int $comment_id
 */
function save_comment_meta_data($comment_id) {

    if (( isset($_POST['rating']) ) && ( $_POST['rating'] != ''))
        $rating = wp_filter_nohtml_kses($_POST['rating']);
    add_comment_meta($comment_id, 'rating', $rating);
}

/**
 * To check that rating is given or not
 */
add_filter('preprocess_comment', 'verify_comment_meta_data');

function verify_comment_meta_data($commentdata) {
    if (!isset($_POST['rating']))
        wp_die(__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
    return $commentdata;
}

add_filter('comment_text', 'modify_comment');
/**
 * Display star ratting
 * @param string $text
 * @return string
 */
function modify_comment($text) {

    $plugin_url_path = WP_PLUGIN_URL;

    if ($commenttitle = get_comment_meta(get_comment_ID(), 'title', true)) {
        $commenttitle = '<strong>' . esc_attr($commenttitle) . '</strong><br/>';
        $text = $commenttitle . $text;
    }

    if ($commentrating = get_comment_meta(get_comment_ID(), 'rating', true)) {

        $commentrating = '<p class="comment-rating">  <img src="' . get_template_directory_uri() . '/star/' . $commentrating . 'star.png"/><br/>Rating: <strong>' . $commentrating . ' / 5</strong></p>';
        $text = $text . $commentrating;
        return $text;
    } else {
        return $text;
    }
}

/**
 *  Add an edit option to comment editing screen  
 */
add_action('add_meta_boxes_comment', 'extend_comment_add_meta_box');

function extend_comment_add_meta_box() {
    add_meta_box('title', __('Comment Metadata - Extend Comment'), 'extend_comment_meta_box', 'comment', 'normal', 'high');
}

function extend_comment_meta_box($comment) {
    $rating = get_comment_meta($comment->comment_ID, 'rating', true);
    wp_nonce_field('extend_comment_update', 'extend_comment_update', false);
    ?>
    <p>
        <label for="rating"><?php _e('Rating: '); ?></label>
        <span class="commentratingbox">
    <?php
    for ($i = 1; $i <= 5; $i++) {
        echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="' . $i . '"';
        if ($rating == $i)
            echo ' checked="checked"';
        echo ' />' . $i . ' </span>';
    }
    ?>
        </span>
    </p>
    <?php
}

/**
 * Update comment meta data from comment editing screen 
 */
add_action('edit_comment', 'extend_comment_edit_metafields');

function extend_comment_edit_metafields($comment_id) {
    if (!isset($_POST['extend_comment_update']) || !wp_verify_nonce($_POST['extend_comment_update'], 'extend_comment_update'))
        return;

    if (( isset($_POST['rating']) ) && ( $_POST['rating'] != '')):
        $rating = wp_filter_nohtml_kses($_POST['rating']);
        update_comment_meta($comment_id, 'rating', $rating);
    else :
        delete_comment_meta($comment_id, 'rating');
    endif;
}

/**
 * Scripts add for map
 */
add_action('get_footer', 'javascript_maps');

function javascript_maps() {
    ?><script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script>
        initMap();
        function initMap() {

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: {lat: -34.397, lng: 150.644}
            });
            var geocoder = new google.maps.Geocoder();
            geocodeAddress(geocoder, map);

            /*document.getElementById('submit').addEventListener('click', function() {
             geocodeAddress(geocoder, map);
             });*/
        }

        function geocodeAddress(geocoder, resultsMap) {
            var address = document.getElementById('data_address').textContent;
            
            geocoder.geocode({'address': address}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    resultsMap.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
        //Slick for slideshow
        jQuery(document).ready(function () {
            jQuery('.image-gallery').slick({
                dots: true,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                
            });
        });
    </script>
    <?php
}

/**
 * photo gallery
 */
add_theme_support('post-thumbnails', array('restaurants'));
set_post_thumbnail_size(50, 50);
add_image_size('single-post-thumbnail', 400, 9999);
