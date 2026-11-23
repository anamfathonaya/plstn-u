<?php

namespace App\View\Composers;

use App\Support\PostCard;
use Roots\Acorn\View\Composer;
use WP_Post;

class Archive extends Composer
{
    /** @var array */
    protected static $views = [
        'index',
        'home',
        'search',
        'author',
        'archive-video',
        'archive-infografik',
    ];

    /** Data arsip (kategori/tag/penulis/tanggal) dan hasil pencarian dari main query. */
    public function with(): array
    {
        global $wp_query;

        $posts = is_array($wp_query->posts ?? null) ? $wp_query->posts : [];
        if ($posts) {
            update_post_thumbnail_cache($wp_query);
        }
        $current = max(1, (int) get_query_var('paged'));
        $total = max(1, (int) ($wp_query->max_num_pages ?? 1));

        return [
            'archive' => [
                'title' => $this->title(),
                'description' => $this->description(),
                'query' => is_search() ? get_search_query(false) : '',
                'isSearch' => is_search(),
                'count' => (int) ($wp_query->found_posts ?? count($posts)),
                'items' => array_map(fn (WP_Post $p) => $this->item($p), $posts),
                'pagination' => $this->pagination($current, $total),
                'searchAction' => esc_url(home_url('/')),
            ],
        ];
    }

    protected function title(): string
    {
        if (is_search()) {
            return sprintf('Hasil pencarian: “%s”', get_search_query(false));
        }
        if (is_category() || is_tag() || is_tax()) {
            return html_entity_decode((string) single_term_title('', false), ENT_QUOTES, 'UTF-8');
        }
        if (is_author()) {
            return (string) get_the_author_meta('display_name', (int) get_queried_object_id());
        }
        if (is_post_type_archive()) {
            return (string) post_type_archive_title('', false);
        }
        if (is_home()) {
            return 'Berita Terbaru';
        }

        return wp_strip_all_tags(get_the_archive_title());
    }

    protected function description(): string
    {
        if (is_search()) {
            return '';
        }

        return wp_strip_all_tags(get_the_archive_description());
    }

    /** @return array{title: string, url: string, image: string, imageAlt: string, kicker: string, time: string} */
    protected function item(WP_Post $post): array
    {
        return PostCard::make($post, 'medium_large', get_the_date('j F Y', $post)) + ['kicker' => PostCard::kicker($post)];
    }

    /**
     * Paginasi: prev/next + nomor halaman (jendela di sekitar halaman aktif, null = elipsis).
     *
     * @return array{prev: ?string, next: ?string, pages: array, current: int, total: int}
     */
    protected function pagination(int $current, int $total): array
    {
        $url = fn (int $n) => esc_url(get_pagenum_link($n));

        $numbers = array_values(array_unique(array_filter(
            array_merge([1, $total], range($current - 1, $current + 1)),
            fn ($n) => $n >= 1 && $n <= $total
        )));
        sort($numbers);

        $pages = [];
        $last = 0;
        foreach ($numbers as $n) {
            if ($last && $n - $last > 1) {
                $pages[] = null;
            }
            $pages[] = ['label' => (string) $n, 'url' => $url($n), 'current' => $n === $current];
            $last = $n;
        }

        return [
            'prev' => $current > 1 ? $url($current - 1) : null,
            'next' => $current < $total ? $url($current + 1) : null,
            'pages' => $total > 1 ? $pages : [],
            'current' => $current,
            'total' => $total,
        ];
    }
}
