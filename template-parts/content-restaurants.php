<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if (is_singular('restaurants')) {

    add_action('wp_enqueue_scripts', 'enqueue_js');
    /**
     * enqueue ajax file
     */
    function enqueue_js() {
        wp_enqueue_script('ajax-script', plugins_url().'restaurants.js', array('jquery'));

        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'we_value' => 1234));
    }

// Same handler function...
    add_action('wp_ajax_my_action', 'ajax_callback');
    
    function ajax_callback() {
        global $wpdb;
        $whatever = intval($_POST['whatever']);
        $whatever += 10;
        echo $whatever;
        wp_die();
    }
    
    global $post;
    $current_post_address = get_post_meta($post->ID, '_restaurant_address', true);
    echo "<p class='labels'>Address</p>";
    echo "<div class='address'>" . $current_post_address . "</div>";

    $current_post_timing = get_post_meta($post->ID, '_timing', true);
    $days = array("mon" => "Monday", "tue" => "Tuesday", "wed" => "Wednesday", "thu" => "Thursday", "fri" => "Friday", "sat" => "Saturday", "sun" => "Sunday");
    ?>
    <br />
    <p class="labels">Restaurant Timing</p>
    <table class="timing_table">
        <tr id="timing_title">
            <td>Day</td>
            <td>From</td>
            <td>To</td>
        </tr>
        <?php
        foreach ($current_post_timing[0] as $key => $day) {
            echo "<tr class='timing_data'>";
            echo "<td>" . $days[$key] . "</td>";
            if ($day[0] == NULL && $day[1] == NULL) {
                echo "<td colspan='3'>Close</td>";
            } else {
                echo "<td>" . $current_post_timing[0][$key][0] . "AM</td>";
                echo "<td>" . $current_post_timing[0][$key][1] . "PM</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <?php
}

/**
 * prints _restaurant_type taxonomy
 */
$terms = wp_get_post_terms($post->ID, 'restaurants_type', '');
if (!is_wp_error($terms) && $terms) {
    $term_text = $term_text = "<p class='labels' >Restaurant Type</p> <br />";
    ;
    foreach ($terms as $term) {
        $term_text .=$term->name;
    }
    echo $term_text;
}

/**
 * prints _food_type taxonomy
 */
$terms = wp_get_post_terms($post->ID, 'food_type', '');
if (!is_wp_error($terms) && $terms) {
    $term_text = "<p class='labels' >Food Type</p> <br />";
    echo "<ul>";
    foreach ($terms as $term) {
        $term_text .='<li>' . $term->name . '</li>';
    }
    echo "</ul>";
    echo $term_text;
}
?>
