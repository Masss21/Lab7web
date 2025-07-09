<?= $this->include('template/admin_header'); ?>  

<h2><?= $title; ?></h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/artikel/update/' . $artikel['id']) ?>" method="post" enctype="multipart/form-data">

    <?= csrf_field(); ?>

    <p>
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" required
            value="<?= old('judul', $artikel['judul'] ?? ''); ?>">
    </p>

    <p>
        <label for="isi">Isi</label>
        <textarea name="isi" id="isi" cols="50" rows="10"><?= old('isi', $artikel['isi'] ?? ''); ?></textarea>
    </p>

    <p>
        <label for="id_kategori">Kategori</label>
        <select name="id_kategori" id="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>"
                    <?= old('id_kategori', $artikel['id_kategori'] ?? '') == $k['id_kategori'] ? 'selected' : ''; ?>>
                    <?= esc($k['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p><input type="submit" value="Kirim" class="btn btn-large"></p>
</form>

<?= $this->include('template/admin_footer'); ?>
