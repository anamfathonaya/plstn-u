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

    /** Kanal "Ikuti di" footer (kunci = ikon + theme_mod `pu_<kunci>`). */
    public const FOLLOW_CHANNELS = [
        'googlenews' => 'Google News',
        'whatsapp' => 'WhatsApp Channel',
        'telegram' => 'Telegram',
    ];

    /** Akun media sosial footer (kunci = ikon + theme_mod `pu_<kunci>`). */
    public const SOCIAL_CHANNELS = [
        'instagram' => 'Instagram',
        'youtube' => 'YouTube',
        'x' => 'X',
        'tiktok' => 'TikTok',
        'facebook' => 'Facebook',
    ];

    /** Semua URL kanal yang diatur di Customizer. */
    public const FOOTER_CHANNELS = [...self::FOLLOW_CHANNELS, ...self::SOCIAL_CHANNELS];

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
        $custom = esc_url_raw((string) get_theme_mod('pu_live_url', ''));
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
        return $this->homeUrl();
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
            'label' => __('Terkini', 'plstn-u'),
            'url' => $this->homeUrl(),
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
            'utama' => __('Utama', 'plstn-u'),
            'terbaru' => __('Terbaru', 'plstn-u'),
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
        return $this->copyright();
    }

    /**
     * Kolom tautan footer dari menu `footer_navigation`: item tingkat atas yang
     * punya sub-item = judul kolom + tautannya; item tingkat atas tanpa sub-item
     * dikumpulkan jadi satu kolom tanpa judul (di depan). Tanpa menu: halaman
     * tentang/redaksi/kontak yang terbit.
     *
     * @return array<int, array{heading: string, links: array<int, array{label: string, url: string}>}>
     */
    public function footerColumns(): array
    {
        $link = fn ($item) => ['label' => $item['label'], 'url' => $item['url']];

        if ($menu = $this->menuTree('footer_navigation')) {
            $loose = [];
            $columns = [];
            foreach ($menu as $item) {
                if ($item['children']) {
                    $columns[] = ['heading' => $item['label'], 'links' => array_map($link, $item['children'])];
                } else {
                    $loose[] = $link($item);
                }
            }

            return $loose ? [['heading' => '', 'links' => $loose], ...$columns] : $columns;
        }

        $links = [];
        foreach (['tentang' => __('Tentang', 'plstn-u'), 'redaksi' => __('Redaksi', 'plstn-u'), 'kontak' => __('Kontak', 'plstn-u')] as $slug => $label) {
            // Hanya halaman yang benar-benar ada (situs baru belum punya halaman ini).
            if ($url = $this->pageUrl($slug)) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links ? [['heading' => '', 'links' => $links]] : [];
    }

    /** Slogan situs (Settings > General > Tagline). */
    public function siteTagline(): string
    {
        return get_bloginfo('description', 'display');
    }

    /**
     * Tombol "Ikuti di" footer; hanya kanal yang URL-nya diisi di Customizer.
     *
     * @return array<int, array{key: string, label: string, url: string}>
     */
    public function followLinks(): array
    {
        return $this->channelLinks(self::FOLLOW_CHANNELS);
    }

    /**
     * Media sosial footer (yang diisi di Customizer) + feed RSS (selalu ada).
     *
     * @return array<int, array{key: string, label: string, url: string}>
     */
    public function socialLinks(): array
    {
        $links = $this->channelLinks(self::SOCIAL_CHANNELS);
        $links[] = ['key' => 'rss', 'label' => 'RSS', 'url' => esc_url(get_feed_link())];

        return $links;
    }

    /** @return array<int, array{key: string, label: string, url: string}> */
    protected function channelLinks(array $channels): array
    {
        $links = [];
        foreach ($channels as $key => $label) {
            $url = esc_url_raw((string) get_theme_mod('pu_'.$key, ''));
            if ($url !== '') {
                $links[] = ['key' => $key, 'label' => $label, 'url' => $url];
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
            $url = esc_url_raw((string) $item->url);
            if ($url === '' || $item->title === '') {
                continue;
            }
            $nodes[(int) $item->ID] = [
                'parent' => (int) $item->menu_item_parent,
                // Judul tersimpan sebagai entity (mis. "&#038;"); decode agar tidak ter-escape ganda oleh Blade.
                'label' => html_entity_decode(wp_strip_all_tags($item->title), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
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
