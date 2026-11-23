<?php
/**
 * Plugin Name: PU Feeds
 * Description: Penyempurnaan feed RSS Palestina Update (gambar utama, ringkasan, feed kategori, bahasa id, 20 item).
 * Version: 1.0.0
 *
 * Berada di mu-plugins agar tetap aktif saat tema diganti. Aman di Multisite:
 * hanya memakai hook standar WordPress dan tidak mengasumsikan single-site.
 */

if (! defined('ABSPATH')) {
    exit;
}

// Cegah pendaftaran ganda bila file dimuat dua kali.
if (defined('PU_FEEDS_LOADED')) {
    return;
}
define('PU_FEEDS_LOADED', true);

if (! function_exists('pu_feeds_boot')) {
    /**
     * Daftarkan semua hook. Dipanggil sekali.
     */
    function pu_feeds_boot(): void
    {
        // Bahasa kanal (judul mengikuti get_bloginfo('name')).
        add_filter('bloginfo_rss', 'pu_feeds_bloginfo', 10, 2);

        // Jumlah item per feed.
        add_filter('pre_option_posts_per_rss', 'pu_feeds_per_page');

        // Namespace media pada elemen <rss>.
        add_action('rss2_ns', 'pu_feeds_namespace');

        // Gambar utama per item.
        add_action('rss2_item', 'pu_feeds_item_media');

        // Ringkasan + tautan "Baca selengkapnya".
        add_filter('the_excerpt_rss', 'pu_feeds_excerpt');

        // Iklankan feed kategori di <head>.
        add_action('wp_head', 'pu_feeds_category_link', 1);

        // Iklankan feed utama di <head> lewat feed_links() core (hanya aktif
        // dengan dukungan ini). Di sini, bukan di tema, agar tetap ada saat tema diganti.
        add_action('after_setup_theme', 'pu_feeds_theme_support', 20);
    }
}

if (! function_exists('pu_feeds_theme_support')) {
    function pu_feeds_theme_support(): void
    {
        add_theme_support('automatic-feed-links');
    }
}

if (! function_exists('pu_feeds_bloginfo')) {
    function pu_feeds_bloginfo($output, $show = '')
    {
        if ($show === 'language') {
            // Format WordPress (mis. "id-ID") valid untuk <language> RSS.
            $language = get_option('WPLANG') ?: get_locale();

            return $language ? str_replace('_', '-', $language) : 'id';
        }

        return $output;
    }
}

if (! function_exists('pu_feeds_per_page')) {
    function pu_feeds_per_page()
    {
        return 20;
    }
}

if (! function_exists('pu_feeds_namespace')) {
    function pu_feeds_namespace(): void
    {
        echo "\n\txmlns:media=\"http://search.yahoo.com/mrss/\"";
    }
}

if (! function_exists('pu_feeds_item_media')) {
    function pu_feeds_item_media(): void
    {
        $post = get_post();
        if (! $post) {
            return;
        }

        $thumb_id = get_post_thumbnail_id($post);
        if (! $thumb_id) {
            return;
        }

        $url = wp_get_attachment_url($thumb_id);
        if (! $url) {
            return;
        }

        $mime = get_post_mime_type($thumb_id) ?: 'image/jpeg';
        $file = get_attached_file($thumb_id);
        $length = ($file && is_readable($file)) ? (int) filesize($file) : 0;
        $meta = wp_get_attachment_metadata($thumb_id);

        $attrs = sprintf(' url="%s" type="%s"', esc_url($url), esc_attr($mime));
        if ($length > 0) {
            $attrs .= sprintf(' length="%d"', $length);
        }

        echo "\t\t<enclosure" . $attrs . " />\n";

        $media = sprintf(' url="%s" type="%s" medium="image"', esc_url($url), esc_attr($mime));
        if (is_array($meta) && ! empty($meta['width']) && ! empty($meta['height'])) {
            $media .= sprintf(' width="%d" height="%d"', (int) $meta['width'], (int) $meta['height']);
        }
        if ($length > 0) {
            $media .= sprintf(' fileSize="%d"', $length);
        }

        echo "\t\t<media:content" . $media . " />\n";
    }
}

if (! function_exists('pu_feeds_excerpt')) {
    function pu_feeds_excerpt($excerpt)
    {
        $post = get_post();
        $text = trim(wp_strip_all_tags((string) $excerpt));
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text);

        if ($post) {
            $text .= ' Baca selengkapnya: ' . esc_url(get_permalink($post));
        }

        // Cegah penutupan CDATA dini bila teks memuat "]]>".
        return str_replace(']]>', ']]]]><![CDATA[>', trim($text));
    }
}

if (! function_exists('pu_feeds_category_link')) {
    function pu_feeds_category_link(): void
    {
        if (! is_category()) {
            return;
        }

        $term = get_queried_object();
        if (! $term || empty($term->term_id)) {
            return;
        }

        $href = get_category_feed_link($term->term_id);
        if (is_wp_error($href) || ! $href) {
            return;
        }

        // Hindari duplikat dengan tautan kategori bawaan core.
        remove_action('wp_head', 'feed_links_extra', 3);

        printf(
            "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"%s\" href=\"%s\" />\n",
            esc_attr(get_bloginfo('name') . ' » ' . $term->name),
            esc_url($href)
        );
    }
}

pu_feeds_boot();
