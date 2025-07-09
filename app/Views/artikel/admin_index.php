<?= $this->include('template/admin_header') ?>

<h2><?= esc($title) ?></h2>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success">
      <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger">
      <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<form method="get" action="<?= base_url('admin/artikel') ?>" class="form-inline mb-3">
  <input type="text" name="q" class="form-control mr-2" placeholder="Cari judul..."
         value="<?= esc($q ?? '') ?>">
  <select name="kategori" class="form-control mr-2" onchange="this.form.submit()">
    <option value="">-- Semua Kategori --</option>
    <?php foreach ($kategori as $k): ?>
      <option value="<?= $k['id_kategori'] ?>" <?= ($kategoriTerpilih ?? '') == $k['id_kategori'] ? 'selected' : '' ?>>
        <?= esc($k['nama_kategori']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-primary">Cari</button>
</form>

<p>
    <a href="<?= current_url() ?>" class="btn btn-outline-secondary">↺ Reset Filter</a>
</p>


<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Judul</th>
      <th>Kategori</th>
      <th>Status</th>
      <th>Tanggal Pembuatan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($artikel)): ?>
      <tr><td colspan="6" class="text-center">Belum ada artikel.</td></tr>
    <?php else: ?>
      <?php foreach ($artikel as $a): ?>
      <tr>
        <td><?= esc($a['id']) ?></td>
        <td><?= esc($a['judul']) ?></td>
        <td><?= esc($a['nama_kategori'] ?? '—') ?></td>
        <td><?= $a['status'] == 1 ? 'Aktif' : 'Nonaktif' ?></td>
        <td><?= date('d M Y H:i', strtotime($a['created_at'])) ?></td>
        <td>
          <a href="<?= base_url("admin/artikel/edit/{$a['id']}") ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= base_url("admin/artikel/delete/{$a['id']}") ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Yakin hapus?')">Delete</a>
        </td>
      </tr>
      <?php endforeach ?>
    <?php endif ?>
  </tbody>
</table>

<?= $pager->links('group1', 'page_admin_artikel') ?>

<div class="d-flex justify-content-between mb-3">
  <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
</div>
<?= $this->include('template/admin_footer') ?>
