<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArtikelModel;

class Post extends ResourceController
{
    use ResponseTrait;

    // GET /post
    public function index()
    {
        $model = new ArtikelModel();
        $data['artikel'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    // GET /post/{id}
    public function show($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Data tidak ditemukan.');
    }

    // POST /post
    public function create()
    {
        $data = [
            'judul' => $this->request->getVar('judul'),
            'isi'   => $this->request->getVar('isi'),
        ];

        if (!$data['judul'] || !$data['isi']) {
            return $this->failValidationErrors("Judul dan Isi tidak boleh kosong.");
        }

        $model = new ArtikelModel();
        $model->insert($data);
        return $this->respondCreated([
            'status' => 201,
            'messages' => ['success' => 'Data artikel berhasil ditambahkan.']
        ]);
    }

    // PUT /post/{id}
    public function update($id = null)
    {
        $model = new ArtikelModel();

        if (!$model->find($id)) {
            return $this->failNotFound('Data tidak ditemukan.');
        }

        $data = [
            'judul' => $this->request->getVar('judul'),
            'isi'   => $this->request->getVar('isi'),
        ];

        $model->update($id, $data);
        return $this->respond([
            'status' => 200,
            'messages' => ['success' => 'Data artikel berhasil diubah.']
        ]);
    }

    // DELETE /post/{id}
    public function delete($id = null)
    {
        $model = new ArtikelModel();

        if (!$model->find($id)) {
            return $this->failNotFound('Data tidak ditemukan.');
        }

        $model->delete($id);
        return $this->respondDeleted([
            'status' => 200,
            'messages' => ['success' => 'Data artikel berhasil dihapus.']
        ]);
    }
}
