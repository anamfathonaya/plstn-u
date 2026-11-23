<?php

/**
 * Theme Customizer: section "Plstn-U" (toggle Live, halaman Kabar Terkini, kategori blok beranda).
 * Nilai dibaca di View Composer (App.php, FrontPage.php), bukan di Blade.
 */

namespace App;

/** Pastikan slug adalah kategori yang ada; selain itu string kosong (slot dilewati). */
function sanitize_category_slug($value): string
{
    $slug = sanitize_title((string) $value);

    return $slug !== '' && term_exists($slug, 'category') ? $slug : '';
}

add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    $wp_customize->add_section('pu_theme', [
        'title' => __('Plstn-U', 'sage'),
        'priority' => 30,
    ]);

    // (a) Toggle Live. Disimpan sebagai option `pu_live_active` (kompatibel dengan WP-CLI).
    $wp_customize->add_setting('pu_live_active', [
        'type' => 'option',
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'capability' => 'edit_theme_options',
    ]);
    $wp_customize->add_control('pu_live_active', [
        'section' => 'pu_theme',
        'label' => __('Liputan live sedang berjalan', 'sage'),
        'description' => __('Menampilkan tombol Live di navbar.', 'sage'),
        'type' => 'checkbox',
    ]);

    $wp_customize->add_setting('pu_live_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('pu_live_url', [
        'section' => 'pu_theme',
        'label' => __('URL tombol Live (opsional)', 'sage'),
        'description' => __('Kosongkan untuk memakai halaman Kabar Terkini.', 'sage'),
        'type' => 'url',
    ]);

    // (b) Halaman Kabar Terkini.
    $wp_customize->add_setting('pu_news_page', [
        'default' => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('pu_news_page', [
        'section' => 'pu_theme',
        'label' => __('Halaman Kabar Terkini', 'sage'),
        'type' => 'dropdown-pages',
        'allow_addition' => false,
    ]);

    // (c) Kategori blok beranda (urutan = urutan tampil).
    $choices = ['' => __('(kosong)', 'sage')];
    $terms = get_terms(['taxonomy' => 'category', 'hide_empty' => false]);
    if (! is_wp_error($terms)) {
        foreach ($terms as $term) {
            $choices[$term->slug] = $term->name;
        }
    }

    // (d) Kanal & media sosial di footer. Kosong = tombol disembunyikan.
    foreach (\App\View\Composers\App::FOOTER_CHANNELS as $id => $label) {
        $wp_customize->add_setting('pu_'.$id, [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control('pu_'.$id, [
            'section' => 'pu_theme',
            /* translators: %s: nama kanal, mis. Instagram */
            'label' => sprintf(__('URL %s', 'sage'), $label),
            'description' => __('Kosongkan untuk menyembunyikan tombol di footer.', 'sage'),
            'type' => 'url',
        ]);
    }

    foreach (\App\View\Composers\FrontPage::SECTIONS as $i => $default) {
        $id = 'pu_home_category_'.($i + 1);
        $wp_customize->add_setting($id, [
            'default' => $default,
            'sanitize_callback' => __NAMESPACE__.'\sanitize_category_slug',
        ]);
        $wp_customize->add_control($id, [
            'section' => 'pu_theme',
            /* translators: %d: nomor blok */
            'label' => sprintf(__('Kategori blok beranda %d', 'sage'), $i + 1),
            'type' => 'select',
            'choices' => $choices,
        ]);
    }
});
