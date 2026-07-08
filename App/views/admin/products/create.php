<?php
$activeMenu = 'products';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
    <h1>Tambah Produk</h1>
    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="admin-card">
    <?php if (!empty($error)): ?>
        <div class="alert-admin-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/products" method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label>Judul Produk</label>
                <input type="text" name="title" class="form-control"
                    placeholder="Contoh: Template Canva Feed Instagram Aesthetic" required>
            </div>
            <div class="col-md-4">
                <label>Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">— Pilih Kategori —</option>
                    <?php foreach ($categoriesGrouped as $parent): ?>
                        <?php if (!empty($parent['children'])): ?>
                            <optgroup label="── <?= htmlspecialchars($parent['name']) ?>">
                                <?php foreach ($parent['children'] as $child): ?>
                                    <option value="<?= $child['id'] ?>" <?= isset($product) && $product['category_id'] == $child['id'] ? 'selected' : '' ?>>
                                        ├ <?= htmlspecialchars($child['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php else: ?>
                            <option value="<?= $parent['id'] ?>" <?= isset($product) && $product['category_id'] == $parent['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($parent['name']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="4"
                placeholder="Jelaskan produk ini, format file, jumlah halaman/varian, dll."></textarea>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Harga (Rp)</label>
                <input type="number" name="price" class="form-control" placeholder="50000" min="0" required>
            </div>
            <div class="col-md-4">
                <label>Diskon (%)</label>
                <input type="number" name="discount" class="form-control" placeholder="0" min="0" max="100" value="0">
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="draft">Draft (belum tampil di toko)</option>
                    <option value="published">Published (langsung tampil)</option>
                </select>
            </div>
        </div>

        <!-- Galeri Foto & Video: bisa upload banyak sekaligus -->
        <div class="media-section">
            <div class="media-section-title">
                <i class="ti ti-photo-plus"></i>
                Galeri Foto & Video
            </div>

            <label class="file-drop">
                <input type="file" name="media_images[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                <i class="ti ti-photo file-drop-icon"></i>
                <div class="file-drop-label">Tambah Foto</div>
                <div class="file-drop-hint">Bisa pilih banyak sekaligus — JPG/PNG/WEBP, maks 3MB per file</div>
            </label>

            <label class="file-drop">
                <input type="file" name="media_videos[]" accept=".mp4,.webm,.mov" multiple>
                <i class="ti ti-video file-drop-icon"></i>
                <div class="file-drop-label">Tambah Video</div>
                <div class="file-drop-hint">Opsional — MP4/WEBM/MOV, maks 100MB per file</div>
            </label>
        </div>

        <!-- Thumbnail, Foto Detail, File Digital -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="single-file-card">
                    <label>Thumbnail</label>
                    <label class="file-drop">
                        <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" required>
                        <i class="ti ti-upload file-drop-icon"></i>
                        <div class="file-drop-label">Pilih Thumbnail</div>
                        <div class="file-drop-hint">Gambar utama di kartu produk. JPG/PNG/WEBP, maks 3MB.</div>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-file-card">
                    <label>Foto Detail</label>
                    <label class="file-drop">
                        <input type="file" name="preview_image" accept=".jpg,.jpeg,.png,.webp">
                        <i class="ti ti-upload file-drop-icon"></i>
                        <div class="file-drop-label">Pilih Foto Detail</div>
                        <div class="file-drop-hint">Gambar besar di halaman detail produk. Opsional.</div>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-file-card">
                    <label>File Digital</label>
                    <label class="file-drop">
                        <input type="file" name="file_path" accept=".zip,.rar,.pdf,.psd,.ai" required>
                        <i class="ti ti-file-upload file-drop-icon"></i>
                        <div class="file-drop-label">Pilih File Digital</div>
                        <div class="file-drop-hint">ZIP/RAR/PDF/PSD/AI, maks 200MB.</div>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-admin-primary">Simpan Produk</button>
    </form>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>