# Konfigurasi

Halaman ini menjelaskan pengaturan yang bisa Anda ubah tanpa menyentuh kode tema, serta beberapa nilai yang ditetapkan di kode dan perlu diubah bila situs Anda memakai struktur berbeda.

## Menu navigasi

Lokasi menu `primary_navigation` terdaftar (`theme/app/setup.php`), tetapi template tidak memanggil `wp_nav_menu()`. Menu utama dibangun dari **kategori**:

- `App::navItems()` (`theme/app/View/Composers/App.php`) mengambil kategori induk beserta sub-kategorinya.
- Kategori yang tidak punya artikel (`hide_empty`) tidak ditampilkan.
- Kategori default WordPress tidak ditampilkan.
- Urutan mengikuti nama kategori (A-Z).

!!! warning
    Mengisi menu lewat **Appearance > Menus** tidak mengubah navigasi utama. Atur struktur navigasi lewat kategori: buat kategori induk, lalu kategori anak di bawahnya.

Strip kategori horizontal di beranda juga memakai daftar kategori yang sama.

## Sidebar

Ada dua area widget yang terdaftar di `theme/app/setup.php`:

| ID | Nama | Status |
| --- | --- | --- |
| `sidebar-primary` | Primary | Partial `sections/sidebar.blade.php` memanggilnya, tetapi belum ada template yang menampilkan partial ini. |
| `sidebar-footer` | Footer | Terdaftar, belum dirender oleh template tema. |

!!! note
    Jika Anda ingin widget tampil, tambahkan `@section('sidebar')` di template yang Anda pilih, lalu `@include('sections.sidebar')` di dalamnya. Bagian ini belum diuji pada tema ini.

## Halaman posts (berita terkini)

Tema memakai halaman posts untuk tautan "Terkini" dan judul daftar artikel.

1. Buat halaman baru, misalnya "Kabar Terkini".
2. Buka **Settings > Reading**.
3. Pada **Posts page**, pilih halaman tersebut.

Jika belum diatur, tautan berita mengarah ke beranda (`theme/app/Support/NewsIndex.php`).

## Halaman dengan slug tetap

Beberapa tautan memakai slug halaman yang ditetapkan di kode. Buat halaman dengan slug berikut bila ingin tautan tampil:

| Slug | Dipakai untuk | Perilaku bila tidak ada |
| --- | --- | --- |
| `kabar-terkini` | Tujuan tombol Live | Memakai halaman posts |
| `tentang` | Tautan footer | Tautan tidak tampil |
| `redaksi` | Tautan footer | Tautan tidak tampil |
| `kontak` | Tautan footer | Tautan tidak tampil |

Halaman footer hanya tampil bila statusnya terbit (`publish`).

## Kategori beranda

Beranda menampilkan blok per kategori untuk tiga slug yang ditetapkan sebagai konstanta di `theme/app/View/Composers/FrontPage.php`:

```php
protected const SECTIONS = ['gaza', 'tepi-barat', 'internasional'];
```

Ubah nilai ini ke slug kategori Anda. Kategori dengan slug lain tidak muncul sebagai blok di beranda.

## Custom post type video dan infografik

File `mu-plugins/pu-content-types.php` mendaftarkan dua CPT:

| Slug | Label | URL arsip | Taksonomi |
| --- | --- | --- | --- |
| `video` | Video | `/video/` | `category`, `post_tag` |
| `infografik` | Infografik | `/infografik/` | `category`, `post_tag` |

Template yang dipakai:

| Tampilan | Template |
| --- | --- |
| Arsip video | `archive-video.blade.php` |
| Arsip infografik | `archive-infografik.blade.php` |
| Satu video | `single-video.blade.php` |
| Satu infografik | `single-infografik.blade.php` |

Setelah menambah atau mengubah CPT, perbarui aturan permalink:

```bash
wp rewrite flush
```

Alternatifnya, simpan ulang **Settings > Permalinks** di admin WordPress.

## Plugin opsional: Post Views Counter

Blok **Terpopuler** (`theme/app/View/Composers/Trending.php`) bekerja begini:

- Jika fungsi `pvc_get_post_views()` tersedia, artikel diurutkan berdasarkan tayangan dalam 7 hari terakhir.
- Jika tidak ada artikel dengan tayangan dalam 7 hari terakhir, blok menampilkan artikel terbaru.
- Jika plugin tidak dipasang, blok selalu menampilkan artikel terbaru.

## Mode Live

Tombol **Live** di navbar tampil saat opsi WordPress `pu_live_active` bernilai benar. Tema tidak menyediakan layar pengaturan untuk opsi ini, jadi atur lewat WP-CLI:

```bash
wp option update pu_live_active 1   # tampilkan tombol Live
wp option update pu_live_active 0   # sembunyikan tombol Live
```

Tombol mengarah ke halaman `kabar-terkini` jika ada, atau ke halaman posts bila tidak ada. Teks tombol ada di `navbar.blade.php` dan `nav-drawer.blade.php` (lihat [Kustomisasi](kustomisasi.md)).

## Feed RSS

File `mu-plugins/pu-feeds.php` mengubah feed WordPress sebagai berikut:

- Jumlah item per feed: 20.
- Bahasa kanal: `id` (ditetapkan di `pu_feeds_bloginfo()`). Ubah bila situs Anda memakai bahasa lain.
- Setiap item memuat `<enclosure>` dan `<media:content>` untuk gambar utama.
- Ringkasan diikuti tautan "Baca selengkapnya".
- Halaman kategori mengiklankan feed kategori di `<head>`.

Alamat feed mengikuti aturan WordPress standar, misalnya `/feed/` dan `/category/<slug>/feed/`.

!!! warning
    Jika Anda mengganti bahasa kanal, ubah juga nilai di `pu_feeds_bloginfo()` dan bukan hanya pengaturan WordPress.

## SEO

Kode tema tidak memuat integrasi khusus plugin SEO. Lihat catatan di [Persyaratan](persyaratan.md#plugin).
