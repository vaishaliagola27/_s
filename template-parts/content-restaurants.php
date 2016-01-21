<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
wp_register_script('restaurant-js', get_template_directory_uri() . '/js/restaurants.js');
if (is_singular('restaurants')) {
        
//    wp_enqueue_script('restaurant-js');
//
//// Same handler function...
//    add_action('wp_ajax_my_action', 'ajax_callback');
//
//    function ajax_callback() {
//        global $wpdb;
//        $whatever = intval($_POST['whatever']);
//        $whatever += 10;
//        echo $whatever;
//        wp_die();
//    }

    global $post;
    echo "<h1>". get_post($post->ID) ->post_title."</h1>";
    $current_post_address = get_post_meta($post->ID, '_restaurant_address', true);
    echo "<div class='address'><p class='labels'>Address </p><label id='data_address' value='".$current_post_address ."'>" . $current_post_address . "</label>";
    
    echo '<div id="map"></div></div>';
    
    $current_post_timing = get_post_meta($post->ID, '_timing', true);
    $days = array("mon" => "Monday", "tue" => "Tuesday", "wed" => "Wednesday", "thu" => "Thursday", "fri" => "Friday", "sat" => "Saturday", "sun" => "Sunday");
    ?>

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
                echo "<td colspan='3' class='close'>Close</td>";
            } else {
                echo "<td>" . $current_post_timing[0][$key][0] . "AM</td>";
                echo "<td>" . $current_post_timing[0][$key][1] . "PM</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <?php
    /**
     * prints _restaurant_type taxonomy
     */
    $terms = wp_get_post_terms($post->ID, 'restaurants_type', '');
    if (!is_wp_error($terms) && $terms) {
        echo "<p class='labels' >Restaurant Type</p>";
        $term_text = '';
        foreach ($terms as $term) {
            $term_text .=$term->name . "<br />";
        }
        echo "<div class='data'>" . $term_text . "</div>";
    }

    /**
     * prints _food_type taxonomy
     */
    $terms = wp_get_post_terms($post->ID, 'food_type', '');
    if (!is_wp_error($terms) && $terms) {
        echo "<p class='labels' >Food Type</p>";
        $term_text = "<ul class='data'>";
        foreach ($terms as $term) {
            $term_text .="<li>" . $term->name . "</li>";
        }
        $term_text.="</ul>";
        echo $term_text;
    }

    global $post;
   
    $comments = get_comments($post->ID);
   // echo print_r($comments);
    $rating=0;
    $cnt=0;
    foreach($comments as $cm){
        $rating+= get_comment_meta($cm->comment_ID, 'rating', true);
        $cnt+=1;
    }
      
    ?>
    
        <?php
        if($cnt != 0){
            $rating/=$cnt;
            echo '<p class="comment-rating">  <img src="'. get_template_directory_uri().'/star/'. intval($rating) . 'star.png"/><br/>Rating: <strong>'. intval($rating) .' / 5</strong></p>';
        }
        else{
            echo '<p> No Ratings Yet!!</p>';
        }

    }
    
       

