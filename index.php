<?php

/**
 * Plugin Name: User Company Grid
 * Description: Registers Company & Department CPTs, links them to users, and prepares for team grid display.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: usercompanygrid
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Plugin Constants
 */
define('UCG_VERSION', '1.0.0');
define('UCG_PLUGIN_FILE', __FILE__);
define('UCG_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('UCG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UCG_PLUGIN_URL', plugin_dir_url(__FILE__));
// Common subdirectories (optional but recommended)
define( 'UCG_INC_DIR', UCG_PLUGIN_DIR . 'includes/' );
define( 'UCG_POST_TYPES_DIR', UCG_INC_DIR . 'post-types/' );
define( 'UCG_BLOCKS_DIR', UCG_INC_DIR . 'blocks/' );
define( 'UCG_TEMPLATES_DIR', UCG_INC_DIR . 'templates/' );
define( 'UCG_ASSETS_URL', UCG_PLUGIN_URL . 'assets/' );

// ============================
// 1. Include Required Files
// ============================
require_once UCG_PLUGIN_DIR . 'includes/user-position.php';

// ============================
// 2. Register Blocks
// ============================
function create_block_user_teamgrid_block_init() {
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'create_block_user_teamgrid_block_init' );

// ============================
// 2. Register CPT's: Company & Department
// ============================
include_once UCG_PLUGIN_DIR . 'post-types/company.php';
include_once UCG_PLUGIN_DIR . 'post-types/department.php';
// Hook into WordPress init
add_action('init', 'register_department_post_type');
add_action('init', 'register_company_post_type');


// ============================
// 3. Add Meta Box: Department -> Company Link
// ============================
add_action('add_meta_boxes', function () {
    add_meta_box(
        'department_company_box',
        __('Linked Company', 'usercompanygrid'),
        'ucg_render_department_company_meta_box',
        'department',
        'normal',
        'default'
    );
});

// Expose department -> company link in REST for filtering
add_action('init', function () {
    register_post_meta('department', '_linked_company', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'auth_callback' => '__return_true',
    ]);
});

function ucg_render_department_company_meta_box($post)
{
    $selected = get_post_meta($post->ID, '_linked_company', true);
    $companies = get_posts([
        'post_type' => 'company',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);

    echo '<select name="linked_company" style="width:100%">';
    echo '<option value="">— Select Company —</option>';
    foreach ($companies as $company) {
        printf(
            '<option value="%d" %s>%s</option>',
            $company->ID,
            selected($selected, $company->ID, false),
            esc_html($company->post_title)
        );
    }
    echo '</select>';
}

add_action('save_post_department', function ($post_id) {
    if (array_key_exists('linked_company', $_POST)) {
        $linked_company = isset($_POST['linked_company']) ? intval($_POST['linked_company']) : 0;
        if ($linked_company) {
            update_post_meta($post_id, '_linked_company', $linked_company);
        } else {
            delete_post_meta($post_id, '_linked_company');
        }
    }
});

// ============================
// 4. Add User Meta Fields (Company & Department)
// ============================
add_action('show_user_profile', 'ucg_add_user_company_department_fields');
add_action('edit_user_profile', 'ucg_add_user_company_department_fields');

function ucg_add_user_company_department_fields($user)
{
    $company_id    = get_user_meta($user->ID, 'company_id', true);
    $department_id = get_user_meta($user->ID, 'department_id', true);

    $companies = get_posts([
        'post_type' => 'company',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);

    $departments = get_posts([
        'post_type' => 'department',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);
?>
    <h3><?php _e('User Company & Department', 'usercompanygrid'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="company_id"><?php _e('Company', 'usercompanygrid'); ?></label></th>
            <td>
                <select name="company_id" id="company_id">
                    <option value="">— Select Company —</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo esc_attr($company->ID); ?>" <?php selected($company_id, $company->ID); ?>>
                            <?php echo esc_html($company->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="department_id"><?php _e('Department', 'usercompanygrid'); ?></label></th>
            <td>
                <select name="department_id" id="department_id">
                    <option value="">— Select Department —</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo esc_attr($department->ID); ?>" <?php selected($department_id, $department->ID); ?>>
                            <?php echo esc_html($department->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
<?php
}

add_action('personal_options_update', 'ucg_save_user_company_department_fields');
add_action('edit_user_profile_update', 'ucg_save_user_company_department_fields');

function ucg_save_user_company_department_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST['company_id'])) {
        $company_id = intval($_POST['company_id']);
        if ($company_id) {
            update_user_meta($user_id, 'company_id', $company_id);
        } else {
            delete_user_meta($user_id, 'company_id');
        }
    }

    if (isset($_POST['department_id'])) {
        $department_id = intval($_POST['department_id']);
        if ($department_id) {
            update_user_meta($user_id, 'department_id', $department_id);
        } else {
            delete_user_meta($user_id, 'department_id');
        }
    }
}
