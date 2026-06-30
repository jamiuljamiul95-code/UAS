<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Admin' ?> — Mizu Design</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>

<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <div class="brand">
      <span style="color:#fff">Mizu</span><span style="color:#7C3AED">Admin</span>
    </div>
    <nav>
      <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
      <a href="<?= BASE_URL ?>/admin/products" class="<?= ($activeMenu ?? '') === 'products' ? 'active' : '' ?>">Produk</a>
      <a href="<?= BASE_URL ?>/admin/categories" class="<?= ($activeMenu ?? '') === 'categories' ? 'active' : '' ?>">Kategori</a>
      <a href="<?= BASE_URL ?>/admin/orders" class="<?= ($activeMenu ?? '') === 'orders' ? 'active' : '' ?>">Order</a>
      <a href="<?= BASE_URL ?>/admin/users" class="<?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?>">User</a>
      <a href="<?= BASE_URL ?>/logout" style="margin-top:20px;border-top:1px solid rgba(255,255,255,0.08);padding-top:18px">Logout</a>
    </nav>
  </aside>

  <main class="admin-content">
