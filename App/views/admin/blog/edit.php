<?php
$activeMenu = 'blogs';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Artikel</h2>
        <a href="<?= BASE_URL ?>/admin/blogs" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= BASE_URL ?>/admin/blogs/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $post['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Artikel</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>"
                        required>
                </div>

                <div class=" mb-3">
                    <label class="form-label fw-semibold">Thumbnail Saat Ini</label>
                    <div class="mb-2">
                        <img src="<?= BASE_URL ?>/assets/images/blog/<?= htmlspecialchars($post['thumbnail']) ?>"
                            style="width:120px;height:75px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb"
                            onerror="this.src='<?= BASE_URL ?>/assets/images/blog/default-blog.jpg'">
                    </div>
                    <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                    <small class="text-secondary">Kosongkan jika tidak ingin mengganti thumbnail.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konten Artikel</label>
                    <textarea name="content" class="form-control" rows="10"
                        required><?= htmlspecialchars($post['content']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Artikel</button>
            </form>
        </div>
    </div>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>