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



add_action('init', 'rt_restaurant_create_post_type');

/**
 * add new post type of restaurants
 * 
 */
function rt_restaurant_create_post_type() {
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
    
    //filter for custom post labels
    $labels=apply_filters('rt_restaurant_add_custom_post_labels', $labels);
    
    $taxonomy=array('restaurants_type','food_type');
    
    //filter for taxonomies
    $taxonomy=apply_filters('rt_restaurant_get_taxonomies',$taxonomy);
    
    $args=array(
        'public' => true,
        'taxonomies' => $taxonomy,
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
            );
    
    //filter for custom post type arguments
    $args=apply_filters('rt_restaurant_custom_post_args',$args);
    
    register_post_type('restaurants', $args);
}

add_action('init', 'rt_restaurant_reg_taxonomy');
/**
 * register new texonomy to post type restaurants
 */
function rt_restaurant_reg_taxonomy() {
    $taxonomy = array('restaurants_type' => 'Restaurants Type', 'food_type' => 'Food Type');
    
    //filter for taxonomies with label
    $taxonomy = apply_filters('rt_restaurant_get_taxonomies_with_label', $taxonomy);
    $post_type = 'restaurants';

    foreach ($taxonomy as $name => $label) {
        $args = array(
            'show_ui' => true,
            'show_admin_column' => true,
            'label' => $label
        );
        
        //filter for taxonomy arguments
        $args = apply_filters('rt_restaurant_taxonomy_args', $args);
        
        register_taxonomy($name, $post_type, $args);
    }
}


add_action('load-post.php', 'rt_restaurant_address_postmeta');
add_action('load-post-new.php', 'rt_restaurant_address_postmeta');

/**
 * add and saves new meta boxes
 */
function rt_restaurant_address_postmeta() {
    add_action('add_meta_boxes', 'rt_restaurant_add_address');
    add_action('save_post', 'rt_restaurant_save_address');
}

/**
 * add new meta box of address for restaurants post type
 */
function rt_restaurant_add_address() {
    add_meta_box(
            'restaurants-address', esc_html__('Address', 'Address'), 'rt_restaurant_add_address_meta_box', 'restaurants', 'side', 'default'
    );
}

/**
 * Saves or Update address postmeta 
 * @param int $post_id  
 */
function rt_restaurant_save_address($post_id) {
    if (isset($_POST['restaurant_add'])) {
        $address = array($_POST['restaurant_add']);
        update_post_meta($post_id, '_restaurant_address', $address);
    }
}

/**
 * display/add meta box on restaurants post
 * @param array $post
 */
function rt_restaurant_add_address_meta_box($post) {
    ob_start();
    $addr = array("streetAddress" => "Street Address", "addressLocality" => "Locality", "addressRegion" => "Region", "postalCode" => "Postal Code", "addressCountry" => "Country");
    $add = get_post_meta($post->ID, '_restaurant_address', true);
    
    ?>
    <table class="address_table">
        <?php 
       foreach($addr as $key => $value){
           if ($add != NULL && !empty($add)) {
                $value = $add[0][$key];    
           }
           else
           {
               $value='';
           }
           ?>
            <tr>
                <td><label> <?php echo $addr[$key]; ?></label></td>
                <td>
                    <input size="15" type="text" name="<?php echo "restaurant_add[".$key."]"; ?>" value="<?php echo $value;?>" />
                </td> 
            </tr>
            <?php
       }
       ?>
    </table>
    <?php
    $ob_address=  ob_get_clean();
    
    //filter for address html
    $ob_address = apply_filters('rt_restaurant_address_html',$ob_address);
    echo $ob_address;
}

add_action('load-post.php', 'rt_restaurant_contactno_postmeta');
add_action('load-post-new.php', 'rt_restaurant_contactno_postmeta');
/**
 * add and saves new meta box for contact number
 */
function rt_restaurant_contactno_postmeta() {
    add_action('add_meta_boxes', 'rt_restaurant_add_contactno');
    add_action('save_post', 'rt_restaurant_save_contactno');
}

/**
 * Saves or update contact number of restaurant
 * @param int $post_id
 */
function rt_restaurant_save_contactno($post_id) {
    if (isset($_POST['restaurant_contact_no'])) {
        $contactno = $_POST['restaurant_contact_no'];
        update_post_meta($post_id, '_restaurant_contactno', $contactno);
    }
}

/**
 * add new meta box for contact number
 */
function rt_restaurant_add_contactno() {
    add_meta_box(
            'restaurants-contactno', esc_html__('Contact no.', 'Contact no.'), 'rt_restaurant_add_contactno_meta_box', 'restaurants', 'side', 'default'
    );
}


/**
 * display/add meta box on restaurants post
 * @param array $post
 */
function rt_restaurant_add_contactno_meta_box($post) {
    ob_start();
    $restaurant_contact = "";
    $val = get_post_meta($post->ID, '_restaurant_contactno', true);
    if ($val != NULL && !empty($val)) {
        $restaurant_contact = $val;
    }
    echo "<input type='text' id='contact-no' value='" . $restaurant_contact . "' name='restaurant_contact_no' />";
 
    $ob_contactno=  ob_get_clean();
    
    //filter for user define html of restaurant contact number
    $ob_contactno = apply_filters('rt_restaurant_contactno_html',$ob_contactno);
    echo $ob_contactno;
}

add_action('load-post.php', 'rt_restaurant_timing_metapost');
add_action('load-post-new.php', 'rt_restaurant_timing_metapost');

/**
 * add and save timing meta post for restaurant post type
 */
function rt_restaurant_timing_metapost() {
    add_action('add_meta_boxes', 'rt_restaurant_add_timing');
    add_action('save_post', 'rt_restaurant_save_timing');
}

/**
 * add meta box for timing
 */
function rt_restaurant_add_timing() {
    add_meta_box(
            'restaurants-timing', esc_html__('Timing & Working Days', 'Timing & Working Days'), 'rt_restaurant_add_timing_meta_box', 'restaurants', 'side', 'default'
    );
}

/**
 * add timing meta box on restaurant post display
 * @param int $post
 */
function rt_restaurant_add_timing_meta_box($post) {
    ob_start();
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
                $am = $pm = NULL;
                if (!empty($time) && is_array($time)) {
                    if ($time[0][$key][0] != NULL) {
                        $am = $time[0][$key][0];
                    }
                    if ($time[0][$key][1] != NULL) {
                        $pm = $time[0][$key][1];
                    }
                }
                ?>
                <tr>
                    <td name=" <?php echo $day ?> "> <?php echo $day ?> </td>
                    <td><input type="text" name="<?php echo "time[".$key."][]";?>" size="3" value=" <?php echo $am ?> ">AM</td>
                    <td><input type="text" name="<?php echo "time[".$key."][]";?>" size="3" value=" <?php echo $pm ?> ">PM</td>
                </tr>
            <?php
            }
            ?>
        </table>
    </form>
    <?php
    $ob_timing_working_days=  ob_get_clean();
    
    //filter for user define html for timing and working days
    $ob_timing_working_days = apply_filters('rt_restaurant_timing_working_days_html',$ob_timing_working_days);
    echo $ob_timing_working_days;
}

/**
 * save timing postmeta for restaurant
 * @param int $post_id
 */
function rt_restaurant_save_timing($post_id) {
    if (isset($_POST['time'])) {
        $time = array($_POST['time']);
        
        //apply filter for restaurant timing
        $time = apply_filters('rt_restaurant_time',$time);
        
        update_post_meta($post_id, '_timing', $time);
        $close_days = array();
        $i = 0;
        foreach ($time[0] as $key => $day) {
            if ($day[0] == NULL && $day[1] == NULL) {
                $close_days[$i++] = ($key);
            }
        }
        
        //filter for close days of restaurants
        $close_days = apply_filters('rt_restaurant_close_days' , $close_days);
        update_post_meta($post_id, '_close_days', $close_days);
    }
}

add_action('wp_enqueue_scripts', 'rt_restaurant_add_css_js');

/**
 * enqueue css for restaurant post type
 */
function rt_restaurant_add_css_js() {
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'ajax_object', admin_url('admin-ajax.php'));

    wp_enqueue_style("restaurants_css", get_template_directory_uri() . '/restaurant.css');
    wp_enqueue_style("Slick_css", get_template_directory_uri() . '/slick/slick.css');
    wp_enqueue_style("Slick_theme_css", get_template_directory_uri() . '/slick/slick-theme.css');

    wp_register_script('slick-js1', get_template_directory_uri() . '/slick/slick.min.js');
    wp_enqueue_script('slick-js1');

    wp_register_script('jquery-migrate-js', get_template_directory_uri() . '/js/jquery-migrate-1.2.1.min.js');
    wp_enqueue_script('jquery-migrate-js');
 
    wp_register_script('slider-js', get_template_directory_uri() . '/js/restaurants.js');
    wp_enqueue_script('slider-js');
}

/**
 * Review field add, save review and display review
 */
add_filter('comment_form_defaults', 'rt_restaurant_default_fields');

function rt_restaurant_default_fields() {
    $default ['comment_field'] = '<p class="comment-form-comment"><label for="Review">' . _x('Review', 'noun') . '</label> <br />'
            . '<textarea id="review_area" name="comment" cols="20" rows="5" width=50% aria-required="true" required="required"></textarea></p>';
    $default ['title_reply'] = __('Review Us');
    $default ['label_submit'] = __('Post Review');
    
    //filter for default comment fields
    $default = apply_filters('rt_restaurant_default_comment_fields', $default);
    
    return $default;
}

add_filter('comment_form_default_fields', 'rt_restaurant_custom_fields');

/**
 * add fields to review
 * @return string
 */
function rt_restaurant_custom_fields() {
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

    //filter for custom fields in comment
    $fields = apply_filters('rt_restaurant_custom_comment_fields',$fields);
    
    return $fields;
}

add_action('comment_form_logged_in_after', 'rt_restaurant_additional_fields');
add_action('comment_form_after_fields', 'rt_restaurant_additional_fields');

/**
 * Add field of rating in review
 */
function rt_restaurant_additional_fields() {
    ob_start();
    echo '<p class="comment-form-rating">' .
    '<label for="rating">' . __('Rating') . '<span class="required">*</span></label>
  <span class="commentratingbox">';

    for ($i = 1; $i <= 5; $i++)
        echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="' . $i . '"/> ' . $i . '</span>';

    echo'</span></p>';
    $ob_rating=  ob_get_clean();
    
    //filter for user define html for rating
    $ob_rating = apply_filters('rt_restaurant_rating_html',$ob_rating);
    echo $ob_rating;
}

add_action('comment_post', 'rt_restaurant_save_comment_meta_data');

/**
 * Save the comment meta data along with comment
 * @param int $comment_id
 */
function rt_restaurant_save_comment_meta_data($comment_id) {
    if (( isset($_POST['rating']) ) && ( $_POST['rating'] != ''))
        $rating = wp_filter_nohtml_kses($_POST['rating']);
    
    add_comment_meta($comment_id, 'rating', $rating);
    rt_restaurant_add_transient_rating($comment_id);
}

/**
 * To check that rating is given or not
 */
add_filter('preprocess_comment', 'rt_restaurant_verify_comment_meta_data');

function rt_restaurant_verify_comment_meta_data($commentdata) {
    if (!isset($_POST['rating']))
        wp_die(__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
    return $commentdata;
}

/**
 *  Add an edit option to comment editing screen  
 */
add_action('add_meta_boxes_comment', 'rt_restaurant_extend_comment_add_meta_box');

function rt_restaurant_extend_comment_add_meta_box() {
    add_meta_box('title', __('Comment Metadata - Extend Comment'), 'rt_restaurant_extend_comment_meta_box', 'comment', 'normal', 'high');
}

/**
 * edit comment meta box 
 * @param array $comment
 */
function rt_restaurant_extend_comment_meta_box($comment) {
    ob_start();
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
    $ob_rating_display_edit=  ob_get_clean();
    
    //filter for user define html for rating display in edit screen
    $ob_rating_display_edit = apply_filters('rt_restaurant_rating_display_edit_html',$ob_rating_display_edit);
    echo $ob_rating_display_edit;
}

/**
 * Update comment meta data from comment editing screen 
 */
add_action('edit_comment', 'rt_restaurant_extend_comment_edit_metafields');

function rt_restaurant_extend_comment_edit_metafields($comment_id) {
    if (!isset($_POST['extend_comment_update']) || !wp_verify_nonce($_POST['extend_comment_update'], 'extend_comment_update'))
        return;

    if (( isset($_POST['rating']) ) && ( $_POST['rating'] != '')):
        $rating = wp_filter_nohtml_kses($_POST['rating']);
        update_comment_meta($comment_id, 'rating', $rating);
    else :
        delete_comment_meta($comment_id, 'rating');
    endif;
    rt_restaurant_add_transient_rating($comment_id);
}

/**
 * Scripts add for map
 */
add_action('get_footer', 'rt_restaurant_javascript_maps');

function rt_restaurant_javascript_maps() {
    ?>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <?php
}

/**
 * photo gallery
 */
add_theme_support('post-thumbnails', array('restaurants'));
set_post_thumbnail_size(50, 50);
add_image_size('single-post-thumbnail', 400, 9999);

/**
 * Display review of restaurants
 * @param array $review
 * @param string $args
 * @param int $depth
 */
function rt_restaurants_reviews_html($review, $args, $depth){
    ob_start();
    
    $GLOBALS['comment'] = $review;
    extract($args, EXTR_SKIP);
    if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
    ?>
    <fieldset id="div-comment-<?php comment_ID() ?>" class="comment-body" itemprop="review" itemscope itemtype="http://schema.org/Review">
        <legend class="comment-author" itemprop="author">
            <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $review, $args['avatar_size'] ); ?>
            <?php echo  get_comment_author_link() ; ?>
        </legend>
        
        <?php if ( $review->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
		<br />
	<?php endif; ?>

        <div class="comment-meta commentmetadata" itemprop="datePublished">
		<?php
			/* translators: 1: date, 2: time */
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
		?>
	</div>
        
                <div itemprop="description">
                    <?php echo $review->comment_content; ?>
                </div>
                <?php
                    $commentrating = get_comment_meta(get_comment_ID(), 'rating', true);
                ?>
                <p class="comment-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <img src="<?php echo get_template_directory_uri() . '/star/' . $commentrating . 'star.png' ; ?>" />
                    <br/>
                    Rating: 
                    <strong itemprop="ratingValue">
                        <?php echo $commentrating ."\n";?>
                        / <span itemprop="bestRating">5</span>
                    </strong>
                </p>
        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
    </fieldset>
    <?php
    
    $ob_review_all=  ob_get_clean();
    
    //filter for user define html for all review display
    $ob_review_all = apply_filters('rt_restaurant_review_display',$ob_review_all);
    echo $ob_review_all;
}

/**
 * Set transient to store ratting and postmeta to store average
 */

function rt_restaurant_add_transient_rating($comment_id) {
    echo $comment_id;
    $comments = get_comments($comment_id);
    $rating = 0;
    $cnt = 0;
    $postid=$comments[0] -> comment_post_ID ;
    $comments = get_comments($postid);
    foreach ($comments as $cm) {
        $rating+= get_comment_meta($cm->comment_ID, 'rating', true);
        $cnt+=1;
    }
    $value = array("postID" => 0, "sum" => 0, "count" => 0);
    $value["postID"] = $postid;
    $value["sum"] = $rating;
    $value["count"] = $cnt;
    set_transient('_site_transient_rating_sum_count-'.$postid, $value, 0);
    $rating= $rating / $cnt;
    update_post_meta($postid, '_restaurant_ratting', intval($rating));
}
