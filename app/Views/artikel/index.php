<?= $this->include('template/header') ?>

<!-- Tombol Login -->
<p style="text-align: right;">
    <a href="<?= base_url('login') ?>" class="btn btn-sm btn-primary">Login Admin - Ajax</a>
</p>

<h2><?= esc($title) ?></h2>

<!-- FORM FILTER KATEGORI -->
<form method="get" action="<?= base_url('/artikel') ?>" class="form-inline mb-3">
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


<!-- DAFTAR ARTIKEL -->
<?php if(empty($artikel)): ?>
  <p>Belum ada artikel.</p>
<?php else: ?>
  <?php foreach($artikel as $a): ?>
    <article>
      <h3><a href="<?= base_url("artikel/{$a['slug']}") ?>"><?= esc($a['judul']) ?></a></h3>
      <p><em><?= esc($a['nama_kategori'] ?? '-') ?> — <?= date('d M Y', strtotime($a['created_at'])) ?></em></p>
      <div><?= esc(substr($a['isi'], 0, 200)) ?>...</div>
    </article>
  <?php endforeach ?>
<?php endif; ?>


<?= $this->include('template/footer') ?>
