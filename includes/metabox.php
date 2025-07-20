<?php
if (!defined('ABSPATH')) exit;

/**
 * Adds a custom meta box for scheduling Sendy integration on posts.
 */

function post2sendy_add_meta_box() {
    add_meta_box(
        'post2sendy_meta_box',
        __('Sendy Email Scheduling', 'post-to-sendy'),
        'post2sendy_meta_box_markup',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'post2sendy_add_meta_box');

function post2sendy_meta_box_markup($post) {
    $optimal = get_optimal_post_datetime();
    $selected = get_post_meta($post->ID, 'post2sendy_radio', true) ?: 'Never';
    $custom_date = get_post_meta($post->ID, 'post2sendy_date', true);
    $custom_time = get_post_meta($post->ID, 'post2sendy_time', true);

    wp_nonce_field('post2sendy_meta_box', 'post2sendy_meta_box_nonce');
    ?>
    <p><label><input type="radio" name="post2sendy_radio" value="Never" <?php checked($selected, 'Never'); ?> /> <?php _e('Never', 'post-to-sendy'); ?></label></p>
    <p><label><input type="radio" name="post2sendy_radio" value="Optimal" <?php checked($selected, 'Optimal'); ?> /> <?php _e('Optimal time', 'post-to-sendy'); ?> <em>(<?php echo esc_html($optimal); ?>)</em></label></p>
    <p><label><input type="radio" name="post2sendy_radio" value="Custom" <?php checked($selected, 'Custom'); ?> /> <?php _e('Custom date/time', 'post-to-sendy'); ?></label></p>
    <p>
        <input type="date" name="post2sendy_date" value="<?php echo esc_attr($custom_date); ?>" />
        <input type="time" name="post2sendy_time" value="<?php echo esc_attr($custom_time); ?>" />
    </p>
    <?php
}

function post2sendy_save_post_meta($post_id) {
    if (!isset($_POST['post2sendy_meta_box_nonce']) || !wp_verify_nonce($_POST['post2sendy_meta_box_nonce'], 'post2sendy_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, 'post2sendy_radio', sanitize_text_field($_POST['post2sendy_radio'] ?? ''));
    update_post_meta($post_id, 'post2sendy_date', sanitize_text_field($_POST['post2sendy_date'] ?? ''));
    update_post_meta($post_id, 'post2sendy_time', sanitize_text_field($_POST['post2sendy_time'] ?? ''));
}
add_action('save_post', 'post2sendy_save_post_meta');
