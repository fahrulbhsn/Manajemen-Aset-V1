<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manajemen Aset</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
         @stack('styles')
        <style>
            .sidebar-brand-icon img {
            border-radius: 50%;
            object-fit: cover;}
        </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            {{-- Isi sidebar --}}
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                    <div class="sidebar-brand-icon">
                        <img src="{{ asset('img/logo.png') }}" alt="Gigih Com" width="40" height="40">
                    </div>
                    <div class="sidebar-brand-text mx-3">Gigih Com</div>
                </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Utama</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAset" aria-expanded="true" aria-controls="collapseAset">
                    <i class="fas fa-fw fa-archive"></i><span>Aset</span>
                </a>
                <div id="collapseAset" class="collapse" aria-labelledby="headingAset" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Aset:</h6>
                        <a class="collapse-item" href="{{ route('aset.index') }}">Daftar Aset</a>
                        <a class="collapse-item" href="{{ route('kategori.index') }}">Kategori</a>
                        <a class="collapse-item" href="{{ route('status.index') }}">Status</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi" aria-expanded="true" aria-controls="collapseTransaksi">
                    <i class="fas fa-fw fa-dollar-sign"></i><span>Transaksi</span>
                </a>
                <div id="collapseTransaksi" class="collapse" aria-labelledby="headingTransaksi" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Transaksi:</h6>
                        <a class="collapse-item" href="{{ route('transaksi.index') }}">Daftar Transaksi</a>
                        @if(Auth::user()->role == 'admin')
                        <a class="collapse-item" href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a>
                        <a class="collapse-item" href="{{ route('laporan.pembelian') }}">Laporan Pembelian</a>
                        <a class="collapse-item" href="{{ route('laporan.laba_rugi') }}">Laporan Laba Rugi</a>
                        @endif
                    </div>
                </div>
            </li>
            @if(Auth::user()->role == 'admin')
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Admin</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('approval.index') }}">
                    <i class="fas fa-fw fa-check-double"></i>
                        <span>Pusat Persetujuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-fw fa-users"></i><span>Manajemen User</span></a>
            </li>
            @endif
            
            @if(Auth::user()->role == 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('aktivitas.index') }}">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Aktivitas Pengguna</span>
                </a>
            </li>
            @endif
            
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                @if(Auth::user()->role == 'admin')
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah ini jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Anda yakin ingin menghapus?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Hapus" di bawah ini jika Anda yakin untuk menghapus data ini secara permanen.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    {{-- Form diisi oleh JavaScript --}}
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @stack('scripts')
    <script>
        // Local Storage untuk Sidebar Toggle
        (function() {
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');
            const storageKey = 'sb|sidebar-toggle';

            document.addEventListener('DOMContentLoaded', function() {
                if (localStorage.getItem(storageKey) === 'true') {
                    if (sidebar) {
                        body.classList.add('sidebar-toggled');
                        sidebar.classList.add('toggled');
                    }
                }
            });

            const sidebarTogglers = document.querySelectorAll('#sidebarToggle, #sidebarToggleTop');

            const toggleListener = function() {
                setTimeout(function() {
                    const isToggled = body.classList.contains('sidebar-toggled');
                    localStorage.setItem(storageKey, isToggled);
                }, 10);
            };

            sidebarTogglers.forEach(function(toggler) {
                if (toggler) {
                    toggler.addEventListener('click', toggleListener);
                }
            });
        })();

        $(document).ready(function() {
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                var modal = $(this);
                modal.find('form#deleteForm').attr('action', url);
            });
        });
    </script>
</body>
</html>