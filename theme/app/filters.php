<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Beranda memakai query sendiri di View Composer; batasi main query agar murah.
 */
add_action('pre_get_posts', function (\WP_Query $query) {
    if (! is_admin() && $query->is_main_query() && $query->is_front_page()) {
        $query->set('posts_per_page', 1);
        $query->set('no_found_rows', true);
        $query->set('update_post_meta_cache', false);
        $query->set('update_post_term_cache', false);
    }
});

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});
