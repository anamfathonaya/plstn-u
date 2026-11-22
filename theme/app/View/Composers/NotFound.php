<?php

namespace App\View\Composers;

use App\Support\NewsIndex;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class NotFound extends Composer
{
    /** @var array */
    protected static $views = [
        '404',
    ];

    /** Daftar Kabar Terbaru untuk halaman 404. */
    public function with(): array
    {
        $query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
        update_post_thumbnail_cache($query);

        return [
            'latest' => array_map(fn (WP_Post $p) => [
                'title' => html_entity_decode(get_the_title($p), ENT_QUOTES, 'UTF-8'),
                'url' => esc_url(get_permalink($p)),
                'image' => ($u = get_the_post_thumbnail_url($p, 'medium_large')) ? esc_url($u) : '',
                'imageAlt' => '',
                'kicker' => '',
                'time' => get_the_date('j F Y', $p),
            ], $query->posts),
            'latestUrl' => NewsIndex::url(),
        ];
    }
}
