# 🚀 Praktikum I – PHP Framework (CodeIgniter 4)

Praktikum ini membahas dasar hingga lanjutan pengembangan aplikasi menggunakan PHP Framework CodeIgniter 4.
Materi meliputi routing, controller, view, template, CRUD, login, upload gambar, relasi tabel, dan AJAX.

### 📦 Persiapan
Langkah	Keterangan
| Langkah               | Keterangan                       |
| --------------------- | -------------------------------- |
| Install XAMPP         | Untuk menjalankan Apache & MySQL |
| Install CodeIgniter 4 | Framework utama                  |
| Buat folder proyek    | `C:/xampp/htdocs/lab7_php_ci`    |
| Ekstrak CI4           | Letakkan di dalam folder proyek  |


## ⚙️ Pengaturan Dasar CodeIgniter 4
1. Konfigurasi Routes

File:

**app/Config/Routes.php**

Tambahkan:
*$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');*


2. Aktifkan Ekstensi PHP di XAMPP
Buka:
**XAMPP → Apache → Config → PHP.ini**

Hilangkan tanda:

```ini
;extension=mysqli
;extension=intl
```

3. 🛠️ Mode Development

Buka file:
**.env**
Ubah:

**CI_ENVIRONMENT = production**

Menjadi:

**CI_ENVIRONMENT = development**


4. 🧱 Membuat Controller “Page”

File:

app/Controllers/Page.php

<?php
namespace App\Controllers;

class Page extends BaseController
{
    public function about() { echo "Ini halaman About"; }
    public function contact() { echo "Ini halaman Contact"; }
    public function faqs() { echo "Ini halaman FAQ"; }
}



