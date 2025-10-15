<?php
function ucg_register_user_position_meta() {
    register_meta('user', 'position', [
        'type' => 'number',
        'description' => 'User position level',
        'single' => true,
        'show_in_rest' => true,
        'default' => 1,
    ]);
}
add_action('init', 'ucg_register_user_position_meta');

// Define positions array
function ucg_get_positions() {
    return [
        1 => __('Junior', 'usercompanygrid'),
        2 => __('Associate', 'usercompanygrid'),
        3 => __('Senior', 'usercompanygrid'),
        4 => __('Team Lead', 'usercompanygrid'),
        5 => __('Manager', 'usercompanygrid'),
    ];
}

// Add position field to user profile
function ucg_add_position_field($user) {
    $positions = ucg_get_positions();
    $current_position = get_user_meta($user->ID, 'position', true) ?: 1;
    ?>
    <h3><?php _e('Position Information', 'usercompanygrid'); ?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="position"><?php _e('Position', 'usercompanygrid'); ?></label>
            </th>
            <td>
                <select name="position" id="position">
                    <?php foreach ($positions as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($current_position, $value); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php _e('Select the user\'s position in the company.', 'usercompanygrid'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'ucg_add_position_field');
add_action('edit_user_profile', 'ucg_add_position_field');

// Save position field
function ucg_save_position_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['position'])) {
        $position = intval($_POST['position']);
        if ($position >= 1 && $position <= 5) {
            update_user_meta($user_id, 'position', $position);
        }
    }
}
add_action('personal_options_update', 'ucg_save_position_field');
add_action('edit_user_profile_update', 'ucg_save_position_field');