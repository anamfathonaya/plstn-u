<?php

namespace App\View\Composers;

use App\Support\NewsIndex;
use Illuminate\Support\Facades\Vite;
use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    public function homeUrl(): string
    {
        return esc_url(home_url('/'));
    }

    /** URL logo mark (favicon) dari aset Vite. */
    public function logoUrl(): string
    {
        return esc_url(Vite::asset('resources/images/favicon.svg'));
    }

    /** Ada liputan live berjalan? Diatur lewat opsi `pu_live_active`. */
    public function liveActive(): bool
    {
        return (bool) get_option('pu_live_active', false);
    }

    /** Tujuan tombol Live (kabar terkini). */
    public function liveUrl(): string
    {
        return $this->pageUrl('kabar-terkini') ?: NewsIndex::url();
    }

    /** Tujuan pencarian. */
    public function searchUrl(): string
    {
        return esc_url(home_url('/'));
    }

    /**
     * Menu NavDrawer: kategori induk + sub-kategori (satu query, dikelompokkan di PHP).
     *
     * @return array<int, array{label: string, url: string, children: array<int, array{label: string, url: string}>}>
     */
    public function navItems(): array
    {
        $terms = $this->categoryTerms();

        $children = [];
        foreach ($terms as $term) {
            if ($term->parent) {
                $children[$term->parent][] = [
                    'label' => $term->name,
                    'url' => esc_url(get_term_link($term)),
                ];
            }
        }

        $items = [];
        foreach ($terms as $term) {
            if (! $term->parent) {
                $items[] = [
                    'label' => $term->name,
                    'url' => esc_url(get_term_link($term)),
                    'children' => $children[$term->term_id] ?? [],
                ];
            }
        }

        return $items;
    }

    /**
     * Semua kategori (tanpa kategori default), satu query per request dan dipakai bersama
     * oleh navItems() dan categoryStrip().
     *
     * @return array<int, \WP_Term>
     */
    protected function categoryTerms(): array
    {
        static $terms = null;

        if ($terms === null) {
            $found = get_terms([
                'taxonomy' => 'category',
                'hide_empty' => true,
                'exclude' => [(int) get_option('default_category')],
                'orderby' => 'name',
            ]);
            $terms = is_wp_error($found) ? [] : $found;
        }

        return $terms;
    }

    /**
     * CategoryStrip: "Terkini" + kategori induk. `active` menandai halaman yang sedang dibuka.
     *
     * @return array<int, array{label: string, url: string, active: bool}>
     */
    public function categoryStrip(): array
    {
        $items = [[
            'label' => __('Terkini', 'sage'),
            'url' => esc_url(home_url('/')),
            'active' => is_front_page(),
        ]];

        $current = is_category() ? get_queried_object_id() : 0;

        foreach ($this->categoryTerms() as $term) {
            if (! $term->parent) {
                $items[] = [
                    'label' => $term->name,
                    'url' => esc_url(get_term_link($term)),
                    'active' => $current === $term->term_id,
                ];
            }
        }

        return $items;
    }

    /**
     * QuickTabs: id => label. Hanya dua tab (DESIGN_GUIDE).
     *
     * @return array<string, string>
     */
    public function quickTabs(): array
    {
        return [
            'utama' => __('Utama', 'sage'),
            'terbaru' => __('Terbaru', 'sage'),
        ];
    }

    /**
     * Chip "Topik populer" di SearchModal: 6 kategori dengan artikel terbanyak.
     *
     * @return array<int, array{label: string, url: string}>
     */
    public function popularTopics(): array
    {
        $terms = $this->categoryTerms();
        usort($terms, fn ($a, $b) => $b->count <=> $a->count);

        return array_map(fn ($term) => [
            'label' => $term->name,
            'url' => esc_url(get_term_link($term)),
        ], array_slice($terms, 0, 6));
    }

    /** Teks kaki NavDrawer. */
    public function navFooter(): string
    {
        return sprintf('© %s %s', wp_date('Y'), get_bloginfo('name', 'display'));
    }

    /**
     * Tautan footer.
     *
     * @return array<int, array{label: string, url: string}>
     */
    public function footerLinks(): array
    {
        $links = [];
        foreach (['tentang' => __('Tentang', 'sage'), 'redaksi' => __('Redaksi', 'sage'), 'kontak' => __('Kontak', 'sage')] as $slug => $label) {
            // Hanya halaman yang benar-benar ada (situs baru belum punya halaman ini).
            if ($url = $this->pageUrl($slug)) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links;
    }

    /** Permalink halaman terbit berdasarkan slug, atau string kosong bila belum ada. */
    protected function pageUrl(string $slug): string
    {
        $page = get_page_by_path($slug, OBJECT, 'page');

        return $page && $page->post_status === 'publish' ? esc_url(get_permalink($page)) : '';
    }

    /** Teks hak cipta footer. */
    public function copyright(): string
    {
        return sprintf('© %s %s', wp_date('Y'), get_bloginfo('name', 'display'));
    }
}
