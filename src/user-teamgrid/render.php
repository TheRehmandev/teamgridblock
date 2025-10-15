<?php

/**
 * Server-side render for Team Grid block
 */

if (! function_exists('render_usercompanygrid_team_grid')) {
    function render_usercompanygrid_team_grid(array $attributes): string
    {
        $company_id    = isset($attributes['companyId']) ? absint($attributes['companyId']) : 0;
        $department_id = isset($attributes['departmentId']) ? absint($attributes['departmentId']) : 0;

        if (! $company_id || ! $department_id) {
            return '<div ' . get_block_wrapper_attributes() . '><p>Select a company and department.</p></div>';
        }

        // $users = get_users( [
        //     'meta_query' => [
        //         'relation' => 'AND',
        //         [ 'key' => 'company_id', 'value' => (string) $company_id, 'compare' => '=' ],
        //         [ 'key' => 'department_id', 'value' => (string) $department_id, 'compare' => '=' ],
        //     ],
        //     'orderby' => 'display_name',
        //     'order'   => 'ASC',
        // ] );
        $users = get_users([
            'meta_query' => [
                'relation' => 'AND',
                ['key' => 'company_id', 'value' => (string) $company_id, 'compare' => '='],
                ['key' => 'department_id', 'value' => (string) $department_id, 'compare' => '='],
            ],
            'meta_key' => 'position',           // ✅ sort by position meta field
            'orderby'  => 'meta_value_num',     // ✅ numeric sort
            'order'    => 'DESC',               // ✅ highest number first (e.g., managers > juniors)
        ]);


        if (empty($users)) {
            return '<div ' . get_block_wrapper_attributes() . '><p>No users found.</p></div>';
        }

        ob_start();
?>
        <div <?php echo get_block_wrapper_attributes(['class' => 'wp-block-usercompanygrid-team-grid']); ?>>
            <div class="team-grid-container">
                <?php foreach ($users as $user) :
                    $avatar      = get_avatar($user->ID, 72);
                    $displayName = esc_html($user->display_name);
                    $email       = esc_html($user->user_email);
                    $subtitle    = $email ? $email : '';
                    // ✅ Get numeric position
                    $position_value = get_user_meta($user->ID, 'position', true);

                    // ✅ Map numeric values to labels
                    $position_labels = [
                        1 => __('Junior', 'usercompanygrid'),
                        2 => __('Associate', 'usercompanygrid'),
                        3 => __('Senior', 'usercompanygrid'),
                        4 => __('Team Lead', 'usercompanygrid'),
                        5 => __('Manager', 'usercompanygrid'),
                    ];

                    // ✅ Match label or fallback
                    $position_label = isset($position_labels[$position_value]) ? $position_labels[$position_value] : '';

                ?>
                    <div class="team-member-card">
                        <div class="team-member-left">
                            <div class="team-member-avatar"><?php echo $avatar; ?></div>
                            <div class="team-member-info">
                                <h3 class="item-title"><?php echo $displayName; ?></h3>
                                <?php if ($position_label) : ?>
                                    <p class="item-position"><?php echo esc_html($position_label); ?></p>
                                <?php endif; ?>
                                <?php if ($subtitle) : ?>
                                    <p class="item-subtitle"><?php echo $subtitle; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="team-member-right">
                            <a class="item-cta" href="mailto:<?php echo $email ? $email : '#'; ?>">
                                <span>Kontakt aufnehmen</span>
                                <span class="arrow">➔</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

echo render_usercompanygrid_team_grid(isset($attributes) ? $attributes : []);
