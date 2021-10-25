<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('system.index') }}" class="nav-link">Home</a>
            </li>
        </ul>
    
        <!-- SEARCH FORM -->
        <div class="form-inline ml-auto">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append" style="border-top: 1px solid #ced4da;border-right: 1px solid #ced4da;border-bottom: 1px solid #ced4da;border-top-right-radius: 0.25rem;border-bottom-right-radius: 0.25rem;">
                    <button class="btn btn-sidebar" style="margin: 0;padding-top: unset;padding-bottom: unset;">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- /.navbar -->