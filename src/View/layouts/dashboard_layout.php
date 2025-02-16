<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title><?= $this->e($title) ?></title>

    <link rel="stylesheet" href="/resources/css/template-style.css">
    <link rel="stylesheet" href="/resources/css/bootstrap/bootstrap.min.css">
    <script src="/resources/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/resources/js/jquery/jquery.min.js"></script>
    <script src="/resources/fontawesome/js/all.js"></script>
    <?= $this->section('styles') ?>
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a href="/private" class="navbar-brand ps-3"><img src="/favicon.ico" width="30" height="30" class="d-inline-block align-top mx-2">Gestione museo</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item text-center" href="/">
                            Home
                        </a></li>
                        <li><a class="dropdown-item text-center" href="/private/profile">
                            Profilo
                        </a></li>
                    <li><a class="dropdown-item text-center" href="/catalog">
                            Catalogo
                        </a></li>
                    <li><a class="dropdown-item text-center" href="#!">
                            <form action='/private' method='GET'>
                                <input type='submit' class="dropdown-item" name='logout-btn' value='Logout'>
                            </form>
                        </a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php
                        if ($this->e($user->privilege) === "1") {
                        ?>
                            <div class="sb-sidenav-menu-heading">Gestione utenti</div>
                            <div class="nav-link" role="button" id="view_users">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Visualizza utenti
                            </div>
                            <div class="nav-link" role="button" id="add_user">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Aggiungi utente
                            </div>
                        <?php } ?>
                        <div class="sb-sidenav-menu-heading">Gestione reperti</div>
                        <div class="nav-link" role="button" id="view_artifacts">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Visualizza reperti
                        </div>
                        <div class="nav-link" role="button" id="add_artifact">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Aggiungi reperto
                        </div>
                        <div class="sb-sidenav-menu-heading">Gestione componenti</div>
                        <div class="nav-link" role="button" id="view_components">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Visualizza componenti
                        </div>
                        <div class="nav-link" role="button" id="add_component">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Aggiungi componente
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid p-4">
                    <?= $this->section('content') ?>
                </div>
            </main>
        </div>
    </div>
    <script src="/resources/js/util.js"></script>
    <script src="/api/scripts?filename=home.js"></script>
    <script src="/api/scripts?filename=menu_toggle.js"></script>
    <?= $this->section('scripts') ?>
</body>

</html>