<?php
namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table         = 'artikel';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $allowedFields = [
        'judul', 'isi', 'slug', 'gambar', 'status', 'id_kategori',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Builder: Ambil semua artikel dengan join kategori (untuk paginate)
     */
    public function getArtikelDenganKategori()
{
    return $this->db->table('artikel')
        ->select('artikel.*, kategori.nama_kategori, artikel.created_at') // tambahkan ini
        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
        ->get()
        ->getResultArray();
}


    /**
     * Builder: Filter berdasarkan id_kategori (untuk paginate)
     */
    public function getArtikelDenganKategoriFiltered($id_kategori)
    {
        return $this->select('artikel.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                    ->where('artikel.id_kategori', $id_kategori);
    }

    /**
     * Builder: Pencarian berdasarkan judul dan kategori (untuk paginate)
     */
    public function searchArtikel($q = null, $kategori = null)
    {
        $builder = $this->db->table('artikel')
                            ->select('artikel.*, kategori.nama_kategori, artikel.created_at')
                            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');
    
        if ($q) {
            $builder->like('artikel.judul', $q);
        }
    
        if ($kategori) {
            $builder->where('artikel.id_kategori', $kategori);
        }
    
        $builder->orderBy('artikel.created_at', 'DESC'); // biar hasil terurut
    
        return $builder->get()->getResultArray();
    }
    
    public function getArtikelDenganKategoriQuery()
    {
        return $this->select('artikel.*, kategori.nama_kategori, artikel.created_at') // tambahkan ini juga
                    ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');
    }
    
    public function getPaginatedArtikel($perPage, $kategori = null, $q = null)
    {
        if ($kategori || $q) {
            $this->select('artikel.*, kategori.nama_kategori')
                 ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                 ->orderBy('created_at', 'DESC');
    
            if ($kategori) {
                $this->where('artikel.id_kategori', $kategori);
            }
    
            if ($q) {
                $this->like('artikel.judul', $q);
            }
        } else {
            $this->select('artikel.*, kategori.nama_kategori')
                 ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                 ->orderBy('created_at', 'DESC');
        }
    
        return $this->paginate($perPage, 'group1');
    }
    
    public function getAllForAjax()
{
    return $this->select('artikel.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                ->orderBy('artikel.created_at', 'DESC')
                ->findAll();
}

}
