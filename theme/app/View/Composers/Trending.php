<?php

namespace App\View\Composers;

use App\Support\PostCard;
use Roots\Acorn\View\Composer;

class Trending extends Composer
{
    /** @var array */
    protected static $views = [
        'components.trending-list',
    ];

    /**
     * Terpopuler: bila plugin Post Views Counter aktif, urut berdasarkan view 7 hari terakhir;
     * bila tidak, kembali ke kiriman terbaru. Satu query, tanpa hitung baris.
     *
     * @return array<int, array{title: string, url: string}>
     */
    public function trending(): array
    {
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];

        if (function_exists('pvc_get_post_views')) {
            // Plugin menyambung ke WP_Query lewat filter, jadi filter tidak boleh ditekan.
            $args['suppress_filters'] = false;
            $args['orderby'] = 'post_views';
            $args['order'] = 'DESC';
            $args['views_query'] = ['after' => '7 days ago', 'inclusive' => true];
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        }

        $posts = (new \WP_Query($args))->posts;

        // Tidak ada view dalam 7 hari terakhir: tampilkan terbaru.
        if (! $posts && isset($args['views_query'])) {
            unset($args['views_query'], $args['suppress_filters']);
            $args['orderby'] = 'date';
            $posts = (new \WP_Query($args))->posts;
        }

        if (! $posts) {
            return [];
        }

        return array_map(fn ($post) => [
            'title' => PostCard::title($post),
            'url' => esc_url(get_permalink($post)),
        ], $posts);
    }
}
