<?php
function register_company_post_type() {
    $company_labels = [
        'name'                  => _x('Companies', 'Post Type General Name', 'usercompanygrid'),
        'singular_name'         => _x('Company', 'Post Type Singular Name', 'usercompanygrid'),
        'menu_name'             => __('Companies', 'usercompanygrid'),
        'name_admin_bar'        => __('Company', 'usercompanygrid'),
        'add_new'               => __('Add New', 'usercompanygrid'),
        'add_new_item'          => __('Add New Company', 'usercompanygrid'),
        'new_item'              => __('New Company', 'usercompanygrid'),
        'edit_item'             => __('Edit Company', 'usercompanygrid'),
        'view_item'             => __('View Company', 'usercompanygrid'),
        'all_items'             => __('All Companies', 'usercompanygrid'),
        'search_items'          => __('Search Companies', 'usercompanygrid'),
        'parent_item_colon'     => __('Parent Company:', 'usercompanygrid'),
        'not_found'             => __('No companies found.', 'usercompanygrid'),
        'not_found_in_trash'    => __('No companies found in Trash.', 'usercompanygrid'),
        'featured_image'        => __('Company Logo', 'usercompanygrid'),
        'set_featured_image'    => __('Set company logo', 'usercompanygrid'),
        'remove_featured_image' => __('Remove company logo', 'usercompanygrid'),
        'use_featured_image'    => __('Use as company logo', 'usercompanygrid'),
        'archives'              => __('Company Archives', 'usercompanygrid'),
        'insert_into_item'      => __('Insert into company', 'usercompanygrid'),
        'uploaded_to_this_item' => __('Uploaded to this company', 'usercompanygrid'),
        'filter_items_list'     => __('Filter companies list', 'usercompanygrid'),
        'items_list_navigation' => __('Companies list navigation', 'usercompanygrid'),
        'items_list'            => __('Companies list', 'usercompanygrid'),
    ];

    register_post_type('company', [
        'labels'       => $company_labels, // ✅ FIXED: should be 'labels', not 'label'
        'public'       => true,
        'menu_icon'    => 'dashicons-building',
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'companies'],
    ]);
}


