<table class="table table-striped">
  <thead>
  <tr>
    <th>ID</th>
    <th>Judul</th>
    <th>Kategori</th>
    <th>Status</th>
    <th>Tanggal</th>
    <th>Aksi</th>
  </tr>
</thead>

  <tbody>
    <?php if (empty($artikel)): ?>
      <tr><td colspan="6" class="text-center">Tidak ada artikel.</td></tr>
    <?php else: ?>
      <?php foreach ($artikel as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= esc($a['judul']) ?></td>
          <td><?= esc($a['nama_kategori']) ?></td>
          <td><?= $a['status'] == 1 ? 'Aktif' : 'Nonaktif' ?></td>
          <td><?= date('d M Y H:i', strtotime($a['created_at'])) ?></td>
          <td>
            <button class="btn btn-sm btn-info btn-edit" 
        data-id="<?= $a['id'] ?>" 
        data-judul="<?= esc($a['judul']) ?>" 
        data-isi="<?= esc($a['isi']) ?>" 
        data-id_kategori="<?= $a['id_kategori'] ?>" 
        data-status="<?= $a['status'] ?>">Edit</button>

        <button class="btn btn-sm btn-danger btn-delete" 
        data-id="<?= $a['id'] ?>">Delete</button>

          </td>
        </tr>
      <?php endforeach ?>
    <?php endif ?>
  </tbody>
</table>

<!-- PAGINATION -->
<?php if ($pager): ?>
  <div class="d-flex justify-content-center mt-3" id="pagination-container">
    <?= $pager->links('group1', 'page_admin_artikel') ?>
</div>
<?php endif; ?>
