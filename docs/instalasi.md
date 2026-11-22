# Instalasi

Halaman ini menjelaskan cara memasang Plstn-U di instalasi WordPress Anda. Anda membutuhkan akses ke folder `wp-content/` dan terminal dengan Composer serta Node.js (lihat [Persyaratan](persyaratan.md)).

!!! note "Prasyarat"
    Panduan ini mengasumsikan WordPress sudah terpasang, database sudah siap, dan Anda bisa masuk ke wp-admin. Panduan ini tidak menjelaskan cara memasang WordPress.

## 1. Salin tema

Repo berisi folder `theme/` (tema) dan `mu-plugins/` (data editorial). Salin `theme/` ke `wp-content/themes/plstn-u`:

```bash
cp -r theme /path/ke/wordpress/wp-content/themes/plstn-u
```

Nama folder menentukan slug tema. Slug ini juga dipakai di `theme/vite.config.js` (lihat langkah 4).

## 2. Salin mu-plugins

Dua file di `mu-plugins/` didaftarkan sebagai Must-Use plugin. Keduanya berjalan otomatis tanpa aktivasi:

```bash
mkdir -p /path/ke/wordpress/wp-content/mu-plugins
cp mu-plugins/pu-content-types.php mu-plugins/pu-feeds.php /path/ke/wordpress/wp-content/mu-plugins/
```

| File | Fungsi |
| --- | --- |
| `pu-content-types.php` | Mendaftarkan CPT `video` dan `infografik` |
| `pu-feeds.php` | Menyempurnakan feed RSS |

!!! note
    Data custom post type tetap di database WordPress. Jika tema diganti nanti, konten `video` dan `infografik` tidak hilang, tetapi template tema untuk keduanya ikut hilang.

## 3. Pasang dependensi PHP

Jalankan di dalam folder tema:

```bash
cd /path/ke/wordpress/wp-content/themes/plstn-u
composer install
```

Perintah ini membuat folder `vendor/`. Tema menolak berjalan bila `vendor/autoload.php` tidak ada.

## 4. Sesuaikan `base`

`theme/vite.config.js` memiliki nilai `base` yang bergantung pada lokasi instalasi:

```js
export default defineConfig({
  base: '/wp-content/themes/plstn-u/public/build/',
  // ...
})
```

`base` harus sama dengan path folder tema Anda. Jika folder bernama `my-theme`, ubah menjadi `/wp-content/themes/my-theme/public/build/`, lalu jalankan build ulang (langkah 5).

!!! warning
    Jika `base` tidak sesuai, CSS dan JS tetap termuat, tetapi font tidak. Browser memakai font cadangan. Nilai `base` juga tertanam di URL font di dalam CSS.

## 5. Pasang dependensi dan build aset

```bash
npm ci
npm run build
```

| Perintah | Keterangan |
| --- | --- |
| `npm ci` | Memasang paket sesuai `package-lock.json` |
| `npm run build` | Membuat `public/build/` (CSS, JS, dan `theme.json` hasil build) |
| `npm run dev` | Menjalankan Vite untuk pengembangan dengan hot reload |

Folder `node_modules/`, `vendor/`, dan `public/build/` tidak ikut di repo. Anda perlu membuatnya sendiri di server.

## 6. Aktifkan tema

1. Buka **Appearance > Themes** di admin WordPress.
2. Aktifkan **Plstn-U**.
3. Buka **Settings > Permalinks**, pilih **Post name**, lalu klik **Save Changes**.

!!! warning
    Instalasi baru memakai struktur **Plain**. Dengan struktur ini, `/feed/` dan `/video/` menampilkan beranda. Klik **Save Changes** tanpa mengganti struktur tidak cukup.

Setelah aktif, atur nama situs di **Settings > General**. Nama ini tampil di navbar, footer, dan judul feed.

## 7. Periksa hasil

- Beranda memuat CSS dan JS tanpa error 404 di konsol browser, dan berkas font `.woff2` juga termuat.
- Halaman artikel menampilkan kolom isi dan tautan terkait.
- URL `/video/` dan `/infografik/` memuat arsip (setelah ada konten).

!!! warning
    Jika aset tidak termuat, periksa dulu nilai `base` di `vite.config.js` dan apakah `public/build/` sudah dibuat.

## Lingkungan lokal

Repo publik tidak menyertakan konfigurasi lingkungan lokal. Anda bebas memakai alat apa pun (misalnya Docker, Local, atau server pengembangan) selama instalasi WordPress menyediakan PHP dan Node.js sesuai [Persyaratan](persyaratan.md).

Lanjut ke [Konfigurasi](konfigurasi.md) untuk menyiapkan menu, halaman, dan fitur tambahan.
