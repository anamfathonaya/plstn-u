<?php
/**
 * Plugin Name: PU Content Types
 * Description: CPT multimedia Palestina Update (video, infografik) memakai kategori dan tag bawaan.
 * Version: 1.0.0
 *
 * Berada di mu-plugins agar data editorial aman saat tema diganti.
 */

if (! defined('ABSPATH')) {
    exit;
}

if (defined('PU_CONTENT_TYPES_LOADED')) {
    return;
}
define('PU_CONTENT_TYPES_LOADED', true);

if (! function_exists('pu_content_types_register')) {
    /**
     * Daftarkan CPT video & infografik, lalu hubungkan ke category dan post_tag.
     */
    function pu_content_types_register(): void
    {
        $types = [
            'video' => ['Video', 'Video', 'dashicons-video-alt3'],
            'infografik' => ['Infografik', 'Infografik', 'dashicons-chart-bar'],
        ];

        foreach ($types as $slug => [$plural, $singular, $icon]) {
            register_post_type($slug, [
                'labels' => [
                    'name' => $plural,
                    'singular_name' => $singular,
                    'menu_name' => $plural,
                    'add_new' => 'Tambah Baru',
                    'add_new_item' => "Tambah $singular Baru",
                    'edit_item' => "Sunting $singular",
                    'new_item' => "$singular Baru",
                    'view_item' => "Lihat $singular",
                    'view_items' => "Lihat $plural",
                    'search_items' => "Cari $plural",
                    'not_found' => "$plural tidak ditemukan",
                    'not_found_in_trash' => "$plural tidak ditemukan di tempat sampah",
                    'all_items' => "Semua $plural",
                    'archives' => "Arsip $plural",
                    'featured_image' => 'Gambar utama',
                    'set_featured_image' => 'Setel gambar utama',
                    'remove_featured_image' => 'Hapus gambar utama',
                    'use_featured_image' => 'Gunakan sebagai gambar utama',
                ],
                'public' => true,
                'has_archive' => true,
                'show_in_rest' => true,
                'menu_icon' => $icon,
                'menu_position' => 6,
                'rewrite' => ['slug' => $slug, 'with_front' => false],
                'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
                'taxonomies' => ['category', 'post_tag'],
            ]);

            register_taxonomy_for_object_type('category', $slug);
            register_taxonomy_for_object_type('post_tag', $slug);
        }
    }
    add_action('init', 'pu_content_types_register');
}
