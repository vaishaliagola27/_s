<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
wp_register_script('restaurant-js', get_template_directory_uri() . '/js/restaurants.js');
if (is_singular('restaurants')) {
    global $post;
?>
<article class="main-content" itemscope itemtype="http://schema.org/Restaurant">
    <header>
        <div id="restaurant-title"><?php echo get_post($post->ID)->post_title ?></div>
        <div id="ratting" itemprop="ratingValue">
                <?php
                $comments = get_comments($post->ID);
                $rating = 0;
                $cnt = 0;
                foreach ($comments as $cm) {
                    $rating+= get_comment_meta($cm->comment_ID, 'rating', true);
                    $cnt+=1;
                }

                /**
                 * Average Rating display
                 */
                if ($cnt != 0) {
                    $rating/=$cnt;
                    echo '<img src="' . get_template_directory_uri() . '/star/' . intval($rating) . 'star.png"/>';
                } 
                ?>
        </div>
    </header>
    <section class="content">
        <div class="content-left">
<!--             address, contact number, restaurant type, food type -->
            <div class="left">
                <div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <?php
                        $current_post_address = get_post_meta($post->ID, '_restaurant_address', true); 
                        $addr = array("streetAddress", "addressLocality", "addressRegion", "postalCode", "addressCountry");
                        ?>
                            <p class='labels'>Address </p>
                            <div itemprop = "address" itemscope itemtype = "http://schema.org/PostalAddress">
                            <?php
                                $address = '';
                                foreach ($addr as $key) {
                                    ?>
                                <span itemprop = "<?php echo $key  ?>"> <?php echo $current_post_address[0][$key]; $address .= "," . $current_post_address[0][$key] ; ?><br /></span>
                                <?php
                                }
                                ?>
                            </div>
                            <input type="hidden" value="<?php echo $address; ?>" id="address_value"/>
                </div>
                <div class="contact">
                    <?php $phone_no=get_post_meta($post->ID,'_restaurant_contactno',true); ?>
                    <label class="labels">Contact Us:</label>
                    <span itemprop="telephone">
                        <a href="tel://<?php  echo $phone_no ?>"><?php  echo $phone_no ?></a>
                    </span>
                </div>
                
                <div class="restaurant-type">
                    <p class='labels' >Restaurant Type</p>
                    <p>
                    <?php
                    $terms = wp_get_post_terms($post->ID, 'restaurants_type', '');
                    if (!is_wp_error($terms) && $terms) {
                        $term_text = '';
                        foreach ($terms as $term) {
                            $term_text .=$term->name ."<br />\n";
                        }
                        echo  $term_text;
                    }            
                    ?>
                    <p>
                </div>
                
                <div class="food-type">
                    <p class='labels' >Food Type</p>
                    <?php
                        $terms = wp_get_post_terms($post->ID, 'food_type', '');
                        if (!is_wp_error($terms) && $terms) {
                            $term_text = "<ul>";
                            foreach ($terms as $term) {
                                $term_text .="<li>" . $term->name . "</li>";
                            }
                            $term_text.="</ul>";
                            echo $term_text;
                        }
                    ?>
                </div>
                
            </div>
<!--             Google Map for address and timing-->
            <div class="right">
                <div id="map"></div>
                
                <div class="restaurant-timing" itemprop="openingHours">
                    <?php
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
                                    ?>
                            <tr class='timing_data'>
                                <td> <?php echo $days[$key] ?> </td>
                                <?php
                                    if ($day[0] == NULL && $day[1] == NULL) {?>
                                <td colspan='3' class='close'>Close</td>
                                <?php
                                    } else { ?>
                                        <td> <?php echo $current_post_timing[0][$key][0] ?>AM</td>
                                        <td> <?php echo $current_post_timing[0][$key][1] ?>PM</td>
                                <?php    } ?>
                            </tr>
                            <?php
                                }
                                ?>
                        </table>
                </div>
                
            </div>
        </div>
        
<!--         Slide show -->
        <div class="content_right">
            <p class="labels" >Gallery</p>
            <div class="image-gallery">
                    <?php
                    /**
                     * Image gallery display
                     */
                    $args = array(
                        'post_type' => 'attachment',
                        'numberposts' => -1,
                        'post_status' => null,
                        'post_parent' => $post->ID
                    );

                    $attachments = get_posts($args);
                    if ($attachments) {
                        foreach ($attachments as $attachment) {?>
                            <div id="gallery-image"> 
                            <?php echo wp_get_attachment_image($attachment->ID, 'full'); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
            </div>
        </div>
    </section>
</article>
<?php
}