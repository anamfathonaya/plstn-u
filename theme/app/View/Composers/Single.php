<?php

namespace App\View\Composers;

use App\Support\NewsIndex;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class Single extends Composer
{
    /** @var array */
    protected static $views = [
        'single',
    ];

    /** Data artikel tunggal + berita terkait (kategori sama). */
    public function with(): array
    {
        $post = get_queried_object();
        if (! $post instanceof WP_Post) {
            return ['article' => null, 'related' => [], 'latest' => [], 'relatedUrl' => '', 'latestUrl' => ''];
        }

        return [
            'article' => $this->article($post),
            'related' => $this->related($post),
            'relatedUrl' => $this->relatedUrl($post),
            'latest' => $this->latest($post),
            'latestUrl' => NewsIndex::url(),
        ];
    }

    protected function article(WP_Post $post): array
    {
        $thumbId = (int) get_post_thumbnail_id($post);
        $image = $thumbId ? wp_get_attachment_image_url($thumbId, 'large') : '';
        $author = (string) get_post_meta($post->ID, '_pu_author', true);
        $sourceUrl = (string) get_post_meta($post->ID, '_pu_source_url', true);

        $categories = array_map(fn ($t) => [
            'name' => $t->name,
            'url' => esc_url(get_category_link($t->term_id)),
        ], $this->categories($post));

        $tags = array_map(fn ($t) => [
            'name' => $t->name,
            'url' => esc_url(get_tag_link($t->term_id)),
        ], get_the_tags($post->ID) ?: []);

        return [
            'title' => html_entity_decode(get_the_title($post), ENT_QUOTES, 'UTF-8'),
            'kicker' => $categories[0]['name'] ?? '',
            'kickerUrl' => $categories[0]['url'] ?? '',
            'author' => $author !== '' ? $author : get_the_author_meta('display_name', (int) $post->post_author),
            'date' => get_the_date('j F Y', $post),
            'datetime' => get_the_date('c', $post),
            'excerpt' => wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)), 40),
            'image' => $image ? esc_url($image) : '',
            'imageAlt' => $thumbId ? (string) get_post_meta($thumbId, '_wp_attachment_image_alt', true) : '',
            'caption' => $thumbId ? wp_strip_all_tags((string) wp_get_attachment_caption($thumbId)) : '',
            'sourceName' => (string) get_post_meta($post->ID, '_pu_source_name', true),
            'sourceUrl' => $sourceUrl !== '' ? esc_url($sourceUrl) : '',
            'content' => wp_kses_post(apply_filters('the_content', $post->post_content)),
            'categories' => $categories,
            'tags' => $tags,
        ];
    }

    /** Kategori kiriman tanpa kategori bawaan ("Uncategorized"). */
    protected function categories(WP_Post $post): array
    {
        $default = (int) get_option('default_category');

        return array_values(array_filter(
            get_the_category($post->ID),
            fn ($t) => $t->term_id !== $default
        ));
    }

    /** Tautan "Lihat semua" Berita Terkait: arsip kategori utama. */
    protected function relatedUrl(WP_Post $post): string
    {
        $first = $this->categories($post)[0] ?? null;

        return $first ? esc_url(get_category_link($first->term_id)) : '';
    }

    /**
     * Kabar Terbaru (sidebar): kiriman terbaru selain artikel ini.
     *
     * @return array<int, array{title: string, url: string, image: string, imageAlt: string, time: string}>
     */
    protected function latest(WP_Post $post): array
    {
        $posts = $this->run([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'post__not_in' => [$post->ID],
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
        ]);

        return array_map(fn (WP_Post $p) => [
            'title' => html_entity_decode(get_the_title($p), ENT_QUOTES, 'UTF-8'),
            'url' => esc_url(get_permalink($p)),
            'image' => ($u = get_the_post_thumbnail_url($p, 'medium')) ? esc_url($u) : '',
            'imageAlt' => '',
            'time' => get_the_date('j F Y', $p),
        ], $posts);
    }

    /** @return array<int, WP_Post> */
    protected function run(array $args): array
    {
        $query = new WP_Query($args);
        update_post_thumbnail_cache($query);

        return $query->posts;
    }

    /**
     * Berita terkait: kategori sama, tanpa kiriman ini, bila kurang diisi terbaru.
     *
     * @return array<int, array{title: string, url: string, image: string, imageAlt: string, kicker: string, time: string}>
     */
    protected function related(WP_Post $post): array
    {
        $ids = array_map(fn ($t) => $t->term_id, $this->categories($post));
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => [$post->ID],
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ];

        $posts = $ids ? $this->run($args + ['category__in' => $ids]) : [];

        if (count($posts) < 3) {
            $exclude = array_merge([$post->ID], array_map(fn ($p) => $p->ID, $posts));
            $posts = array_merge($posts, $this->run([...$args, 'posts_per_page' => 3 - count($posts), 'post__not_in' => $exclude]));
        }

        return array_map(fn (WP_Post $p) => [
            'title' => html_entity_decode(get_the_title($p), ENT_QUOTES, 'UTF-8'),
            'url' => esc_url(get_permalink($p)),
            'image' => ($u = get_the_post_thumbnail_url($p, 'medium_large')) ? esc_url($u) : '',
            'imageAlt' => '',
            'kicker' => $this->categories($p)[0]->name ?? '',
            'time' => get_the_date('j F Y', $p),
        ], $posts);
    }
}
