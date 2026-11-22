# Plstn-U — Tema WordPress Palestina Update

Tema WordPress untuk situs berita Palestina Update, dibangun di atas Roots Sage 11 dan Tailwind CSS v4. Desainnya editorial, Light-only, dan memakai font Spectral + Roboto.

> [!NOTE]
> Repo ini adalah salinan publik dari kode tema. Pengembangan, dokumentasi internal, dan deploy dikelola di repo terpisah, jadi isi repo ini diperbarui berkala.

## Stack

| Lapisan | Teknologi | Versi |
| --- | --- | --- |
| Tema | Roots Sage 11 + Acorn | `roots/acorn` ^6.3 |
| Template | Laravel Blade + View Composers | — |
| CSS | Tailwind CSS v4 (`@theme`) | `tailwindcss` ^4.0.0 |
| JS | Alpine.js | `alpinejs` ^3.17.5 |
| Build | Vite + `laravel-vite-plugin` + `@roots/vite-plugin` | `vite` ^8.0.0 |
| Runtime | PHP ≥ 8.3, WordPress ≥ 6.4, Node `^20.19.0 \|\| >=22.12.0` | — |

## Struktur

```text
.
├── theme/        # Tema Sage 11 (dipasang sebagai wp-content/themes/plstn-u)
│   ├── app/      # Providers, View/Composers, View/Components, Support, setup.php, filters.php
│   └── resources/  # css/, js/, fonts/, images/, views/
└── mu-plugins/   # CPT video & infografik, penyempurnaan feed RSS
```

## Instalasi

1. Salin atau symlink `theme/` ke `wp-content/themes/plstn-u`.
2. Salin `mu-plugins/*.php` ke `wp-content/mu-plugins/`.
3. Pasang dependensi dan build aset di dalam folder `theme/`:

   ```bash
   cd theme
   composer install
   npm ci
   npm run build   # atau: npm run dev
   ```

4. Aktifkan tema Plstn-U di WordPress.

Hasil build berada di `theme/public/build/` dan tidak di-commit.

## Arsitektur singkat

- **Tanpa logika di Blade:** semua query dan pemformatan data dilakukan di View Composer (`theme/app/View/Composers/`): `App`, `FrontPage`, `Single`, `Archive`, `Post`, `Page`, `NotFound`, `Trending`.
- **Komponen Blade:** ada di `theme/resources/views/components/`, misalnya `navbar`, `nav-drawer`, `search-modal`, `hero-headline`, `card-standard`, `card-compact`, `trending-list`, dan `quick-tabs`.
- **Interaktivitas:** tema memakai fitur bawaan browser lebih dulu, misalnya `<dialog>` untuk drawer dan pencarian. Alpine.js hanya dipakai untuk state.
- **Data editorial:** CPT `video` dan `infografik` didaftarkan di `mu-plugins/`, bukan di tema, sehingga konten tetap aman saat tema diganti.
- **Menu & sidebar:** lokasi menu `primary_navigation`, sidebar `sidebar-primary` dan `sidebar-footer`.
- **Plugin opsional:** Post Views Counter dipakai untuk mengurutkan artikel terpopuler. Tanpa plugin ini, urutan jatuh ke tanggal terbaru.

## Design token

Token warna, tipografi, spacing, dan radius ada di blok `@theme` di `theme/resources/css/tokens.css`. Gunakan token semantik seperti `bg-canvas`, `text-primary`, dan `border-subtle`, bukan nilai arbitrary.

## Lisensi

[MIT](LICENSE.md). Tema ini berbasis Roots Sage.
