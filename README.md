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
🔧 1. Konfigurasi Routes

**File:** `app/Config/Routes.php`

Tambahkan baris kode berikut ini di dalam file tersebut:

```php
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
'''

### 🧩 2. Aktifkan Ekstensi PHP di XAMPP

Buka file konfigurasi `PHP.ini` melalui menu XAMPP:

> XAMPP → Apache → Config → PHP.ini

Hilangkan tanda komentar (`;`) pada baris berikut untuk mengaktifkan ekstensi:

```ini
extension=mysqli
extension=intl'''

### 🛠️ 3. Mode Development

Buka file pengaturan lingkungan (`.env`):

**File:** `.env`

Ubah variabel lingkungan `CI_ENVIRONMENT` dari `production` menjadi `development`:

```ini
CI_ENVIRONMENT = development'''

🧩 2. Aktifkan Ekstensi PHP di XAMPP
Buka file konfigurasi PHP.ini melalui menu XAMPP:
XAMPP → Apache → Config → PHP.ini
Hilangkan tanda komentar (;) pada baris berikut untuk mengaktifkan ekstensi:
ini
extension=mysqli
extension=intl






