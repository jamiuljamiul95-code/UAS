<?php
$activeMenu = 'blogs';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Artikel Baru</h2>
        <a href="<?= BASE_URL ?>/admin/blogs" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= BASE_URL ?>/admin/blogs/store" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Artikel</label>
                    <input type="text" name="title" class="form-control"
                        placeholder="Contoh: 5 Tips Desain Feed Instagram Aesthetic" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Thumbnail</label>
                    <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                    <small class="text-secondary">Opsional. JPG/PNG/WEBP. Kalau tidak diisi, akan pakai gambar
                        default.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konten Artikel</label>
                    <textarea name="content" class="form-control" rows="10" placeholder="Tulis isi artikel di sini..."
                        required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Artikel</button>
            </form>
        </div>
    </div>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>