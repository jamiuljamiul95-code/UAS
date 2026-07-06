<?php
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
                        <option value="<?= $child['id'] ?>"
                            <?= isset($product) && $product['category_id'] == $child['id'] ? 'selected' : '' ?>>
                            ├ <?= htmlspecialchars($child['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <?php else: ?>
                    <option value="<?= $parent['id'] ?>"
                        <?= isset($product) && $product['category_id'] == $parent['id'] ? 'selected' : '' ?>>
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

        <div class="row g-3 mb-3">
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



        <!-- Upload Multiple Media -->
        <div class="mb-4">
            <label class="fw-semibold mb-2 d-block">
                <i class="ti ti-photo-plus" style="color:#2563EB"></i>
                Galeri Foto & Video
            </label>
            <div class="upload-box">
                <input type="file" name="media_images[]" accept=".jpg,.jpeg,.png,.webp" multiple
                    class="form-control mb-2">
                <small class="text-secondary d-block mb-2">Foto tambahan (bisa pilih banyak sekaligus) — JPG/PNG/WEBP,
                    maks 3MB per file</small>

                <input type="file" name="media_videos[]" accept=".mp4,.webm,.mov" multiple class="form-control">
                <small class="text-secondary">Video produk (opsional) — MP4/WEBM/MOV, maks 100MB per file</small>
            </div>

            <?php if (isset($product) && !empty($media)): ?>
            <div class="mt-3">
                <p class="small fw-semibold mb-2">Media yang sudah diupload:</p>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($media as $m): ?>
                    <div class="position-relative">
                        <?php if ($m['type'] === 'image'): ?>
                        <img src="<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>"
                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb">
                        <?php else: ?>
                        <video src="<?= BASE_URL ?>/assets/videos/products/<?= $m['file_path'] ?>"
                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb"></video>
                        <?php endif; ?>
                        <form action="<?= BASE_URL ?>/admin/products/media/delete" method="POST">
                            <input type="hidden" name="media_id" value="<?= $m['id'] ?>">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;
                             border-radius:50%;background:#dc2626;color:#fff;border:none;
                             font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center"
                                onclick="return confirm('Hapus media ini?')">×</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>




        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Thumbnail</label>
                <?php if ($product['thumbnail']): ?>
                <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail']) ?>"
                    class="thumb mb-2" style="width:60px;height:60px">
                <?php endif; ?>
                <div class="upload-box">
                    <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control form-control-sm">
                    <small>Kosongkan jika tidak ingin mengganti.</small>
                </div>
            </div>
            <div class="col-md-4">
                <label>Preview Image</label>
                <?php if ($product['preview_image']): ?>
                <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image']) ?>"
                    class="thumb mb-2" style="width:60px;height:60px">
                <?php endif; ?>
                <div class="upload-box">
                    <input type="file" name="preview_image" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control form-control-sm">
                    <small>Kosongkan jika tidak ingin mengganti.</small>
                </div>
            </div>
            <div class="col-md-4">
                <label>File Digital</label>
                <?php if ($product['file_path']): ?>
                <p class="small text-success mb-2">✓ File sudah ada</p>
                <?php endif; ?>
                <div class="upload-box">
                    <input type="file" name="file_path" accept=".zip,.rar,.pdf,.psd,.ai"
                        class="form-control form-control-sm">
                    <small>Kosongkan jika tidak ingin mengganti.</small>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-admin-primary">Update Produk</button>
    </form>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>