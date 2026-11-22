# Instalasi

Halaman ini menjelaskan cara memasang Plstn-U di instalasi WordPress Anda. Anda membutuhkan akses ke folder `wp-content/` dan terminal dengan Composer serta Node.js (lihat [Persyaratan](persyaratan.md)).

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

## 4. Sesuaikan `base` dan `APP_URL`

`theme/vite.config.js` memiliki dua nilai yang bergantung pada lokasi instalasi:

```js
if (! process.env.APP_URL) {
  process.env.APP_URL = 'https://palestinaupdate.com';
}

export default defineConfig({
  base: '/wp-content/themes/plstn-u/public/build/',
  // ...
})
```

- **`base`** harus sama dengan path folder tema Anda. Jika folder bernama `my-theme`, ubah menjadi `/wp-content/themes/my-theme/public/build/`. Jika tidak, aset CSS dan JS tidak termuat.
- **`APP_URL`** adalah nilai cadangan bila variabel lingkungan tidak diset. Set ke domain Anda sebelum build.

```bash
APP_URL=https://domain-anda.example npm run build
```

Di Windows PowerShell gunakan `$env:APP_URL="https://domain-anda.example"` lalu `npm run build`.

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
3. Buka **Settings > Permalinks** lalu klik **Save Changes**. Langkah ini memperbarui aturan URL untuk CPT `video` dan `infografik`.

Setelah aktif, atur nama situs di **Settings > General**. Nama ini tampil di navbar, footer, dan judul feed.

## 7. Periksa hasil

- Beranda memuat CSS dan JS tanpa error 404 di konsol browser.
- Halaman artikel menampilkan kolom isi dan tautan terkait.
- URL `/video/` dan `/infografik/` memuat arsip (setelah ada konten).

!!! warning
    Jika aset tidak termuat, periksa dulu nilai `base` di `vite.config.js` dan apakah `public/build/` sudah dibuat.

## Lingkungan lokal

Repo publik tidak menyertakan konfigurasi lingkungan lokal. Anda bebas memakai alat apa pun (misalnya Docker, Local, atau server pengembangan) selama instalasi WordPress menyediakan PHP dan Node.js sesuai [Persyaratan](persyaratan.md).

Lanjut ke [Konfigurasi](konfigurasi.md) untuk menyiapkan menu, halaman, dan fitur tambahan.
