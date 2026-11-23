# Kustomisasi

Halaman ini menjelaskan bagian tema yang paling sering diubah saat diadaptasi: token desain, font, logo, dan teks yang tertanam di kode.

## Prinsip dasar

- **Light-only.** Tema tidak memiliki mode gelap. `theme/resources/css/tokens.css` menonaktifkan varian `dark:`. Menambahkan mode gelap berarti mengubah token dan beberapa komponen.
- **Gunakan token semantik.** Di Blade, pakai nama token seperti `bg-canvas`, `text-primary`, dan `border-subtle`. Hindari nilai arbitrer seperti `text-[#111]` agar desain tetap konsisten.
- **Build ulang setelah perubahan.** Setiap perubahan CSS, font, atau gambar memerlukan `npm run build` (atau `npm run dev` saat pengembangan).

## Design token

Semua token ada di blok `@theme` pada `theme/resources/css/tokens.css`. `theme/resources/css/app.css` mengimpor berkas ini.

### Warna

| Token | Nilai | Dipakai untuk |
| --- | --- | --- |
| `--color-canvas` | `#ffffff` | Latar halaman |
| `--color-surface` | `#f5f5f5` | Latar permukaan |
| `--color-placeholder` | `#ebebeb` | Latar placeholder gambar |
| `--color-inverse` | `#111111` | Latar gelap (tombol Live) |
| `--color-live` | `#c31a25` | Penanda LIVE |
| `--color-accent` | `#27771c` | Aksen hijau |
| `--color-primary` | `#111111` | Teks utama |
| `--color-muted` | `#5c5c5c` | Teks sekunder |
| `--color-on-inverse` | `#ffffff` | Teks di atas latar gelap |
| `--color-subtle` | `#cccccc` | Garis tipis |
| `--color-strong` | `#111111` | Garis tebal |

Untuk mengganti warna, ubah nilainya di `tokens.css`, lalu jalankan `npm run build`. Nama token tidak perlu diubah selama Anda tetap memakai nama yang sama di Blade.

### Spasi, radius, dan lebar

Skala bawaan Tailwind dimatikan. Hanya nilai berikut yang tersedia:

| Grup | Contoh | Nilai |
| --- | --- | --- |
| Spasi | `--spacing-4` | `1rem` (kelas `p-4`, `gap-4`, dst.) |
| Radius | `--radius-sm` | `0.25rem` |
| Radius | `--radius-full` | `9999px` |
| Lebar kontainer | `--container-page` | `65rem` (1040px) |
| Lebar artikel | `--container-article` | `41.5rem` (664px) |

### Tipografi

| Token | Nilai | Keterangan |
| --- | --- | --- |
| `--font-serif` | `"Spectral", Georgia, serif` | Judul dan logo |
| `--font-sans` | `"Roboto", system-ui, sans-serif` | Antarmuka dan teks kecil |
| `--text-*` | Ukuran, line-height, dan bobot | Skala teks (`text-hero`, `text-body`, `text-nav`, dst.) |

Skala teks memakai variabel `--text-<nama>--line-height`, `--text-<nama>--font-weight`, dan `--text-<nama>--letter-spacing` di satu blok.

## Font

Font di-host sendiri di `theme/resources/fonts/`:

| Berkas | Keluarga | Bobot |
| --- | --- | --- |
| `spectral-latin-400-normal.woff2` | Spectral | 400 |
| `spectral-latin-600-normal.woff2` | Spectral | 600 |
| `spectral-latin-700-normal.woff2` | Spectral | 700 |
| `roboto-latin-400-normal.woff2` | Roboto | 400 |
| `roboto-latin-500-normal.woff2` | Roboto | 500 |
| `roboto-latin-700-normal.woff2` | Roboto | 700 |

Deklarasi `@font-face` ada di bagian atas `tokens.css`. Untuk mengganti font:

1. Letakkan berkas WOFF2 baru di `theme/resources/fonts/`.
2. Perbarui blok `@font-face` (`src` dan `font-family`).
3. Perbarui `--font-serif` atau `--font-sans`.
4. Jalankan `npm run build`.

!!! note
    Berkas lisensi font (`LICENSE-Spectral.txt`, `LICENSE-Roboto.txt`) ikut repo. Simpan lisensi jika Anda memakai font yang sama, dan tambahkan lisensi font baru bila mengganti.

## Logo dan favicon

- **Logo situs.** Unggah logo di **Appearance > Customize > Site Identity > Logo**. Logo ini dipakai di navbar dan drawer. Jika belum diisi, navbar dan drawer memakai `theme/resources/images/favicon.svg`.
- **Placeholder gambar.** Kartu tanpa gambar unggulan memakai `favicon.svg` (tidak terpengaruh logo kustom). Ganti berkas ini dengan SVG Anda, lalu build ulang.
- **Singkatan di mobile.** Navbar menampilkan teks `shortName` (default `PU`) di layar kecil. Nilai default ada di `theme/resources/views/components/navbar.blade.php`. Ubah nilai tersebut bila Anda memakai singkatan lain.
- **Nama situs.** Nama yang tampil di navbar, drawer, footer, dan judul feed berasal dari **Settings > General > Site Title**. Tidak ditulis langsung di kode.
- **Menu dan kategori.** Navigasi dan footer mengikuti menu yang Anda tetapkan di **Appearance > Menus**, atau kategori bila belum ditetapkan. Lihat [Konfigurasi](konfigurasi.md#menu-navigasi).
- **Screenshot tema.** `theme/screenshot.png` (1200 x 900) dipakai WordPress di daftar tema. Ganti sesuai identitas Anda.
- **Copyright.** Footer menampilkan "© tahun nama situs" (`App::copyright()`).

## Teks yang ditetapkan di kode

Teks berikut ada di Blade atau composer, bukan dari pengaturan WordPress:

| Teks | Lokasi |
| --- | --- |
| "Live Update" dan "Live" | `components/navbar.blade.php`, `components/nav-drawer.blade.php` (`liveLabel`) |
| "Terkini", "Utama", "Terbaru" | `App.php` (`categoryStrip()`, `quickTabs()`) |
| "Kabar Terbaru" | `FrontPage.php` |
| "Tentang", "Redaksi", "Kontak" | `App.php` (`footerColumns()`) |
| "Ikuti di", "Ke atas" | `components/footer.blade.php` |
| "Terpopuler", "Topik populer", "Lihat semua" | komponen `trending-list`, `search-modal`, `section-header` |
| "Cari berita…" | `components/search-field.blade.php`, `components/search-modal.blade.php` |

Ubah teks di tempat ini, atau buat terjemahan (lihat berikut).

## Bahasa dan terjemahan

- Text domain tema adalah `sage` (`theme/style.css` dan semua pemanggilan `__()`). Text domain ini tidak perlu diganti.
- Repo publik tidak menyertakan folder `resources/lang/`. Skrip `translate` di `theme/package.json` mengarah ke folder tersebut, jadi Anda perlu membuatnya sendiri jika ingin menerjemahkan teks.
- Bahasa kanal feed diatur di `mu-plugins/pu-feeds.php` (lihat [Konfigurasi](konfigurasi.md#feed-rss)).

!!! warning
    Jangan mengubah text domain di `style.css` tanpa mengubah semua pemanggilan `__()`. Jika tidak, terjemahan tidak akan dimuat.

## Metadata tema

Ubah header di `theme/style.css` (`Theme Name`, `Theme URI`, `Author`, `Description`, `Version`). Nama tema tampil di daftar tema WordPress. Slug tema mengikuti nama folder, jadi ubah nama folder dan `vite.config.js` (lihat [Instalasi](instalasi.md#4-sesuaikan-base)) bila Anda mengganti slug.

## Lisensi

Kode tema dirilis di bawah MIT (`theme/style.css`). Jika Anda memakai dan mendistribusikan ulang kode ini, sertakan pemberitahuan hak cipta yang berlaku.
