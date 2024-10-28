<?php


// Fungsi untuk mendapatkan nama direktori saat ini
function getCurrentDirectory() {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pathInfo = pathinfo($scriptName);
    return $pathInfo['dirname'];
}

$current_dir = getCurrentDirectory();

?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL_HTML ?>/index.html">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item <?= ($current_dir == '/' || $current_dir == '/thehotel') ? 'active' : '' ?>">
    <a class="nav-link" href="<?= BASE_URL_HTML ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">



<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item <?= ($current_dir == '/thehotel/guest' || $current_dir == '/thehotel/room') ? 'active' : '' ?>">
    <a class="nav-link <?= ($current_dir == '/thehotel/guest' || $current_dir == '/thehotel/room') ? '' : 'collapsed' ?>" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="<?= ($current_dir == '/thehotel/guest' || $current_dir == '/thehotel/room') ? 'true' : 'false' ?>" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-database"></i>
        <span>DATA</span>
    </a>
    <div id="collapseTwo" class="collapse <?= ($current_dir == '/thehotel/guest' || $current_dir == '/thehotel/room') ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= BASE_URL_HTML ?>/room/">Room</a>
            <a class="collapse-item" href="<?= BASE_URL_HTML ?>/guest/">Guest</a>
        </div>
    </div>
</li>



<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>



</ul>