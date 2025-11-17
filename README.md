# 🚀 Praktikum I – PHP Framework (CodeIgniter 4)

Praktikum ini membahas dasar hingga lanjutan pengembangan aplikasi menggunakan PHP Framework CodeIgniter 4. Materi meliputi routing, controller, view, template, CRUD, login, upload gambar, relasi tabel, dan AJAX.

---

## 📦 Persiapan

| Langkah | Keterangan |
|---------|------------|
| Install XAMPP | Untuk menjalankan Apache & MySQL |
| Install CodeIgniter 4 | Framework utama |
| Buat folder proyek | `C:/xampp/htdocs/lab7_php_ci` |
| Ekstrak CI4 | Letakkan di dalam folder proyek |

---

## ⚙️ Pengaturan Dasar CodeIgniter 4

### 🔧 1. Konfigurasi Routes

**File:** `app/Config/Routes.php`

```php
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
```

### 🧩 2. Aktifkan Ekstensi PHP di XAMPP

**Buka:** XAMPP → Apache → Config → PHP.ini

Hilangkan tanda `;` pada baris berikut:

```ini
;extension=mysqli
;extension=intl
```

Menjadi:

```ini
extension=mysqli
extension=intl
```

### 🛠️ 3. Mode Development

**Buka file:** `.env`

Ubah:

```env
CI_ENVIRONMENT = production
```

Menjadi:

```env
CI_ENVIRONMENT = development
```

---

## 🧱 Membuat Controller "Page"

**File:** `app/Controllers/Page.php`

```php
<?php
namespace App\Controllers;

class Page extends BaseController
{
    public function about() { 
        echo "Ini halaman About"; 
    }
    
    public function contact() { 
        echo "Ini halaman Contact"; 
    }
    
    public function faqs() { 
        echo "Ini halaman FAQ"; 
    }
}
```

---

## 🎨 Membuat View Sederhana

**File:** `app/Views/about.php`

```php
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
```

**Controller:**

```php
public function about()
{
    return view('about', [
        'title' => 'Halaman About',
        'content' => 'Ini adalah halaman tentang.'
    ]);
}
```

---

## 🧩 Template Header & Footer

**Folder:** `app/Views/template/`

### header.php

```php
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
```

### footer.php

```php
</section>
<aside id="sidebar">Sidebar</aside>
</section>
<footer><p>&copy; 2021 - Universitas Pelita Bangsa</p></footer>
</body>
</html>
```

---

## 🎨 Menambahkan CSS

**File:** `public/style.css`

Tambahkan styling sesuai kebutuhan layout, sidebar, navbar, dll.

---

## 🧪 Praktikum Lanjutan – Artikel

### 🗄️ Membuat Database & Tabel Artikel

```sql
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
```

### 📘 Model Artikel

**File:** `app/Models/ArtikelModel.php`

```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul','isi','status','slug','gambar'];
}
```

### 📙 Controller Artikel

**File:** `app/Controllers/Artikel.php`

```php
public function index()
{
    $model = new ArtikelModel();
    $artikel = $model->findAll();

    return view('artikel/index', compact('artikel'));
}
```

### 📰 View Artikel

**File:** `app/Views/artikel/index.php`

```php
<?php foreach($artikel as $row): ?>
<h2><?= $row['judul']; ?></h2>
<p><?= substr($row['isi'],0,200); ?></p>
<hr>
<?php endforeach; ?>
```

### 🔀 Routing Artikel

```php
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');
```

---

## 🛠️ CRUD Admin Artikel

### ➕ Tambah Artikel

**Controller:**

```php
public function add()
{
    return view('artikel/form_add');
}
```

**View:**

```php
<form method="post">
    <input type="text" name="judul">
    <textarea name="isi"></textarea>
    <input type="submit" value="Kirim">
</form>
```

### ✏️ Edit Artikel

```php
public function edit($id)
{
    $model = new ArtikelModel();
    $data = $model->find($id);

    return view('artikel/form_edit', compact('data'));
}
```

### ❌ Delete Artikel

```php
public function delete($id)
{
    $model = new ArtikelModel();
    $model->delete($id);

    return redirect('admin/artikel');
}
```

---

## 🔐 Login System

### 🗄️ Tabel User

```sql
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(200),
    useremail VARCHAR(200),
    userpassword VARCHAR(200)
);
```

### 👤 Model User

**File:** `app/Models/UserModel.php`

```php
class UserModel extends Model {
    protected $table = 'user';
    protected $allowedFields = ['username','useremail','userpassword'];
}
```

### 🔑 Controller Login

```php
public function login()
{
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
}
```

### 🛡️ Auth Filter

**File:** `app/Filters/Auth.php`

```php
public function before()
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/user/login');
    }
}
```

**Tambahkan ke:** `app/Config/Filters.php`

---

## 🔍 Pagination & Search

```php
$artikel = $model->like('judul',$q)->paginate(10);
$pager = $model->pager;
```

---

## 🖼️ Upload Gambar

**Controller:**

```php
$file = $this->request->getFile('gambar');
$file->move(ROOTPATH.'public/gambar');
```

**Form:**

```php
<form method="post" enctype="multipart/form-data">
    <input type="file" name="gambar">
</form>
```

---

## 🗂️ Relasi Kategori

### Tabel Kategori

```sql
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100),
    slug_kategori VARCHAR(100)
);
```

### Tambah Relasi ke artikel

```sql
ALTER TABLE artikel
ADD COLUMN id_kategori INT,
ADD CONSTRAINT fk_kategori
FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori);
```

---

## ⚡ AJAX CRUD (Ringkas)

**Controller:**

```php
public function getData() { 
    return $this->response->setJSON($data); 
}

public function create() { 
    // ... 
}

public function update($id) { 
    // ... 
}

public function delete($id) { 
    // ... 
}
```

---

## 🏁 Penutup

Materi praktikum mencakup:

- ✅ Instalasi CI4
- ✅ Routing
- ✅ Controller & View
- ✅ Template
- ✅ CRUD
- ✅ Login
- ✅ Pagination
- ✅ Upload File
- ✅ Relasi Database
- ✅ AJAX CRUD

---

📌 **Semua langkah disusun sesuai alur praktikum.**
