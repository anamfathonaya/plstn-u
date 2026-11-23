# Arsitektur

Halaman ini memetakan bagian-bagian tema agar Anda tahu di mana mengubah data, tampilan, dan perilaku. Semua nama file dan kelas di sini berasal dari `theme/`.

## Alur render

```text
Template WordPress (mis. single.php -> single.blade.php)
  -> layouts/app.blade.php
     -> View Composer (app/View/Composers/*.php) menyiapkan data
     -> Komponen Blade (resources/views/components/*.blade.php) menampilkan data
```

WordPress tetap memilih template dengan hierarki standarnya. Tema menyediakan versi Blade untuk setiap template, bukan file PHP baru.

## Template

| Template Blade | Dipakai untuk |
| --- | --- |
| `front-page.blade.php` | Beranda |
| `home.blade.php`, `index.blade.php` | Daftar artikel (halaman posts dan fallback) |
| `single.blade.php` | Artikel tunggal |
| `single-video.blade.php`, `single-infografik.blade.php` | Satu item CPT `video` dan `infografik` (keduanya `@extends('single')`) |
| `archive-video.blade.php`, `archive-infografik.blade.php` | Arsip CPT |
| `author.blade.php`, `search.blade.php` | Arsip penulis dan hasil pencarian |
| `page.blade.php` | Halaman statis |
| `404.blade.php` | Halaman tidak ditemukan |
| `template-custom.blade.php` | Template halaman kustom (`Template Name: Custom Template`) |
| `layouts/app.blade.php` | Kerangka HTML utama, navbar, footer, dan `@vite` |

## View Composers

Composer berada di `theme/app/View/Composers/`. Setiap composer mendaftarkan view yang dilayaninya lewat `protected static $views`.

| Composer | Berkas | View |
| --- | --- | --- |
| `App` | `App.php` | `*` (semua view) |
| `FrontPage` | `FrontPage.php` | `front-page` |
| `Archive` | `Archive.php` | `index`, `home`, `search`, `author`, `archive-video`, `archive-infografik` |
| `Single` | `Single.php` | `single` |
| `Post` | `Post.php` | `partials.page-header`, `partials.content`, `partials.content-*` |
| `Page` | `Page.php` | `page` |
| `NotFound` | `NotFound.php` | `404` |
| `Trending` | `Trending.php` | `components.trending-list` |

`App` adalah composer global. Method publiknya (mis. `navItems()`, `categoryStrip()`, `quickTabs()`, `footerColumns()`, `liveActive()`) dipanggil dari Blade sebagai fungsi, misalnya `$navItems()`.

Helper pendukung:

| Berkas | Fungsi |
| --- | --- |
| `app/Support/NewsIndex.php` | Mengembalikan URL halaman posts, atau beranda bila belum diset |
| `app/View/Components/Alert.php` | Kelas komponen `alert`. Menentukan kelas CSS dari `type`. |

## Komponen Blade

Komponen berada di `theme/resources/views/components/` (20 berkas). Dipanggil dengan `<x-nama-komponen />`.

| Komponen | Fungsi |
| --- | --- |
| `alert` | Kotak pesan dengan `type` success, warning, caution, atau info. Kelas dihitung di `Alert.php`. |
| `card-compact` | Kartu ringkas. Thumbnail opsional. |
| `card-standard` | Kartu standar dengan kicker, judul, dan waktu. |
| `category-strip` | Strip kategori horizontal di beranda. |
| `font-resizer` | Pilihan ukuran teks normal atau besar. Disimpan di `localStorage`. |
| `footer` | Footer: nama situs, copyright, dan tautan. |
| `hero-column` | Kolom berita di sisi kiri atau kanan hero. |
| `hero-headline` | Berita utama di beranda. |
| `image-placeholder` | Placeholder bila artikel tidak punya gambar utama. |
| `live-badge` | Penanda LIVE. |
| `nav-drawer` | Drawer menu (`<dialog>`) berisi kategori dan sub-kategori. |
| `navbar` | Bilah navigasi atas: tombol menu, Live, pencarian. |
| `pagination` | Navigasi halaman arsip. |
| `quick-tabs` | Tab Utama dan Terbaru di beranda. |
| `search-field` | Kolom pencarian di desktop. |
| `search-form` | Formulir pencarian. |
| `search-modal` | Dialog pencarian (`<dialog>`) dengan topik populer. |
| `section-block` | Blok rubrik: judul dan daftar kartu. |
| `section-header` | Judul seksi dengan tautan "Lihat semua". |
| `trending-list` | Daftar Terpopuler. |

Setiap komponen Blade memakai `@props([...])` untuk nilai default, kecuali `alert`. `alert` menerima props lewat konstruktor kelas `Alert.php`.

## Prinsip pemisahan

1. **Query dan format data di composer.** Blade tidak memanggil `WP_Query`, `get_posts()`, atau `get_terms()`. Semua pengambilan data ada di `theme/app/View/Composers/`.
2. **Escaping.** Judul, tanggal, dan teks biasa ditampilkan dengan `{{ }}` (otomatis di-escape). URL di-escape dengan `esc_url()` di composer.
3. **HTML dari editor.** Konten artikel ditampilkan dengan `{!! !!}` hanya setelah `wp_kses_post()` (lihat `Single.php` dan `Page.php`).
4. **Query ringan.** Query non-paginasi memakai `no_found_rows => true`. Query daftar kartu mengaktifkan cache meta dan term (lihat `FrontPage.php` dan `Single.php`).
5. **Status UI di Alpine.js, sisanya di browser.** Navigasi dan pencarian memakai `<dialog>` bawaan. Alpine.js hanya menyimpan state.

## Interaktivitas

`resources/js/app.js` memulai Alpine.js. Komponen yang memakai `x-data`:

| Komponen | State |
| --- | --- |
| `nav-drawer` | Sub-kategori yang sedang terbuka. Dialog `<dialog>` dibuka dengan `showModal()`. |
| `quick-tabs` | Tab aktif |
| `font-resizer` | Ukuran teks (normal atau besar) |
| `navbar`, `search-field` | Mengirim event (`open-nav-drawer`, `open-search-modal`) yang membuka dialog |
| `search-modal` | Dialog pencarian dengan `showModal()` |

`app.js` juga menutup dialog lewat klik di luar bila peramban tidak mendukung atribut `closedby`.

## Build dan aset

| Berkas | Fungsi |
| --- | --- |
| `resources/css/app.css` | Entri CSS utama. Mengimpor Tailwind dan `tokens.css`. |
| `resources/css/tokens.css` | Blok `@theme` dan `@font-face` |
| `resources/css/editor.css`, `resources/js/editor.js` | Gaya dan skrip editor blok |
| `vite.config.js` | Entri Vite, plugin WordPress, dan `theme.json` hasil build |

Keempat entri Vite (`app.css`, `app.js`, `editor.css`, `editor.js`) dibangun oleh `npm run build`. Lihat [Instalasi](instalasi.md) untuk langkah lengkapnya.
