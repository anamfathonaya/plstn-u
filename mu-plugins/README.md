# `mu-plugins/` — Data Schema Layer (`Plstn-U-theme`)

Folder ini menyimpan registrasi **Custom Post Types (CPT)** dan **Custom Taxonomies** agar struktur data editorial tetap terpisah dari tampilan tema (`theme/`).

### Konvensi File
* Gunakan file loader tunggal atau file deskriptif berawalan prefix proyek, misal:
  * `plstn-editorial-taxonomies.php` — Registrasi taksonomi rubrik, topik khusus, atau seri liputan.
  * `plstn-editorial-cpts.php` — Registrasi CPT multimedia (misal: `video`, `infografik`) jika dibutuhkan di Fase 5.
* Jangan meletakkan kode styling atau tampilan HTML di dalam folder ini.

### File Saat Ini
* `pu-feeds.php` — Penyempurnaan feed RSS (gambar utama, ringkasan, feed kategori).
* `pu-content-types.php` — CPT multimedia `video` (`/video/`) dan `infografik` (`/infografik/`): publik, `has_archive`, `show_in_rest`, mendukung judul/editor/gambar utama/ringkasan, memakai taksonomi `category` dan `post_tag`. Template tema: `archive-video`, `archive-infografik`, `single-video`, `single-infografik` (data dari composer `Archive` dan `Single`). Setelah menambah/mengubah CPT, jalankan `wp rewrite flush` (lokal: `.dev/seed.sh` sudah melakukannya).
