<?php

namespace App\Support;

use WP_Post;

/** Bentuk data kartu berita yang dipakai bersama oleh semua View Composer. */
class PostCard
{
    /** Judul tanpa entitas HTML (Blade meng-escape sendiri; hindari escape ganda). */
    public static function title(WP_Post $post): string
    {
        return html_entity_decode(get_the_title($post), ENT_QUOTES, 'UTF-8');
    }

    public static function image(WP_Post $post, string $size): string
    {
        $url = get_the_post_thumbnail_url($post, $size);

        return $url ? esc_url($url) : '';
    }

    /**
     * Kategori kiriman tanpa kategori bawaan ("Uncategorized").
     *
     * @return array<int, \WP_Term>
     */
    public static function categories(WP_Post $post): array
    {
        $default = (int) get_option('default_category');

        return array_values(array_filter(
            get_the_category($post->ID),
            fn ($term) => $term->term_id !== $default
        ));
    }

    /** Nama kategori utama (kosong bila hanya "Uncategorized"). */
    public static function kicker(WP_Post $post): string
    {
        return static::categories($post)[0]->name ?? '';
    }

    /** @return array{title: string, url: string, image: string, imageAlt: string, time: string} */
    public static function make(WP_Post $post, string $size, string $time): array
    {
        return [
            'title' => static::title($post),
            'url' => esc_url(get_permalink($post)),
            'image' => static::image($post, $size),
            'imageAlt' => '',
            'time' => $time,
        ];
    }
}
