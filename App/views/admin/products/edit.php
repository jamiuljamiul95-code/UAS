<?php
/** @var array $product */
/** @var array $categoriesGrouped */
/** @var array $media */

$activeMenu = 'products';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
    <h1>Edit Produk</h1>
    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="admin-card">
    <?php if (!empty($error)): ?>
        <div class="alert-admin-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/products/update" method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label>Judul Produk</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($product['title']) ?>"
                    required>
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
            <textarea name="description" class="form-control"
                rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Harga (Rp)</label>
                <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" min="0"
                    required>
            </div>
            <div class="col-md-4">
                <label>Diskon (%)</label>
                <input type="number" name="discount" class="form-control" value="<?= $product['discount'] ?>" min="0"
                    max="100">
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $product['status'] === 'published' ? 'selected' : '' ?>>Published
                    </option>
                </select>
            </div>
        </div>

        <!-- Galeri Foto & Video: bisa upload banyak, hapus satu-satu -->
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

            <?php if (!empty($media)): ?>
                <p class="small fw-semibold mt-3 mb-0" style="font-size:12.5px;color:#6b7280">
                    Media yang sudah diupload — klik × untuk hapus:
                </p>
                <div class="media-preview-grid">
                    <?php foreach ($media as $m): ?>
                        <div class="media-preview-item">
                            <?php if ($m['type'] === 'image'): ?>
                                <img src="<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>" alt="">
                            <?php else: ?>
                                <video src="<?= BASE_URL ?>/assets/videos/products/<?= $m['file_path'] ?>" muted></video>
                                <span class="media-preview-type-badge">
                                    <i class="ti ti-player-play-filled"></i>
                                </span>
                            <?php endif; ?>
                            <button type="button" class="media-preview-remove"
                                onclick="deleteMedia(<?= $m['id'] ?>, <?= $product['id'] ?>, this)">×</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="small mt-3 mb-0" style="font-size:12.5px;color:#9ca3af">Belum ada foto/video di galeri.</p>
            <?php endif; ?>
        </div>

        <!-- Thumbnail, Foto Detail, File Digital -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="single-file-card">
                    <label>Thumbnail</label>
                    <?php if ($product['thumbnail']): ?>
                        <div class="existing-file-preview">
                            <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail']) ?>"
                                alt="">
                        </div>
                    <?php endif; ?>
                    <label class="file-drop">
                        <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp">
                        <i class="ti ti-upload file-drop-icon"></i>
                        <div class="file-drop-label">Ganti Thumbnail</div>
                        <div class="file-drop-hint">Gambar utama di kartu produk. Kosongkan jika tidak ingin
                            mengganti.</div>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-file-card">
                    <label>Foto Detail</label>
                    <?php if ($product['preview_image']): ?>
                        <div class="existing-file-preview">
                            <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image']) ?>"
                                alt="">
                        </div>
                    <?php endif; ?>
                    <label class="file-drop">
                        <input type="file" name="preview_image" accept=".jpg,.jpeg,.png,.webp">
                        <i class="ti ti-upload file-drop-icon"></i>
                        <div class="file-drop-label">Ganti Foto Detail</div>
                        <div class="file-drop-hint">Gambar besar di halaman detail produk. Kosongkan jika tidak ingin
                            mengganti.</div>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-file-card">
                    <label>File Digital</label>
                    <?php if ($product['file_path']): ?>
                        <div class="existing-file-badge">
                            <i class="ti ti-circle-check"></i> File sudah ada
                        </div>
                    <?php endif; ?>
                    <label class="file-drop">
                        <input type="file" name="file_path" accept=".zip,.rar,.pdf,.psd,.ai">
                        <i class="ti ti-file-upload file-drop-icon"></i>
                        <div class="file-drop-label">Ganti File Digital</div>
                        <div class="file-drop-hint">ZIP/RAR/PDF/PSD/AI, maks 200MB. Kosongkan jika tidak ingin
                            mengganti.</div>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-admin-primary">Update Produk</button>
    </form>
</div>


<script>
    function deleteMedia(mediaId, productId, btnEl) {
        if (!confirm('Hapus media ini?')) return;

        fetch('<?= BASE_URL ?>/admin/products/media/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'media_id=' + mediaId + '&product_id=' + productId
        }).then(() => {
            btnEl.closest('.media-preview-item').remove();
        });
    }
</script>


<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>