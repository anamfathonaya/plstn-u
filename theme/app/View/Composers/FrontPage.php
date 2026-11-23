<?php

namespace App\View\Composers;

use App\Support\NewsIndex;
use App\Support\PostCard;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class FrontPage extends Composer
{
    /** @var array */
    protected static $views = [
        'front-page',
    ];

    /** Bawaan rubrik blok kartu bila Customizer belum diisi (slug kategori, berurutan). */
    public const SECTIONS = ['gaza', 'tepi-barat', 'internasional'];

    /**
     * Rubrik blok kartu dari Customizer (3 slot), slot kosong dilewati, duplikat dibuang.
     *
     * @return array<int, string>
     */
    protected function sectionSlugs(): array
    {
        $slugs = [];
        foreach (self::SECTIONS as $i => $default) {
            $slug = (string) get_theme_mod('pu_home_category_'.($i + 1), $default);
            if ($slug !== '') {
                $slugs[] = $slug;
            }
        }

        return array_values(array_unique($slugs));
    }

    /** Ukuran kolam kiriman terbaru (satu query untuk seluruh beranda). */
    protected const POOL = 40;

    /** ID kiriman yang sudah tampil, untuk mencegah duplikasi antar blok. */
    protected array $used = [];

    /**
     * Beranda dari query WordPress asli.
     * Satu query "kolam" terbaru (hero, kolom samping, pembaruan, kabar terbaru),
     * lalu satu query per rubrik dengan post__not_in. Tanpa hitung baris.
     */
    public function with(): array
    {
        // Satu query kolam besar (hero, kolom, pembaruan, kabar terbaru + isi rubrik).
        // 14 pertama untuk blok atas; sisanya cadangan untuk kartu rubrik.
        $all = $this->query(['posts_per_page' => self::POOL]);
        $pool = array_slice($all, 0, 14);
        $rest = array_slice($all, 14);
        $heroPost = $this->takeWithImage($pool) ?? array_shift($pool);

        $updates = $this->take($pool, 3);
        $leftLead = $this->takeWithImage($pool) ?? array_shift($pool);
        $left = $this->take($pool, 2);
        $rightLead = $this->takeWithImage($pool) ?? array_shift($pool);
        $right = $this->take($pool, 2);
        $latest = $this->take($pool, 4);

        $sections = [];
        if ($latest) {
            $sections[] = [
                'slot' => 'news',
                'title' => 'Kabar Terbaru',
                'url' => NewsIndex::url(),
                'items' => $this->items($latest),
            ];
        }

        $slugs = $this->sectionSlugs();
        $terms = [];
        foreach (! $slugs ? [] : get_terms(['taxonomy' => 'category', 'slug' => $slugs, 'hide_empty' => false]) as $t) {
            $terms[$t->slug] = $t;
        }

        foreach ($slugs as $slug) {
            $term = $terms[$slug] ?? null;
            if (! $term) {
                continue;
            }
            // Ambil dari kolam (tanpa query tambahan); query hanya bila kolam kurang.
            $posts = $this->fromRest($rest, $term->term_id, 4);
            if (count($posts) < 4) {
                $posts = array_merge($posts, $this->query([
                    'posts_per_page' => 4 - count($posts),
                    'cat' => $term->term_id,
                    'post__not_in' => array_merge($this->used, array_map(fn ($p) => $p->ID, $posts)),
                ]));
            }
            // Rubrik kurang kartu: isi dari kiriman terbaru lain yang belum tampil.
            if (count($posts) < 4) {
                $posts = array_merge($posts, $this->fromRest($rest, null, 4 - count($posts)));
            }
            if (count($posts) < 4) {
                $exclude = array_merge($this->used, array_map(fn ($p) => $p->ID, $posts));
                $posts = array_merge($posts, $this->query([
                    'posts_per_page' => 4 - count($posts),
                    'post__not_in' => $exclude,
                ]));
            }
            if (! $posts) {
                continue;
            }
            foreach ($posts as $p) {
                $this->used[] = $p->ID;
            }
            $sections[] = [
                'slot' => 'cards',
                'title' => $term->name,
                'url' => esc_url(get_category_link($term->term_id)),
                'items' => $this->items($posts),
            ];
        }

        // Kabar Terbaru dijadikan blok kedua agar mengikuti urutan desain (rubrik, terbaru, rubrik).
        if (count($sections) > 2 && $sections[0]['slot'] === 'news') {
            array_splice($sections, 1, 0, array_splice($sections, 0, 1));
        }

        return [
            'hasPosts' => (bool) $all,
            'heroSides' => (int) ($leftLead !== null) + (int) ($rightLead !== null),
            'hero' => $heroPost ? $this->hero($heroPost, $updates) : $this->emptyHero(),
            'heroLeft' => ['lead' => $this->item($leftLead), 'items' => $this->items($left)],
            'heroRight' => ['lead' => $this->item($rightLead), 'items' => $this->items($right)],
            'sections' => $sections,
            'newsLatest' => $this->items($latest),
        ];
    }

    /**
     * Query terbaru dengan cache meta/term aktif (gambar unggulan & kategori tanpa N+1).
     *
     * @return array<int, WP_Post>
     */
    protected function query(array $args): array
    {
        $query = new WP_Query($args + [
            'post_type' => 'post',
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        update_post_thumbnail_cache($query);

        return $query->posts;
    }

    /**
     * Ambil hingga $n kiriman dari cadangan (opsional dalam kategori $termId)
     * yang belum tampil, dan buang dari cadangan. Memakai cache term, tanpa query.
     *
     * @return array<int, WP_Post>
     */
    protected function fromRest(array &$rest, ?int $termId, int $n): array
    {
        // Seperti 'cat' di WP_Query: termasuk turunan kategori.
        $ids = $termId === null ? [] : array_merge([$termId], get_term_children($termId, 'category'));
        $out = [];
        foreach ($rest as $i => $post) {
            if (count($out) >= $n) {
                break;
            }
            if (in_array($post->ID, $this->used, true)) {
                continue;
            }
            if ($ids && ! array_intersect($ids, wp_list_pluck(get_the_category($post->ID), 'term_id'))) {
                continue;
            }
            $out[] = $post;
            unset($rest[$i]);
        }

        return $out;
    }

    /** Ambil satu kiriman pertama yang punya gambar unggulan (dan hapus dari kolam). */
    protected function takeWithImage(array &$pool): ?WP_Post
    {
        foreach ($pool as $i => $post) {
            if (has_post_thumbnail($post)) {
                unset($pool[$i]);
                $pool = array_values($pool);
                $this->used[] = $post->ID;

                return $post;
            }
        }

        return null;
    }

    /** @return array<int, WP_Post> */
    protected function take(array &$pool, int $n): array
    {
        $taken = array_splice($pool, 0, $n);
        foreach ($taken as $post) {
            $this->used[] = $post->ID;
        }

        return $taken;
    }

    /** @param array<int, WP_Post> $posts */
    protected function items(array $posts): array
    {
        return array_map(fn ($post) => $this->item($post), $posts);
    }

    /** @return array{title: string, url: string, image: string, imageAlt: string, kicker: string, time: string} */
    protected function item(?WP_Post $post): array
    {
        if (! $post) {
            return ['title' => '', 'url' => '', 'image' => '', 'imageAlt' => '', 'kicker' => '', 'time' => ''];
        }
        $this->used[] = $post->ID;

        return PostCard::make($post, 'large', $this->when($post)) + ['kicker' => PostCard::kicker($post)];
    }

    /** @param array<int, WP_Post> $updates */
    protected function hero(WP_Post $post, array $updates): array
    {
        return [
            'title' => PostCard::title($post),
            'url' => esc_url(get_permalink($post)),
            'image' => PostCard::image($post, 'full'),
            'imageAlt' => '',
            'excerpt' => wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)), 30),
            'updated' => $this->when($post),
            'updates' => array_map(fn ($u) => [
                'time' => $this->ago($u, false),
                'title' => PostCard::title($u),
                'url' => esc_url(get_permalink($u)),
            ], $updates),
        ];
    }

    protected function emptyHero(): array
    {
        return ['title' => '', 'url' => '', 'image' => '', 'imageAlt' => '', 'excerpt' => '', 'updated' => '', 'updates' => []];
    }

    /** Waktu relatif ("2 jam lalu") untuk kiriman < 7 hari, selain itu tanggal. */
    protected function when(WP_Post $post): string
    {
        $stamp = get_post_time('U', true, $post);
        if (time() - $stamp >= 7 * DAY_IN_SECONDS) {
            return get_the_date('j F Y', $post);
        }

        return $this->ago($post, true);
    }

    protected function ago(WP_Post $post, bool $suffix): string
    {
        $diff = max(0, time() - (int) get_post_time('U', true, $post));
        [$n, $unit] = match (true) {
            $diff < HOUR_IN_SECONDS => [max(1, intdiv($diff, MINUTE_IN_SECONDS)), 'menit'],
            $diff < DAY_IN_SECONDS => [intdiv($diff, HOUR_IN_SECONDS), 'jam'],
            default => [intdiv($diff, DAY_IN_SECONDS), 'hari'],
        };

        return "{$n} {$unit}".($suffix ? ' lalu' : '');
    }
}
