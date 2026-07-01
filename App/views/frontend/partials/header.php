<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Mizu Design' ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/navbar.css">
</head>
<body>

<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
  <!-- Banner kecil khusus kalau admin sedang berada di halaman customer (bukan area /admin) -->
  <div class="admin-notice-bar">
    <span>Kamu login sebagai <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong> (Admin)</span>
    <a href="<?= BASE_URL ?>/admin/dashboard">Buka Admin Panel →</a>
  </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
      <span style="color:#2563EB">Mizu</span><span style="color:#7C3AED">Design</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/shop">Shop</a></li>
        <li class="nav-item">
          <form action="<?= BASE_URL ?>/shop" method="GET" class="navbar-search-form d-flex">
            <input type="text" name="q" class="form-control form-control-sm rounded-pill" placeholder="Cari produk..." style="width:200px">
          </form>
        </li>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
          <!-- Admin: tampilkan link langsung ke Admin Panel, BUKAN dashboard customer -->
          <li class="nav-item"><a class="btn btn-sm btn-nav-user rounded-pill" href="<?= BASE_URL ?>/admin/dashboard">
            <?= htmlspecialchars($_SESSION['user_name']) ?> (Admin)
          </a></li>

        <?php elseif (isset($_SESSION['user_id'])): ?>
          <!-- Customer biasa yang sudah login -->
          <li class="nav-item"><a class="nav-icon-link" href="<?= BASE_URL ?>/wishlist"><i class="ti ti-heart"></i></a></li>
          <li class="nav-item"><a class="btn btn-sm btn-nav-user rounded-pill" href="<?= BASE_URL ?>/dashboard">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
          </a></li>

        <?php else: ?>
          <!-- Belum login -->
          <li class="nav-item"><a class="btn btn-sm btn-nav-outline rounded-pill" href="<?= BASE_URL ?>/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-nav-primary rounded-pill" href="<?= BASE_URL ?>/register">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>