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

    /** URL logo: custom logo Customizer bila ada, jika tidak logo mark (favicon) dari aset Vite. */
    public function logoUrl(): string
    {
        $id = (int) get_theme_mod('custom_logo', 0);
        if ($id && ($custom = wp_get_attachment_image_url($id, 'full'))) {
            return esc_url($custom);
        }

        return esc_url(Vite::asset('resources/images/favicon.svg'));
    }

    /** Logo mark bawaan (favicon) untuk placeholder gambar, tidak terpengaruh logo kustom. */
    public function markUrl(): string
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
        $custom = esc_url((string) get_theme_mod('pu_live_url', ''));
        if ($custom !== '') {
            return $custom;
        }

        $id = (int) get_theme_mod('pu_news_page', 0);
        if ($id && get_post_status($id) === 'publish') {
            return esc_url(get_permalink($id));
        }

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
        if ($menu = $this->menuTree('primary_navigation')) {
            return $menu;
        }

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
        if ($menu = $this->menuTree('primary_navigation')) {
            return array_map(fn ($item) => [
                'label' => $item['label'],
                'url' => $item['url'],
                'active' => $item['active'],
            ], $menu);
        }

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
        if ($menu = $this->menuTree('footer_navigation')) {
            return array_map(fn ($item) => ['label' => $item['label'], 'url' => $item['url']], $menu);
        }

        $links = [];
        foreach (['tentang' => __('Tentang', 'sage'), 'redaksi' => __('Redaksi', 'sage'), 'kontak' => __('Kontak', 'sage')] as $slug => $label) {
            // Hanya halaman yang benar-benar ada (situs baru belum punya halaman ini).
            if ($url = $this->pageUrl($slug)) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links;
    }

    /**
     * Item menu WordPress pada lokasi tertentu sebagai pohon bersih (dua tingkat),
     * atau array kosong bila lokasi belum diberi menu (pemanggil memakai cadangan).
     *
     * @return array<int, array{label: string, url: string, active: bool, children: array<int, array{label: string, url: string, active: bool}>}>
     */
    protected function menuTree(string $location): array
    {
        static $cache = [];

        if (isset($cache[$location])) {
            return $cache[$location];
        }

        $locations = get_nav_menu_locations();
        $menu = has_nav_menu($location) && ! empty($locations[$location]) ? wp_get_nav_menu_items($locations[$location]) : [];
        if (! $menu || is_wp_error($menu)) {
            return $cache[$location] = [];
        }

        $nodes = [];
        foreach ($menu as $item) {
            $url = esc_url((string) $item->url);
            if ($url === '' || $item->title === '') {
                continue;
            }
            $nodes[(int) $item->ID] = [
                'parent' => (int) $item->menu_item_parent,
                'label' => wp_strip_all_tags($item->title),
                'url' => $url,
                'active' => $this->menuItemActive($item),
                'children' => [],
            ];
        }

        $tree = [];
        foreach ($nodes as $id => $node) {
            $parent = $node['parent'];
            unset($node['parent']);
            if ($parent && isset($nodes[$parent])) {
                // Dua tingkat: cucu ikut ke induk tingkat atas.
                $top = $parent;
                while ($nodes[$top]['parent'] && isset($nodes[$nodes[$top]['parent']])) {
                    $top = $nodes[$top]['parent'];
                }
                $nodes[$top]['children'][] = ['label' => $node['label'], 'url' => $node['url'], 'active' => $node['active']];
            } else {
                $tree[$id] = $node;
            }
        }

        // Salinan anak dari $nodes ke $tree (urutan iterasi sudah mengikuti menu_order).
        $result = [];
        foreach ($tree as $id => $node) {
            $node['children'] = $nodes[$id]['children'];
            $result[] = $node;
        }

        return $cache[$location] = $result;
    }

    /** Apakah item menu menunjuk halaman/kategori yang sedang dibuka? */
    protected function menuItemActive(object $item): bool
    {
        $id = (int) $item->object_id;

        return match ($item->type) {
            'taxonomy' => (is_category() || is_tag() || is_tax()) && get_queried_object_id() === $id,
            'post_type' => is_singular() && get_queried_object_id() === $id,
            default => is_front_page() && untrailingslashit((string) $item->url) === untrailingslashit(home_url('/')),
        };
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
