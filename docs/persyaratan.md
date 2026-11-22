# Persyaratan

Halaman ini merangkum versi yang tercantum di kode tema. Angka berikut diambil langsung dari `theme/style.css`, `theme/composer.json`, dan `theme/package.json`.

## Ringkasan

| Komponen | Versi minimum | Sumber |
| --- | --- | --- |
| PHP | 8.3 | `theme/style.css` (`Requires PHP`), `theme/composer.json` (`require.php`) |
| WordPress | Sudah terpasang. Diuji pada 7.1.5 | `theme/style.css` mencantumkan `Requires at least: 6.4`, tetapi versi 6.4 belum diuji |
| Node.js | `^20.19.0` atau `>=22.12.0` | `theme/package.json` (`engines.node`) |
| npm | Ikut Node.js | Dipakai untuk `npm ci` dan `npm run build` |
| Composer | Tidak dibatasi versinya | Dipakai untuk `roots/acorn` |

## WordPress

Plstn-U adalah tema, bukan instalasi WordPress. Pasang WordPress terlebih dahulu, lalu pastikan Anda bisa masuk ke wp-admin.

| Versi | Status |
| --- | --- |
| 7.1.5 | Diuji. Instalasi bersih dengan tema dan `mu-plugins/` berjalan tanpa warning PHP. |
| 6.4 | Tercantum sebagai minimum di `theme/style.css`, belum diuji. |

## PHP dan WordPress

- Tema memakai Acorn (`roots/acorn` `^6.3`) dan View Composers. Keduanya membutuhkan PHP 8.3 atau yang lebih baru.
- Tema menyertakan `theme/theme.json` dan mengaktifkan fitur standar WordPress seperti `post-thumbnails` dan `html5` (`theme/app/setup.php`).

## Node.js dan npm

Build aset memakai Vite 8 dan Tailwind CSS v4. Versi Node.js harus memenuhi `engines` di `theme/package.json`:

```json
"engines": {
  "node": "^20.19.0 || >=22.12.0"
}
```

## Composer

`theme/composer.json` hanya mewajibkan:

- `php` `>=8.3`
- `roots/acorn` `^6.3`

Paket pengembangan `laravel/pint` ada di `require-dev`. Tidak wajib untuk menjalankan tema.

## Plugin

Tidak ada plugin yang wajib. Dua plugin bersifat opsional:

| Plugin | Fungsi | Jika tidak dipasang |
| --- | --- | --- |
| Post Views Counter | Mengurutkan blok Terpopuler berdasarkan jumlah tayangan 7 hari terakhir | Blok Terpopuler memakai artikel terbaru |
| Plugin SEO (pilihan Anda) | Meta dan schema | Tidak ada integrasi khusus di kode tema |

!!! note
    Tema hanya memeriksa keberadaan fungsi `pvc_get_post_views()` (`theme/app/View/Composers/Trending.php`). Plugin lain tidak diperlukan.

!!! warning
    Tema tidak diuji dengan plugin SEO tertentu. Uji di lingkungan staging sebelum dipakai di situs produksi.
