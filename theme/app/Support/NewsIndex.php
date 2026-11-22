<?php

namespace App\Support;

class NewsIndex
{
    /** URL indeks berita (halaman "Terkini" = posts page); cadangan ke beranda bila belum diset. */
    public static function url(): string
    {
        $id = (int) get_option('page_for_posts');
        $url = $id ? get_permalink($id) : '';

        return esc_url($url ?: home_url('/'));
    }
}
