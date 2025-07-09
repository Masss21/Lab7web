<?= $this->include('template/header'); ?>
<script>
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfTokenValue = '<?= csrf_hash() ?>';
</script>

<link rel="stylesheet" href="<?= base_url('css/ajax-style.css') ?>">

<!-- Notifikasi -->
<div id="notification" style="display: none; position: fixed; top: 20px; right: 20px; padding: 10px 20px; border-radius: 8px; font-weight: bold; z-index: 9999;"></div>

<a href="<?= base_url('/artikel'); ?>" class="btn btn-outline-primary mb-3">Lihat Halaman Publik</a>
<button id="btnAddArticle" class="btn btn-success mb-3">Tambah Artikel</button>

<!-- FORM FILTER KATEGORI -->
<form id="filterForm" class="form-inline mb-3">
  <input type="text" name="q" class="form-control mr-2" placeholder="Cari judul..." value="<?= esc($q ?? '') ?>">
  
  <select name="kategori" class="form-control mr-2">
    <option value="">-- Semua Kategori --</option>
    <?php foreach ($kategori as $k): ?>
      <option value="<?= $k['Id_kategori'] ?>" <?= ($kategoriTerpilih ?? '') == $k['Id_kategori'] ? 'selected' : '' ?>>
        <?= esc($k['Nama_kategori']) ?>
      </option>
    <?php endforeach; ?>
  </select>
<p>
  <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary">↺ Reset Filter</button>
</p>
  <button type="submit" class="btn btn-primary">Cari</button>
</form>




<!-- Form Tambah/Edit Artikel -->
<div id="articleFormContainer" class="card p-4 mb-4" style="display: none;">
    <h2 id="formTitle">Form Tambah Artikel</h2>
    <form id="articleForm" enctype="multipart/form-data">
        <input type="hidden" id="articleId" name="id">
        <div class="form-group mb-3">
            <label for="judul">Judul:</label>
            <input type="text" class="form-control" id="judul" name="judul" required>
        </div>
        <div class="form-group mb-3">
            <label for="isi">Isi:</label>
            <textarea class="form-control" id="isi" name="isi" rows="4" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="id_kategori">Kategori:</label>
            <select class="form-control" id="id_kategori" name="id_kategori" required></select>
        </div>
        <div class="form-group mb-3">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="1">Publik</option>
                <option value="0">Draft</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="gambar">Gambar:</label>
            <input type="file" class="form-control" id="gambar" name="gambar">
        </div>
        <button type="submit" class="btn btn-primary" id="btnSubmitForm">Simpan</button>
        <button type="button" class="btn btn-secondary ms-2" id="btnCancelForm">Batal</button>
    </form>
</div>

<div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
  <div class="loading-bars">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
</div>

<div id="article-container"></div>

<script src="<?= base_url('assets/jquery-3.7.1.min.js') ?>"></script>

<script>
$(document).ready(function () {
    const BASE_IMAGE = '<?= base_url('gambar/') ?>';

    function updateCSRF(newToken) {
        if (newToken) {
            csrfTokenValue = newToken;
        }
    }

    function showNotification(message, type = 'success') {
        const notification = $('#notification');
        notification.html(message);
        notification.css({
            backgroundColor: type === 'success' ? '#d4edda' : '#f8d7da',
            color: type === 'success' ? '#155724' : '#721c24',
            border: `1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'}`
        }).fadeIn(300).delay(3000).fadeOut(500);
    }

    function loadCategories(selectedId = null) {
        $.get("<?= base_url('ajax/getCategories') ?>", function (data) {
            let options = '<option value="">-- Pilih Kategori --</option>';
            data.forEach(function (item) {
                options += `<option value="${item.id_kategori}" ${selectedId == item.id_kategori ? 'selected' : ''}>
                                ${item.nama_kategori}
                            </option>`;
            });
            $('#id_kategori').html(options);
        });
    }

    function loadData(url = '/ajax/getData') {
    const q = $('input[name="q"]').val();
    const kategori = $('select[name="kategori"]').val();

    url += `?q=${encodeURIComponent(q)}&kategori=${kategori}`;

    $('#loading-indicator').show();
    $('#article-container').hide();

    // Delay 3 detik agar loading terlihat
    setTimeout(() => {
        $('#article-container').load(url, function () {
            $('#loading-indicator').hide();
            $('#article-container').fadeIn(200);
            $('.pagination a').off('click').on('click', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                console.log('Klik halaman pagination', pageUrl);
                loadData(pageUrl);
             });
        });
    }, 500);
}



    // Handler submit filter form
$('#filterForm').on('submit', function(e) {
    e.preventDefault();
    loadData();
});

// Saat kategori diganti
$('#filterForm select[name="kategori"]').on('change', function () {
    $('#filterForm').submit(); // Trigger submit AJAX
});

// Tombol reset filter
$('#resetFilterBtn').on('click', function () {    
    $('#filterForm')[0].reset();
    $('input[name="q"]').val('');   // Reset semua input
    $('#filterForm').submit();     // Jalankan AJAX filter ulang
});



   $('#articleForm').on('submit', function (e) {
    e.preventDefault();

    $('#loading-indicator').show();
    $('#article-container').hide();

    let formData = new FormData(this);
    let id = $('#articleId').val();
    let url = id ? `<?= base_url('ajax/update/') ?>${id}` : `<?= base_url('ajax/create') ?>`;

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': csrfTokenValue
        },
        success: function (res) {
            if (res.status === 'error') {
                $('#loading-indicator').hide();
                $('#article-container').fadeIn(200);
                showNotification('Gagal: ' + Object.values(res.message).join(', '), 'error');
            } else {
                $('#articleFormContainer').slideUp();
                showNotification(res.message, 'success');

                // Delay 3 detik agar loading terlihat
                setTimeout(() => {
                    loadData(); // otomatis tampilkan data baru
                    $('#loading-indicator').hide();
                    $('#article-container').fadeIn(200);
                }, 1800);
            }

            // Update CSRF
            updateCSRF(res.csrfHash);
        },
        error: function (xhr) {
            $('#loading-indicator').hide();
            $('#article-container').fadeIn(200);
            console.error('AJAX ERROR:', xhr.responseText);
            showNotification('Terjadi kesalahan saat menyimpan data.', 'error');
        }
    });
});


    $('#btnCancelForm').on('click', function () {
        $('#articleFormContainer').slideUp();
    });

    $('#btnAddArticle').on('click', function () {
        $('#formTitle').text('Form Tambah Artikel');
        $('#articleForm')[0].reset();
        $('#articleId').val('');
        $('#btnSubmitForm').text('Simpan');
        loadCategories();
        $('#articleFormContainer').slideDown();
    });

    $(document).on('click', '.btn-edit', function () {
        $('#formTitle').text('Form Edit Artikel');
        $('#articleFormContainer').slideDown();

        const id = $(this).data('id');
        const judul = $(this).data('judul');
        const isi = decodeURIComponent($(this).data('isi'));
        const status = $(this).data('status');
        const id_kategori = $(this).data('id_kategori');

        $('#articleId').val(id);
        $('#judul').val(judul);
        $('#isi').val(isi);
        $('#status').val(status);

        loadCategories(id_kategori);
        $('#btnSubmitForm').text('Update');
    });

    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Yakin ingin menghapus artikel ini?')) return;
        let id = $(this).data('id');

        $.ajax({
            url: `<?= base_url('ajax/delete/') ?>${id}`,
            method: 'POST',
            data: { '_method': 'DELETE' },
            headers: {
                'X-CSRF-TOKEN': csrfTokenValue
            },
            success: function (res) {
                showNotification(res.message, 'success');
                loadData();
                updateCSRF(res.csrfHash);
            },
            error: function (xhr) {
                console.error('ERROR DELETE:', xhr.responseText);
                showNotification('Gagal menghapus data', 'error');
            }
        });
    });

    // Load awal
    loadData();
    loadCategories();
});
</script>
<script src="<?= base_url('js/admin-ajax.js') ?>"></script>

<div class="d-flex justify-content-between mb-3">
  <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
</div>

<?= $this->include('template/footer'); ?>

