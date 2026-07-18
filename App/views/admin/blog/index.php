<?php
$activeMenu = 'blogs';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Artikel Blog</h2>
        <a href="<?= BASE_URL ?>/admin/blogs/create" class="btn btn-primary">+ Tambah Artikel</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="100">Gambar</th>
                        <th>Judul</th>
                        <th>Slug URL</th>
                        <th width="150">Tanggal Dibuat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada artikel yang dibuat.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $index => $post): ?>
                            <tr>
                                <td>
                                    <?= $index + 1; ?></td>
                                <td>
                                    <img src="<?= BASE_URL ?>/assets/images/blog/<?= $post['thumbnail']; ?>"
                                        class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                                </td>
                                <td><strong><?= htmlspecialchars($post['title']); ?></strong></td>
                                <td><span class="badge bg-secondary">/blog/detail?slug=<?= $post['slug']; ?></span></td>
                                <td><?= date('d M Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/blogs/edit?id=<?= $post['id']; ?>"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= BASE_URL ?>/admin/blogs/delete?id=<?= $post['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus artikel ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>