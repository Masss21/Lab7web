<?php namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'Id_kategori';     // CASE‑SENSITIVE!
    protected $returnType    = 'array';
    protected $allowedFields = ['Nama_kategori','Slug_kategori'];
    // jangan pakai property 'fieldNames'; cukup allowedFields/primaryKey
    public function getAllForAjax()
{
    return $this->select('id_kategori, nama_kategori')->findAll();
}

}