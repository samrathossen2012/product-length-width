<?php

namespace WeLabs\TestPlugin;

class NewField {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action( 'dokan_product_edit_after_product_tags', array( $this, 'add_new_field_for_edit_page' ), 19 );
        add_action('save_post', array($this, 'save_measurements_data_for_edit_page'),99);
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_new_field_for_edit_page' ), 19 );
        add_action('save_post', array($this, 'save_measurements_data_for_edit_page'),99);
    }

    /**
     * Adds the measurement fields to the submenu page.
     *
     * @param WP_Post $post The post object.
     */
    public function add_new_field_for_edit_page() {

        global $post;
        $post = get_post();

        // Retrieve the existing measurements data from wp_postmeta
        $measurements = get_post_meta($post->ID, '_measurements_data', true);

        // If data is serialized, unserialize it
        if (!empty($measurements)) {
            $measurements = unserialize($measurements);
        }
        ?>
        <div id="measurements-section">
            <h3>Measurements</h3>
            <div id="measurements-container">
                <?php if (!empty($measurements) && is_array($measurements)) : ?>
                    <?php foreach ($measurements as $index => $measurement) : ?>
                        <div class="measurement-row">
                            <input type="text" name="length[]" placeholder="Length" value="<?php echo esc_attr($measurement['length']); ?>">
                            <input type="text" name="width[]" placeholder="Width" value="<?php echo esc_attr($measurement['width']); ?>">
                            <button type="button" class="remove-row" onclick="removeRow(this)">❌</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- Check if the last row has values filled in, if so, display a new empty row -->
                <?php 
                $add_empty_row = true;
                if (!empty($measurements) && is_array($measurements)) {
                    $last_measurement = end($measurements);
                    if (empty($last_measurement['length']) || empty($last_measurement['width'])) {
                        $add_empty_row = false;
                    }
                }
                if ($add_empty_row) : ?>
                    <div class="measurement-row">
                        <input type="text" name="length[]" placeholder="Length">
                        <input type="text" name="width[]" placeholder="Width">
                        <button type="button" class="remove-row" onclick="removeRow(this)">❌</button>
                    </div>
                <?php endif; ?>
            </div> <br>
            <button type="button" id="add-row" onclick="addRow()">Add Row</button>
        </div>
        <?php
    }

    /**
     * Saves the measurements data to wp_postmeta.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_measurements_data_for_edit_page($post_id) {

        // Check if we are autosaving or the user has permissions to save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // dd('save_measurements is set');


        if (!current_user_can('edit_post', $post_id)) {
            return;
        }



        // Ensure both length and width arrays are provided and are arrays
        if (isset($_POST['length']) && is_array($_POST['length']) && isset($_POST['width']) && is_array($_POST['width'])) {

            $measurements = [];

            // Sanitize and combine lengths and widths into a single array of measurements
            foreach ($_POST['length'] as $index => $length) {
                $length = sanitize_text_field($length);
                $width = isset($_POST['width'][$index]) ? sanitize_text_field($_POST['width'][$index]) : '';

                if (!empty($length) && !empty($width)) {
                    $measurements[] = [
                        'length' => $length,
                        'width'  => $width,
                    ];
                }
            }

            // Save the measurements as serialized data in wp_postmeta
            if (!empty($measurements)) {
                update_post_meta($post_id, '_measurements_data', maybe_serialize($measurements));
            } else {
                // If no measurements are provided, delete the meta field
                update_post_meta($post_id, '_measurements_data', maybe_serialize([]));
            }
        } 
    
    }
}
