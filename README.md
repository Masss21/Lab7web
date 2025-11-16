# I.	PHP FRAMEWORK

**I.	Praktikum I**

Langkah Awal

Download dan install XAMPP dan CodeIgniter 4.

Buat folder baru bernama lab7_php_ci (opsional) di:

C:/xampp/htdocs


Ekstrak file CodeIgniter4 ke dalam folder tersebut.

Mengatur Routes

Buka file:

app/Config/Routes.php


Tambahkan kode berikut:

$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

Mengaktifkan Ekstensi PHP pada XAMPP

Jalankan XAMPP, klik Admin pada bagian Apache.

Klik Config → PHP.ini

Tekan CTRL + F lalu cari dan hilangkan tanda ; pada:

extension=mysqli
extension=intl

Mengakses CI 4 Melalui Browser

Buka XAMPP Shell, lalu jalankan:

cd htdocs/lab11_php_ci


Akses melalui browser:

localhost:8080

NOTE PENTING

Ubah file .env:

Lokasi:

xampp/htdocs/lab11_php_ci/.env


Ubah:

CI_ENVIRONMENT = production


menjadi:

CI_ENVIRONMENT = development

Menambah Route Lagi

Edit file:

app/Config/Routes.php


Tambahkan:

$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

Melihat Semua Routes di CLI

Jalankan perintah berikut di XAMPP Shell:

php spark routes

Mengakses Route Baru

Contoh:

http://localhost:8080/about


Jika muncul:

Error 404 file not found

maka controller belum dibuat.

Membuat Controller Page

Buat file baru:

app/Controllers/Page.php


Isi:

<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        echo "Ini halaman About";
    }

    public function contact()
    {
        echo "Ini halaman Contact";
    }

    public function faqs()
    {
        echo "Ini halaman FAQ";
    }
}


Reload browser dan halaman akan tampil.

Autoroute CodeIgniter

Autoroute default = aktif.

Untuk menonaktifkan, buka:

app/Config/Routes.php


Ubah:

$routes->setAutoRoute(true);

Menambah Method Baru

Pada Page.php:

public function tos()
{
    echo "ini halaman Term of Services";
}

Membuat View

Buat file:

app/Views/about.php


Isi:

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
</head>
<body>
    <h1><?= $title; ?></h1>
    <hr>
    <p><?= $content; ?></p>
</body>
</html>


Ubah controller:

public function about()
{
    return view('about', [
        'title' => 'Halaman About',
        'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.'
    ]);
}

Menambahkan Layout Template

Pada CodeIgniter, asset CSS & JS disimpan di folder:

public/


Buat file:

public/style.css


Buat folder template:

app/Views/template/

header.php
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title; ?></title>
<link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>
<div id="container">
<header>
<h1>Layout Sederhana</h1>
</header>
<nav>
    <a href="<?= base_url('/');?>" class="active">Home</a>
    <a href="<?= base_url('/artikel');?>">Artikel</a>
    <a href="<?= base_url('/about');?>">About</a>
    <a href="<?= base_url('/contact');?>">Kontak</a>
</nav>
<section id="wrapper">
<section id="main">

footer.php
</section>
<aside id="sidebar">
    <div class="widget-box">
        <h3 class="title">Widget Header</h3>
        <ul>
            <li><a href="#">Widget Link</a></li>
            <li><a href="#">Widget Link</a></li>
        </ul>
    </div>
    <div class="widget-box">
        <h3 class="title">Widget Text</h3>
        <p>Vestibulum lorem elit, iaculis in nisl volutpat...</p>
    </div>
</aside>
</section>
<footer>
<p>&copy; 2021 - Universitas Pelita Bangsa</p>
</footer>
</div>
</body>
</html>

about.php (menggunakan template)
<?= $this->include('template/header'); ?>

<h1><?= $title; ?></h1>
<hr>
<p><?= $content; ?></p>

<?= $this->include('template/footer'); ?>
