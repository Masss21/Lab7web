<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    /**
     * PUBLIC: Menampilkan daftar artikel untuk publik
     */
    public function index()
    {
        $artikelModel  = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $rawKategori = $kategoriModel->findAll();
        $kategori    = array_map('array_change_key_case', $rawKategori);

        $kategoriTerpilih = $this->request->getGet('kategori');
        $q                = $this->request->getGet('q');

        if ($kategoriTerpilih || $q) {
            $artikel = $artikelModel->searchArtikel($q, $kategoriTerpilih);
        } else {
            $artikel = $artikelModel->getArtikelDenganKategori();
        }

        return view('artikel/index', [
            'title'            => 'Daftar Artikel',
            'artikel'          => $artikel,
            'kategori'         => $kategori,
            'kategoriTerpilih' => $kategoriTerpilih,
            'q'                => $q,
        ]);
    }

    /**
     * ADMIN: Menampilkan daftar artikel untuk admin
     */
    public function admin_index()
    {
        $artikelModel  = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $rawKategori = $kategoriModel->findAll();
        $kategori    = array_map('array_change_key_case', $rawKategori);

        $kategoriTerpilih = $this->request->getGet('kategori');
        $q                = $this->request->getGet('q');
        $perPage          = 4;

        $artikel = $artikelModel->getPaginatedArtikel($perPage, $kategoriTerpilih, $q);

        return view('artikel/admin_index', [
            'title'            => 'Daftar Artikel (Admin)',
            'artikel'          => $artikel,
            'pager'            => $artikelModel->pager,
            'kategori'         => $kategori,
            'kategoriTerpilih' => $kategoriTerpilih,
            'q'                => $q,
        ]);
    }

    /**
     * ADMIN: Menampilkan form tambah artikel
     */
    public function add()
    {
        $kategoriModel = new KategoriModel();
        $rawData       = $kategoriModel->findAll();
        $kategori      = array_map('array_change_key_case', $rawData);

        return view('artikel/add', [
            'title'    => 'Tambah Artikel',
            'kategori' => $kategori,
            'errors'   => session()->getFlashdata('errors') ?? []
        ]);
    }

    /**
     * ADMIN: Menyimpan artikel baru
     */
    public function save_artikel()
    {
        $artikelModel = new ArtikelModel();

        $rules = [
            'judul'       => 'required|min_length[5]|max_length[255]',
            'isi'         => 'required',
            'id_kategori' => 'required|integer',
            'gambar'      => [
                'is_image[gambar]',
                'max_size[gambar,2048]',
                'mime_in[gambar,image/jpg,image/jpeg,image/png]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file     = $this->request->getFile('gambar');
        $fileName = null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $fileName);
        }

        $data = [
            'judul'       => $this->request->getPost('judul'),
            'isi'         => $this->request->getPost('isi'),
            'slug'        => url_title($this->request->getPost('judul'), '-', true),
            'gambar'      => $fileName,
            'id_kategori' => $this->request->getPost('id_kategori'),
            'status'      => 1,
        ];

        $artikelModel->insert($data);

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil ditambahkan');
    }

    /**
     * ADMIN: Menampilkan form edit artikel
     */
    public function edit($id)
    {
        $artikelModel  = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $artikel = $artikelModel->find($id);
        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound("Artikel tidak ditemukan");
        }

        $kategori = $kategoriModel->findAll();
        $kategori = array_map(function ($row) {
            return array_change_key_case($row, CASE_LOWER);
        }, $kategori);

        return view('artikel/edit', [
            'title'    => 'Edit Artikel',
            'artikel'  => $artikel,
            'kategori' => $kategori,
            'errors'   => session()->getFlashdata('errors') ?? [],
            'validation' => \Config\Services::validation(),
        ]);
    }

    /**
     * ADMIN: Memproses update artikel (POST)
     */
    public function update($id)
{
    $artikelModel  = new ArtikelModel();
    $artikel       = $artikelModel->find($id);

    if (!$artikel) {
        throw PageNotFoundException::forPageNotFound("Artikel tidak ditemukan");
    }

    // Validasi kondisi
    $gambarRules = [];
    $file = $this->request->getFile('gambar');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $gambarRules = [
            'is_image[gambar]',
            'max_size[gambar,2048]',
            'mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ];
    }

    $rules = [
        'judul'       => 'required|min_length[5]|max_length[255]',
        'isi'         => 'required',
        'id_kategori' => 'required|integer',
    ];

    if (!empty($gambarRules)) {
        $rules['gambar'] = $gambarRules;
    }

    if (! $this->validate($rules)) {
        return redirect()->back()
                         ->withInput()
                         ->with('errors', $this->validator->getErrors());
    }

    $fileName = $artikel['gambar'];

    if ($file && $file->isValid() && !$file->hasMoved()) {
        if ($fileName && file_exists(ROOTPATH . 'public/uploads/' . $fileName)) {
            unlink(ROOTPATH . 'public/uploads/' . $fileName);
        }

        $fileName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads', $fileName);
    }

    $data = [
        'judul'       => $this->request->getPost('judul'),
        'isi'         => $this->request->getPost('isi'),
        'slug'        => url_title($this->request->getPost('judul'), '-', true),
        'id_kategori' => $this->request->getPost('id_kategori'),
        'gambar'      => $fileName,
    ];

    $artikelModel->update($id, $data);

    return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil diperbarui');
}


    /**
     * ADMIN: Menghapus artikel berdasarkan ID
     */
    public function delete($id)
    {
        $artikelModel = new ArtikelModel();
        $artikel = $artikelModel->find($id);

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound("Artikel tidak ditemukan");
        }

        if ($artikel['gambar'] && file_exists(ROOTPATH . 'public/uploads/' . $artikel['gambar'])) {
            unlink(ROOTPATH . 'public/uploads/' . $artikel['gambar']);
        }

        $artikelModel->delete($id);

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil dihapus');
    }

    /**
     * PRIVATE: Memastikan pengguna sudah login
     */
    private function checkLogin()
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu')->send();
        }
    }

    public function view($slug)
    {
        $this->checkLogin();
        $model = new ArtikelModel();

        $artikel = $model
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.Id_kategori = artikel.id_kategori', 'left')
            ->where('slug', $slug)
            ->first();

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('artikel/detail', [
            'artikel' => $artikel,
            'title'   => $artikel['judul'],
        ]);
    }

}
