# Kanal & media sosial footer

Lembar isian URL kanal untuk footer tema. Salin tabel ini ke catatan proyek Anda, isi setelah akun dibuat, lalu terapkan ke situs (lihat "Cara menerapkan"). Kolom yang kosong = tombolnya **tidak tampil** di footer, jadi aman diisi bertahap.

URL kanal adalah alamat publik, jadi aman disimpan di repo proyek Anda.

## Isian

| Kanal | Kunci Customizer | URL | Contoh format |
| :--- | :--- | :--- | :--- |
| Google News | `pu_googlenews` | | `https://news.google.com/publications/XXXXXXXX` |
| WhatsApp Channel | `pu_whatsapp` | | `https://whatsapp.com/channel/XXXXXXXX` |
| Telegram | `pu_telegram` | | `https://t.me/namakanal` |
| Instagram | `pu_instagram` | | `https://www.instagram.com/namaakun` |
| YouTube | `pu_youtube` | | `https://www.youtube.com/@namaakun` |
| X | `pu_x` | | `https://x.com/namaakun` |
| TikTok | `pu_tiktok` | | `https://www.tiktok.com/@namaakun` |
| Facebook | `pu_facebook` | | `https://www.facebook.com/namahalaman` |

Tautan **RSS** (`/feed/`) selalu tampil dan tidak perlu diisi.

## Cara mendapatkan URL

- **WhatsApp Channel**: buka WhatsApp, lalu **Pembaruan > Saluran > + > Buat saluran**. Setelah jadi, buka info saluran, ketuk **Bagikan**, lalu salin tautannya. Sebaiknya pakai nomor WhatsApp Business khusus redaksi.
- **Telegram**: **New Channel > Public**, lalu tentukan nama pengguna. URL-nya `https://t.me/<nama pengguna>`. Kanal Telegram bisa diisi otomatis dari feed RSS situs memakai bot RSS atau layanan seperti IFTTT/Zapier.
- **Google News**: halaman publikasi tidak bisa didaftarkan manual lagi. Google membuatnya otomatis bila situs memenuhi syarat:
    1. berita orisinal dan terbit rutin;
    2. halaman Tentang, Redaksi, Kontak, dan nama penulis di setiap berita;
    3. situs terverifikasi di Google Search Console (bisa lewat Site Kit).

    Setelah halaman publikasi muncul (cari nama situs di [news.google.com](https://news.google.com)), salin URL-nya yang berbentuk `https://news.google.com/publications/...`. Sebelum itu, biarkan kosong.
- **Media sosial lain**: salin URL halaman profil dari peramban.

## Cara menerapkan

**Lewat wp-admin** (paling mudah): **Appearance > Customize > Plstn-U**, isi kolom "URL …" sesuai tabel, lalu **Publish**.

**Lewat WP-CLI** (bila ada akses): satu perintah per kanal, misalnya:

```bash
wp theme mod set pu_whatsapp "https://whatsapp.com/channel/XXXXXXXX"
wp theme mod set pu_telegram "https://t.me/namakanal"
```

Untuk mengosongkan lagi: `wp theme mod remove pu_whatsapp`.

Setelah diterapkan, cek footer di beranda: tombol yang diisi muncul, yang kosong tidak.
