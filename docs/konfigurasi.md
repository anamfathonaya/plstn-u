# Konfigurasi

Halaman ini menjelaskan pengaturan yang bisa Anda ubah tanpa menyentuh kode tema, serta beberapa nilai yang ditetapkan di kode dan perlu diubah bila situs Anda memakai struktur berbeda.

## Customizer Plstn-U

Buka **Appearance > Customize** lalu pilih bagian **Plstn-U**. Pengaturan berikut tersedia:

| Pengaturan | Nama di kode | Default | Keterangan |
| --- | --- | --- | --- |
| Liputan live sedang berjalan | `pu_live_active` | Mati | Menampilkan tombol Live di navbar dan drawer, serta badge LIVE di hero beranda. |
| URL tombol Live (opsional) | `pu_live_url` | Kosong | Tujuan tombol Live. Jika kosong, dipakai halaman Kabar Terkini. |
| Halaman Kabar Terkini | `pu_news_page` | Kosong | Halaman tujuan tombol Live bila URL Live kosong. |
| Kategori blok beranda 1 | `pu_home_category_1` | (kosong) | Blok kartu pertama di beranda. |
| Kategori blok beranda 2 | `pu_home_category_2` | (kosong) | Blok kartu kedua di beranda. |
| Kategori blok beranda 3 | `pu_home_category_3` | (kosong) | Blok kartu ketiga di beranda. |
| URL Google News, WhatsApp Channel, Telegram | `pu_googlenews`, `pu_whatsapp`, `pu_telegram` | Kosong | Tombol "Ikuti di" di footer. Kosong = tombol disembunyikan. |
| URL Instagram, YouTube, X, TikTok, Facebook | `pu_instagram`, `pu_youtube`, `pu_x`, `pu_tiktok`, `pu_facebook` | Kosong | Baris media sosial footer. Kosong = disembunyikan. Tautan RSS (`/feed/`) selalu tampil. |

Lembar isian dan cara mendapatkan URL tiap kanal (termasuk Google News): [Kanal & media sosial](kanal-sosial.md).

Keterangan tambahan:

- **Tombol Live tidak tampil sampai diaktifkan.** Setelah opsi "Liputan live sedang berjalan" dicentang dan disimpan, tombol muncul di navbar, item teratas di drawer, dan badge LIVE di hero beranda.
- **Urutan tujuan tombol Live:** URL Live (jika diisi), lalu halaman dari "Halaman Kabar Terkini", lalu halaman dengan slug `kabar-terkini`, lalu halaman Posts page (lihat [Halaman Kabar Terkini](#halaman-kabar-terkini)).
- **Kategori beranda** dipilih dari daftar kategori yang ada. Pilihan "(kosong)" melewati slot tersebut.

!!! note
    Opsi Live juga bisa diatur lewat WP-CLI: `wp option update pu_live_active 1` (aktif) atau `wp option update pu_live_active 0` (nonaktif).

## Logo dan nama situs

- **Logo.** Unggah logo di **Appearance > Customize > Site Identity > Logo**. Logo ini dipakai di navbar dan drawer. Jika belum diisi, tema memakai tanda logo bawaan (`favicon.svg`).
- **Nama situs.** Atur di **Settings > General > Site Title**. Nama ini tampil di navbar, drawer, footer, dan judul feed.

## Menu navigasi

Tema memiliki dua lokasi menu: **Primary Navigation** (`primary_navigation`) dan **Footer Navigation** (`footer_navigation`). Atur di **Appearance > Menus**, lalu tetapkan menu ke lokasi tersebut.

### Menu utama

- **Jika menu ditetapkan ke Primary Navigation**, menu itu dipakai untuk strip kategori di beranda dan drawer navigasi. Tema membaca dua tingkat: item tingkat kedua tampil sebagai sub-item di bawah induknya. Item **Terkini** bawaan tidak ditambahkan otomatis, jadi tambahkan sendiri ke menu bila dibutuhkan.
- **Jika belum ditetapkan**, tema membangun menu dari kategori: kategori induk beserta sub-kategorinya. Kategori tanpa artikel dan kategori default WordPress tidak ditampilkan. Urutannya mengikuti nama (A-Z).

!!! note
    Item menu tanpa judul dilewati. Item yang menunjuk ke kategori atau halaman yang sedang dibuka ditandai aktif.

### Menu footer

- **Jika menu ditetapkan ke Footer Navigation**, menu menjadi kolom tautan footer. Item tingkat atas yang punya sub-item menjadi judul kolom, dan sub-itemnya menjadi tautan kolom tersebut (contoh: "Palestina Update" berisi Tentang Kami, Redaksi, Kontak; "Kebijakan" berisi Pedoman Media Siber, Kebijakan Privasi). Item tingkat atas tanpa sub-item dikumpulkan dalam satu kolom tanpa judul.
- **Jika belum ditetapkan**, footer menampilkan satu kolom tautan ke halaman dengan slug `tentang`, `redaksi`, dan `kontak`. Halaman yang belum diterbitkan tidak tampil.
- **Slogan** di bawah nama situs di footer diambil dari **Settings > General > Tagline**.

## Halaman Kabar Terkini

Tema memakai halaman posts (Posts page) sebagai indeks berita, yaitu halaman tujuan tautan "Terkini" dan judul daftar artikel.

1. Buat halaman baru, misalnya "Kabar Terkini", lalu terbitkan.
2. Buka **Settings > Reading**.
3. Pada kolom **Posts page**, pilih halaman tersebut, lalu simpan.

!!! note
    Kolom **Posts page** tampil di bawah pilihan **A static page** dan hanya muncul setelah ada minimal satu halaman. Anda tidak harus menjadikan halaman ini beranda. Pada uji instalasi, beranda tetap memakai **Your latest posts** dan tautan Terkini mengarah ke halaman tersebut.

Jika Posts page belum diset, tautan Terkini mengarah ke beranda.

## Halaman dengan slug tetap

Beberapa tautan memakai slug halaman yang ditetapkan di kode. Buat halaman dengan slug berikut bila ingin tautan tampil:

| Slug | Dipakai untuk | Perilaku bila tidak ada |
| --- | --- | --- |
| `kabar-terkini` | Tujuan tombol Live (bila "Halaman Kabar Terkini" dan URL Live kosong) | Memakai Posts page |
| `tentang` | Tautan footer (bila Footer Navigation kosong) | Tautan tidak tampil |
| `redaksi` | Tautan footer (bila Footer Navigation kosong) | Tautan tidak tampil |
| `kontak` | Tautan footer (bila Footer Navigation kosong) | Tautan tidak tampil |

Halaman footer hanya tampil bila statusnya terbit (`publish`).

## Sidebar

Ada dua area widget yang terdaftar di `theme/app/setup.php`:

| ID | Nama | Status |
| --- | --- | --- |
| `sidebar-primary` | Primary | Partial `sections/sidebar.blade.php` memanggilnya, tetapi belum ada template yang menampilkan partial ini. |
| `sidebar-footer` | Footer | Terdaftar, belum dirender oleh template tema. |

!!! note
    Jika Anda ingin widget tampil, tambahkan `@section('sidebar')` di template yang Anda pilih, lalu `@include('sections.sidebar')` di dalamnya. Bagian ini belum diuji pada tema ini.

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

Setelah menambah atau mengubah CPT, perbarui aturan permalink dengan menyimpan ulang **Settings > Permalinks**, atau dengan `wp rewrite flush`.

## Kategori beranda (nilai bawaan kode)

Nilai bawaan tiga blok beranda ada di `theme/app/View/Composers/FrontPage.php` (konstanta `SECTIONS`). Nilai ini dipakai bila Customizer belum diisi. Setelah Customizer diisi, nilai di Customizer yang berlaku.

```php
public const SECTIONS = ['gaza', 'tepi-barat', 'internasional'];
```

## Plugin opsional: Post Views Counter

Blok **Terpopuler** (`theme/app/View/Composers/Trending.php`) bekerja begini:

- Jika fungsi `pvc_get_post_views()` tersedia, artikel diurutkan berdasarkan tayangan dalam 7 hari terakhir.
- Jika tidak ada artikel dengan tayangan dalam 7 hari terakhir, blok menampilkan artikel terbaru.
- Jika plugin tidak dipasang, blok selalu menampilkan artikel terbaru.

## Feed RSS

File `mu-plugins/pu-feeds.php` mengubah feed WordPress sebagai berikut:

- Jumlah item per feed: 20.
- Bahasa kanal mengikuti bahasa situs di **Pengaturan > Umum > Bahasa Situs**, dengan garis bawah diganti tanda hubung (misalnya `id_ID` menjadi `id-ID`). Jika bahasa situs kosong, dipakai `id`.
- Setiap item memuat `<enclosure>` dan `<media:content>` untuk gambar utama.
- Ringkasan diikuti tautan "Baca selengkapnya".
- Halaman kategori mengiklankan feed kategori di `<head>`.

Alamat feed mengikuti aturan WordPress standar, misalnya `/feed/` dan `/category/<slug>/feed/`. Jika `/feed/` menampilkan beranda, struktur permalink belum diatur (lihat [Instalasi](instalasi.md#6-aktifkan-tema)).

## SEO

Kode tema tidak memuat integrasi khusus plugin SEO. Lihat catatan di [Persyaratan](persyaratan.md#plugin).
