<?= $this->include('template/admin_header') ?>
<p>
    <a href="<?= base_url('admin/artikel') ?>" class="btn btn-secondary">← Kembali ke Daftar Artikel</a>
</p>

<h2><?= esc($title) ?></h2>

<?php if (! empty($errors)): ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif ?>



<form action="" method="post" enctype="multipart/form-data">

<form action="<?= current_url() ?>" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <p>
    <label for="judul">Judul</label><br>
    <input type="text" name="judul" id="judul" required
           value="<?= esc(old('judul')) ?>">
  </p>

  <p>
    <label for="isi">Isi</label><br>
    <textarea name="isi" id="isi" cols="50" rows="10"><?= esc(old('isi')) ?></textarea>
  </p>

  <p>
    <label for="id_kategori">Kategori</label><br>
    <select name="id_kategori" id="id_kategori" required>
      <option value="">-- Pilih Kategori --</option>
      <?php foreach ($kategori as $k): ?>
        <option value="<?= esc($k['id_kategori']) ?>"
          <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>>
          <?= esc($k['nama_kategori']) ?>
        </option>
      <?php endforeach ?>
    </select>
  </p>

  <p>
    <label for="gambar">Gambar (Opsional)</label>
    <input type="file" name="gambar" id="gambar">
</p>


  <p>
    <button type="submit" class="btn btn-primary">Kirim</button>
    <a href="<?= base_url('admin/artikel') ?>" class="btn btn-secondary">Batal</a>
  </p>
</form>

<?= $this->include('template/admin_footer') ?>
