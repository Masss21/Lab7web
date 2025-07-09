$(function () {
    const articleContainer = $('#article-container').load(url);
    const loadingBar = $('#loading-bar');
    const searchForm = $('#search-form');
    const searchBox = $('#search-box');
    const categoryFilter = $('#category-filter');
    
    let currentSort = 'artikel.id';
    let currentOrder = 'DESC';

    // Fungsi utama ambil data dari server
    function fetchData(url) {
        loadingBar.show();
        
        setTimeout(() => {
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    articleContainer.html(response);
                },
                error: function () {
                    articleContainer.html('<div class="alert alert-danger">Gagal memuat data</div>');
                },
                complete: function () {
                    loadingBar.hide();
                }
            });
        }, 2000); // Delay 2 detik supaya loading terlihat
    }

    // Saat form pencarian dikirim
    searchForm.on('submit', function (e) {
        e.preventDefault();
        const q = searchBox.val();
        const kategori_id = categoryFilter.val();

        const url = `/admin/artikel?q=${encodeURIComponent(q)}&kategori_id=${kategori_id}&sort_by=${currentSort}&order=${currentOrder}`;
        fetchData(url);
    });

    // Saat kategori diganti
    categoryFilter.on('change', function () {
        searchForm.trigger('submit');
    });

    // Saat pagination diklik
    $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();

    const q = searchBox.val();
    const kategori_id = categoryFilter.val();
    let baseUrl = $(this).attr('href');

    // Hilangkan q dan kategori jika sudah ada di URL agar tidak dobel
    baseUrl = baseUrl.replace(/(&|\?)q=[^&]*/g, '');
    baseUrl = baseUrl.replace(/(&|\?)kategori_id=[^&]*/g, '');
    baseUrl = baseUrl.replace(/(&|\?)sort_by=[^&]*/g, '');
    baseUrl = baseUrl.replace(/(&|\?)order=[^&]*/g, '');

    const separator = baseUrl.includes('?') ? '&' : '?';
    const url = `${baseUrl}${separator}q=${encodeURIComponent(q)}&kategori_id=${kategori_id}&sort_by=${currentSort}&order=${currentOrder}`;

    fetchData(url);
});


    // Saat header kolom diklik untuk sorting
    $(document).on('click', '.sort-link', function (e) {
        e.preventDefault();
        const sortBy = $(this).data('sort');
        currentOrder = (currentSort === sortBy && currentOrder === 'ASC') ? 'DESC' : 'ASC';
        currentSort = sortBy;
        searchForm.trigger('submit');
    });
    
    // Load awal saat halaman dibuka
    fetchData('/admin/artikel');
});
