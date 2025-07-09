<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use App\Models\ArtikelModel;
use App\Models\KategoriModel; // Pastikan ini di-import
use CodeIgniter\Files\File;

class AjaxController extends Controller
{
    public function index()
{
    // Ambil semua kategori
    $kategoriModel = new KategoriModel();
    $kategori = $kategoriModel->findAll();

    // Data pencarian/filter (opsional jika digunakan)
    $q = $this->request->getGet('q');
    $kategoriTerpilih = $this->request->getGet('kategori');

    return view('ajax/index', [
        'kategori' => $kategori,
        'q' => $q,
        'kategoriTerpilih' => $kategoriTerpilih,
        'title' => 'Halaman Admin AJAX'
    ]);
}


    public function getData()
{
    $artikelModel = new ArtikelModel();

    // Ambil parameter GET
    $q = $this->request->getGet('q');
    $kategori = $this->request->getGet('kategori');
    $sort_by = $this->request->getGet('sort_by') ?? 'artikel.id';
    $order = $this->request->getGet('order') ?? 'DESC';

    // Query builder
    $artikelModel->select('artikel.*, kategori.nama_kategori')
                 ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

    if (!empty($q)) {
        $artikelModel->like('artikel.judul', $q);
    }

    if (!empty($kategori)) {
        $artikelModel->where('artikel.id_kategori', $kategori);
    }
    

$allowedSorts = ['artikel.id', 'artikel.judul', 'kategori.nama_kategori', 'artikel.created_at'];
if (!in_array($sort_by, $allowedSorts)) {
    $sort_by = 'artikel.id'; // default aman
}
    $artikelModel->orderBy($sort_by, $order);

    // Paginasi
    $perPage = 5;
    $artikel = $artikelModel->paginate($perPage, 'group1');
    $pager = $artikelModel->pager;

    // Format tanggal (boleh tetap, atau diolah di view)
    foreach ($artikel as &$row) {
        $row['created_at_formatted'] = isset($row['created_at']) ? date('d-m-Y H:i:s', strtotime($row['created_at'])) : '-';
        $row['updated_at_formatted'] = isset($row['updated_at']) ? date('d-m-Y H:i:s', strtotime($row['updated_at'])) : '-';
    }

    return view('ajax/ajax_page', [
        'artikel' => $artikel,
        'pager' => $pager
    ]);
}



    public function getCategories()
{
    $model = new KategoriModel();
    $raw = $model->findAll();

    // Pastikan lowercase semua key supaya konsisten
    $data = array_map('array_change_key_case', $raw); 

    return $this->response->setJSON($data);
}



public function create()
{
    $artikelModel = new ArtikelModel();

    $rules = [
        'judul'       => 'required|min_length[3]|max_length[255]',
        'isi'         => 'required',
        'id_kategori' => 'required|integer',
        'status'      => 'required|in_list[0,1]',
        'gambar'      => 'if_exist|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]|max_size[gambar,1024]'
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $this->validator->getErrors(),
            'csrfHash' => csrf_hash()
        ]);
    }

    $gambarName = null;
    $file = $this->request->getFile('gambar');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $gambarName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/gambar', $gambarName);
    }

    $data = [
        'judul'       => $this->request->getPost('judul'),
        'isi'         => $this->request->getPost('isi'),
        'id_kategori' => $this->request->getPost('id_kategori'),
        'status'      => $this->request->getPost('status'),
        'gambar'      => $gambarName
    ];

    if ($artikelModel->save($data)) {
        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil ditambahkan',
            'csrfHash' => csrf_hash()
        ]);
    } else {
        if ($gambarName && file_exists(ROOTPATH . 'public/gambar/' . $gambarName)) {
            unlink(ROOTPATH . 'public/gambar/' . $gambarName);
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menambahkan artikel',
            'csrfHash' => csrf_hash()
        ]);
    }
}


public function update($id = null)
{
    $artikelModel = new \App\Models\ArtikelModel();
    $artikel = $artikelModel->find($id);

    if (!$artikel) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Artikel tidak ditemukan.',
            'csrfHash' => csrf_hash()
        ]);
    }

    $rules = [
        'judul'       => 'required|min_length[3]|max_length[255]',
        'isi'         => 'required',
        'id_kategori' => 'required|integer',
        'status'      => 'required'
    ];

    $file = $this->request->getFile('gambar');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $rules['gambar'] = 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]|max_size[gambar,1024]';
    }

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $this->validator->getErrors(),
            'csrfHash' => csrf_hash()
        ]);
    }

    $gambarName = $artikel['gambar'];

    if ($file && $file->isValid() && !$file->hasMoved()) {
        if ($gambarName && file_exists(ROOTPATH . 'public/gambar/' . $gambarName)) {
            unlink(ROOTPATH . 'public/gambar/' . $gambarName);
        }

        $gambarName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/gambar', $gambarName);
    }

    $data = [
        'judul'       => $this->request->getPost('judul'),
        'isi'         => $this->request->getPost('isi'),
        'id_kategori' => $this->request->getPost('id_kategori'),
        'status'      => $this->request->getPost('status'),
        'gambar'      => $gambarName,
    ];

    if ($artikelModel->update($id, $data)) {
        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil diperbarui',
            'csrfHash' => csrf_hash()
        ]);
    } else {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menyimpan perubahan',
            'csrfHash' => csrf_hash()
        ]);
    }
}


    

public function delete($id = null)
{
    $artikelModel = new \App\Models\ArtikelModel();
    $artikel = $artikelModel->find($id);

    if (!$artikel) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
    }

    // Hapus gambar jika ada
    if ($artikel['gambar'] && file_exists(ROOTPATH . 'public/gambar/' . $artikel['gambar'])) {
        unlink(ROOTPATH . 'public/gambar/' . $artikel['gambar']);
    }

    $artikelModel->delete($id);
    return $this->response->setJSON(['status' => 'OK', 'message' => 'Artikel berhasil dihapus.']);
}


    public function removeImage($id)
    {
        $artikelModel = new ArtikelModel();
        $artikel = $artikelModel->find($id);

        if (!$artikel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
        }

        if ($artikel['gambar']) {
            $filePath = ROOTPATH . 'public/gambar/' . $artikel['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($artikelModel->update($id, ['gambar' => NULL])) {
                return $this->response->setJSON(['status' => 'OK', 'message' => 'Gambar berhasil dihapus.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus nama gambar dari database.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Artikel ini tidak memiliki gambar.']);
        }
    }
}
