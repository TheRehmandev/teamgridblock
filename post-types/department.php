<?php
function register_department_post_type() {
    $department_labels = [
        'name'                  => _x('Departments', 'Post Type General Name', 'usercompanygrid'),
        'singular_name'         => _x('Department', 'Post Type Singular Name', 'usercompanygrid'),
        'menu_name'             => __('Departments', 'usercompanygrid'),
        'name_admin_bar'        => __('Department', 'usercompanygrid'),
        'add_new'               => __('Add New', 'usercompanygrid'),
        'add_new_item'          => __('Add New Department', 'usercompanygrid'),
        'new_item'              => __('New Department', 'usercompanygrid'),
        'edit_item'             => __('Edit Department', 'usercompanygrid'),
        'view_item'             => __('View Department', 'usercompanygrid'),
        'all_items'             => __('All Departments', 'usercompanygrid'),
        'search_items'          => __('Search Departments', 'usercompanygrid'),
        'parent_item_colon'     => __('Parent Department:', 'usercompanygrid'),
        'not_found'             => __('No departments found.', 'usercompanygrid'),
        'not_found_in_trash'    => __('No departments found in Trash.', 'usercompanygrid'),
        'featured_image'        => __('Department Image', 'usercompanygrid'),
        'set_featured_image'    => __('Set department image', 'usercompanygrid'),
        'remove_featured_image' => __('Remove department image', 'usercompanygrid'),
        'use_featured_image'    => __('Use as department image', 'usercompanygrid'),
        'archives'              => __('Department Archives', 'usercompanygrid'),
        'insert_into_item'      => __('Insert into department', 'usercompanygrid'),
        'uploaded_to_this_item' => __('Uploaded to this department', 'usercompanygrid'),
        'filter_items_list'     => __('Filter departments list', 'usercompanygrid'),
        'items_list_navigation' => __('Departments list navigation', 'usercompanygrid'),
        'items_list'            => __('Departments list', 'usercompanygrid'),
    ];

    register_post_type('department', [
        'labels' => $department_labels,
        'public' => true,
        'menu_icon' => 'dashicons-networking',
        'supports' => ['title', 'editor'],
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'departments'],
    ]);
}


