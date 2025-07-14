Struktur database Project Artikel

Table Artikel

CREATE TABLE artikel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  isi TEXT NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 0, -- 1=Publish, 0=Draft
  id_kategori INT,
  id_user INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_kategori) REFERENCES kategori(id) ON DELETE SET NULL,
  FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE SET NULL
);


Table Kategori

CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE
);


Table User

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  level ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
