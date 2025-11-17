# 🚀 Praktikum I – PHP Framework (CodeIgniter 4)

Praktikum ini membahas dasar hingga lanjutan pengembangan aplikasi menggunakan PHP Framework **CodeIgniter 4**. Materi meliputi *routing*, *controller*, *view*, *template*, *CRUD*, *login*, *upload gambar*, *relasi tabel*, dan *AJAX*.

---

## 📦 Persiapan
Langkah	Keterangan
| Langkah               | Keterangan                       |
| --------------------- | -------------------------------- |
| Install XAMPP         | Untuk menjalankan Apache & MySQL |
| Install CodeIgniter 4 | Framework utama                  |
| Buat folder proyek    | `C:/xampp/htdocs/lab7_php_ci`    |
| Ekstrak CI4           | Letakkan di dalam folder proyek  |

## ⚙️ Pengaturan Dasar CodeIgniter 4

### 🔧 1. Konfigurasi Routes

**File:** `app/Config/Routes.php`

Tambahkan baris kode berikut ini di dalam file tersebut:

```php
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');




