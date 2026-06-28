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
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">
      <span style="color:#2563EB">Mizu</span><span style="color:#7C3AED">Design</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/shop">Shop</a></li>
        <li class="nav-item">
          <form action="/shop" method="GET" class="d-flex">
            <input type="text" name="q" class="form-control form-control-sm rounded-pill" placeholder="Cari produk..." style="width:200px">
          </form>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="/wishlist"><i class="ti ti-heart"></i></a></li>
          <li class="nav-item"><a class="btn btn-sm btn-primary rounded-pill px-3" href="/dashboard"><?= htmlspecialchars($_SESSION['user_name']) ?></a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-sm btn-outline-primary rounded-pill px-3" href="/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-primary rounded-pill px-3" href="/register">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>