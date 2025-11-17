Praktikum I – PHP Framework (CodeIgniter 4)
📌 Persiapan

Install XAMPP dan CodeIgniter 4

Buat folder proyek di:

C:/xampp/htdocs/lab7_php_ci


Ekstrak CodeIgniter ke dalam folder tersebut

📌 Pengaturan Dasar CodeIgniter 4
1. Mengatur Routes

Edit file:

app/Config/Routes.php


Tambahkan:

$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

2. Mengaktifkan Ekstensi PHP di XAMPP

Buka:

XAMPP → Apache → Config → PHP.ini


Hilangkan tanda ; pada:

extension=mysqli
extension=intl

3. Mode Development

Buka file:

.env


Ubah:

CI_ENVIRONMENT = production


menjadi:

CI_ENVIRONMENT = development

📌 Membuat Controller “Page”

Buat file:

app/Controllers/Page.php


Isi:

<?php
namespace App\Controllers;

class Page extends BaseController
{
    public function about() { echo "Ini halaman About"; }
    public function contact() { echo "Ini halaman Contact"; }
    public function faqs() { echo "Ini halaman FAQ"; }
}

📌 Membuat View Sederhana

Buat file:

app/Views/about.php

<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
</head>
<body>
    <h1><?= $title; ?></h1>
    <p><?= $content; ?></p>
</body>
</html>


Controller di-update menjadi:

public function about()
{
    return view('about', [
        'title' => 'Halaman About',
        'content' => 'Ini adalah halaman tentang.'
    ]);
}

📌 Membuat Template Header & Footer

Folder:

app/Views/template/

header.php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body>
<header><h1>Layout Sederhana</h1></header>
<nav>
    <a href="/">Home</a>
    <a href="/artikel">Artikel</a>
    <a href="/about">About</a>
    <a href="/contact">Kontak</a>
</nav>
<section id="wrapper">
<section id="main">

footer.php
</section>
<aside id="sidebar">Sidebar</aside>
</section>
<footer><p>&copy; 2021 - Universitas Pelita Bangsa</p></footer>
</body>
</html>

📌 Menambahkan CSS

File:

public/style.css


Isi sesuai kebutuhan (layout, nav, sidebar, widget).

Praktikum – Framework Lanjutan
📌 Membuat Database & Tabel Artikel
CREATE DATABASE lab_ci4;
USE lab_ci4;

CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT,
    gambar VARCHAR(200),
    status TINYINT(1) DEFAULT 0,
    slug VARCHAR(200)
);

📌 Model Artikel

Buat file:

app/Models/ArtikelModel.php

<?php
namespace App\Models;
use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul','isi','status','slug','gambar'];
}

📌 Controller Artikel
app/Controllers/Artikel.php

public function index()
{
    $model = new ArtikelModel();
    $artikel = $model->findAll();

    return view('artikel/index', compact('artikel'));
}

📌 View Artikel

Folder:

app/Views/artikel/index.php

<?php foreach($artikel as $row): ?>
<h2><?= $row['judul']; ?></h2>
<p><?= substr($row['isi'],0,200); ?></p>
<hr>
<?php endforeach; ?>

📌 Menambahkan Routing Artikel
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

📌 CRUD Admin Artikel
1. Halaman Admin

Controller:

public function admin_index()
{
    $model = new ArtikelModel();
    $artikel = $model->findAll();

    return view('artikel/admin_index', compact('artikel'));
}

2. Tambah Artikel

Controller:

public function add()
{
    return view('artikel/form_add');
}


View form_add.php:

<form method="post">
<input type="text" name="judul">
<textarea name="isi"></textarea>
<input type="submit" value="Kirim">
</form>

3. Edit Artikel
public function edit($id)
{
    $model = new ArtikelModel();
    $data = $model->find($id);

    return view('artikel/form_edit', compact('data'));
}

4. Delete Artikel
public function delete($id)
{
    $model = new ArtikelModel();
    $model->delete($id);

    return redirect('admin/artikel');
}

Praktikum – Login System
📌 Membuat Tabel User
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(200),
    useremail VARCHAR(200),
    userpassword VARCHAR(200)
);

📌 Model User
app/Models/UserModel.php

class UserModel extends Model {
    protected $table = 'user';
    protected $allowedFields = ['username','useremail','userpassword'];
}

📌 Controller Login
public function login()
{
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
}

📌 Auth Filter
app/Filters/Auth.php

public function before()
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/user/login');
    }
}


Tambahkan ke:

app/Config/Filters.php

Praktikum – Pagination & Search
$artikel = $model->like('judul',$q)->paginate(10);
$pager = $model->pager;

Praktikum – Upload Gambar

Controller:

$file = $this->request->getFile('gambar');
$file->move(ROOTPATH.'public/gambar');


Form:

<form method="post" enctype="multipart/form-data">
<input type="file" name="gambar">

Praktikum – Relasi Kategori
Membuat tabel kategori:
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100),
    slug_kategori VARCHAR(100)
);


Relasi:

ALTER TABLE artikel
ADD COLUMN id_kategori INT,
ADD CONSTRAINT fk_kategori
FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori);

Praktikum – AJAX CRUD (Ringkas)

Controller:

public function getData() { return $this->response->setJSON($data); }
public function create() { … }
public function update($id) { … }
public function delete($id) { … }


View:

Menggunakan JavaScript Fetch / AJAX

Menampilkan data tabel dinamis

Edit, delete, upload gambar via AJAX

📌 Penutup

README ini berisi ringkasan lengkap seluruh proses praktikum, mulai dari:

Instalasi CI4

Routing

Controller & View

Template

CRUD

Login

Pagination

Upload Gambar

Relasi Kategori

AJAX CRUD

Seluruh langkah mengikuti alur praktikum sesuai instruksi.
