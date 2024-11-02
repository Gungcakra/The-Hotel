<?php


// Fungsi untuk mendapatkan nama direktori saat ini
function getCurrentDirectory()
{
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pathInfo = pathinfo($scriptName);
    return $pathInfo['dirname'];
}

$current_dir = getCurrentDirectory();

?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL_HTML ?>/thehotel/system/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Hotel <sup>Management</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($current_dir == BASE_URL_HTML . '/system') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= BASE_URL_HTML ?>/system/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">



    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item <?= ($current_dir == BASE_URL_HTML . '/system/extra' || $current_dir == BASE_URL_HTML . '/system/room' || $current_dir == BASE_URL_HTML . '/system/employee' || $current_dir == BASE_URL_HTML . '/system/guest' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/user' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/reservation' || $current_dir == BASE_URL_HTML . '/system/roomType') ? 'active' : '' ?>">

        <a class="nav-link" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="<?= ($current_dir == BASE_URL_HTML . '/system/extra' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/user' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/reservation' || $current_dir == BASE_URL_HTML . '/system/roomType' || $current_dir == BASE_URL_HTML . '/system/room' || $current_dir == BASE_URL_HTML . '/system/employee' || $current_dir == BASE_URL_HTML . '/system/guest') ? 'true' : 'false' ?>" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-database"></i>
            <span>DATA</span>
        </a>
        <div id="collapseTwo" class="collapse <?= ($current_dir == BASE_URL_HTML . '/system/extra' || $current_dir == BASE_URL_HTML . '/system/room' || $current_dir == BASE_URL_HTML . '/system/employee' || $current_dir == BASE_URL_HTML . '/system/guest' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/user' || $current_dir == BASE_URL_HTML . '/system/role' || $current_dir == BASE_URL_HTML . '/system/reservation' || $current_dir == BASE_URL_HTML . '/system/roomType') ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/room' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/room/">Room</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/roomType' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/roomType/">Room Type</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/extra' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/extra/">Extra</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/user' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/user/">User</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/employee' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/employee/">Employee</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/role' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/role/">Role</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/guest' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/guest/">Guest</a>
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/system/reservation' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/system/reservation/">Reservation</a>
            </div>
        </div>
    </li>




    <li class="nav-item <?= ($current_dir == BASE_URL_HTML . '/operational/reservation' ) ? 'active' : '' ?>">

        <a class="nav-link" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="<?= ($current_dir == BASE_URL_HTML . '/operational/reservation') ? 'true' : 'false' ?>" aria-controls="collapseThree">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>OPERATIONAL</span>
        </a>
        <div id="collapseThree" class="collapse <?= ($current_dir == BASE_URL_HTML . '/operational/reservation') ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item font-weight-bold text-white <?= $current_dir == BASE_URL_HTML . '/operational/reservation' ? 'bg-white text-dark' : '' ?>" href="<?= BASE_URL_HTML ?>/operational/reservation/">Reservation</a>

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