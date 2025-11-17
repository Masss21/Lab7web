🎓 Praktikum I – PHP Framework (CodeIgniter 4)

Selamat datang di dokumentasi praktikum CodeIgniter 4!
Panduan ini memuat langkah-langkah mulai dari instalasi, routing, controller, view, hingga CRUD, login, upload gambar, dan relasi database.

🚀 Persiapan Awal
🔧 Install Tools

XAMPP

CodeIgniter 4

📁 Buat Folder Proyek
C:/xampp/htdocs/lab7_php_ci


Ekstrak CodeIgniter ke dalam folder tersebut.


⚙️ Pengaturan Dasar CodeIgniter 4
1️⃣ Mengatur Routes

Edit file:

app/Config/Routes.php


Tambahkan:

$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

2️⃣ Mengaktifkan Ekstensi PHP di XAMPP

Buka:

XAMPP → Apache → Config → PHP.ini


Hilangkan tanda ; pada:

extension=mysqli
extension=intl

3️⃣ Mengaktifkan Mode Development

Buka file:

.env


Ubah:

CI_ENVIRONMENT = production


➡️ Menjadi:

CI_ENVIRONMENT = development
