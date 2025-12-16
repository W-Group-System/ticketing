<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME', "Laravel") }}</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    {{-- <link href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" /> --}}
    <!-- THEME STYLES-->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="{{ asset('assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('assets/vendors/summernote/dist/summernote.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/vendors/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/morris.js/morris.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <header class="header">
            <div class="page-brand">
                <a class="link" href="{{ url('') }}">
                    <span class="brand">Ticketing Portal
                    </span>
                    <span class="brand-mini">TP</span>
                </a>
            </div>
            <div class="flexbox flex-1">
                <!-- START TOP-LEFT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
                    </li>
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            <img src="{{ url('assets/img/admin-avatar.png') }}" />
                            <span></span>{{ auth()->user()->name }}<i class="fa fa-angle-down m-l-5"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            {{-- <a class="dropdown-item" href="profile.html"><i class="fa fa-user"></i>Profile</a> --}}
                            {{-- <a class="dropdown-item" href="profile.html"><i class="fa fa-cog"></i>Settings</a> --}}
                            {{-- <a class="dropdown-item" href="javascript:;"><i class="fa fa-support"></i>Support</a> --}}
                            {{-- <li class="dropdown-divider"></li> --}}
                            <a class="dropdown-item" onclick="logout();"><i class="fa fa-power-off"></i>Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </li>
                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
                <div class="admin-block d-flex">
                    <div>
                        <img src="{{ url('assets/img/admin-avatar.png') }}" width="45px" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong">{{ auth()->user()->name }}</div><small>{{ auth()->user()->role->name }}</small>
                    </div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a class="@if(Request::is('home')) active @endif" href="{{ url('') }}">
                            <i class="sidebar-item-icon fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="heading">MAIN MENU</li>
                    <li>
                        <a href="">
                            <i class="sidebar-item-icon fa fa-ticket"></i>
                                <span class="nav-label">Tickets</span><i class="fa fa-angle-left arrow">
                            </i>
                        </a>
                        <ul class="nav-2-level collapse">
                            <li>
                                <a href="{{ url('tickets') }}" class="@if(Request::is('tickets')) active @endif">Create ticket</a>
                            </li>
                            @can('viewAssign', App\Ticket::class)
                            <li>
                                <a href="{{ url('tickets/assign') }}">Assigned to me</a>
                            </li>
                            @endcan
                            @can('viewListTicket', App\Ticket::class)
                            <li>
                                <a href="{{ url('tickets/list') }}">List of tickets</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @if(auth()->user()->role->name != "User")
                    <li>
                        <a href="{{ url('reports') }}" class="@if(Request::is('reports')) active @endif">
                            <i class="sidebar-item-icon fa fa-file"></i>
                            <span class="nav-label">Reports</span>
                        </a>
                    </li>
                    @endif
                    @can('view', App\User::class)
                    <li class="heading">SETTINGS</li>
                    <li>
                        <a href="{{ url('users') }}" class="@if(Request::is('users')) active @endif">
                            <i class="sidebar-item-icon fa fa-user"></i>
                            <span class="nav-label">Users</span>
                        </a>
                    </li>
                    @endcan
                    @can('view', App\Company::class)
                    <li>
                        <a href="{{ url('companies') }}" class="@if(Request::is('companies')) active @endif">
                            <i class="sidebar-item-icon fa fa-building"></i>
                            <span class="nav-label">Companies</span>
                        </a>
                    </li>
                    @endcan
                    @can('view', App\Department::class)
                    <li>
                        <a href="{{ url('departments') }}" class="@if(Request::is('departments')) active @endif">
                            <i class="sidebar-item-icon fa fa-sitemap"></i>
                            <span class="nav-label">Departments</span>
                        </a>
                    </li>
                    @endcan
                    @can('view', App\Role::class)
                    <li>
                        <a href="{{ url('roles') }}" class="@if(Request::is('roles')) active @endif">
                            <i class="sidebar-item-icon fa fa-users"></i>
                            <span class="nav-label">Roles</span>
                        </a>
                    </li>
                    @endcan
                    @if((auth()->user()->can('view', App\TicketingComment::class)) || (auth()->user()->can('view', App\TicketingComment::class)))
                    <li>
                        <a href="">
                            <i class="sidebar-item-icon fa fa-cog"></i>
                                <span class="nav-label">Ticketing Settings</span><i class="fa fa-angle-left arrow">
                            </i>
                        </a>
                        <ul class="nav-2-level collapse">
                            @can('view', App\TicketingComment::class)
                            <li>
                                <a href="{{ url('ticketing_comments') }}" class="@if(Request::is('ticketing_comments')) active @endif">Ticketing Comments</a>
                            </li>
                            @endcan
                            @can('view', App\TicketingType::class)
                            <li>
                                <a href="{{ url('ticketing_types') }}" class="@if(Request::is('ticketing_types')) active @endif">Ticketing Types</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-content fade-in-up">
                @yield('content')
            </div>
            <!-- END PAGE CONTENT-->
            <footer class="page-footer">
                <div class="font-13">{{ date('Y') }} © <b>WGROUP DEVELOPERS</b> - All rights reserved.</div>
                {{-- <div class="to-top"><i class="fa fa-angle-double-up"></i></div> --}}
            </footer>
        </div>
    </div>

    <!-- BEGIN PAGA BACKDROPS-->
    {{-- <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div> --}}
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script> --}}
    <!-- PAGE LEVEL PLUGINS-->
    {{-- <script src="./assets/vendors/chart.js/dist/Chart.min.js" type="text/javascript"></script> --}}
    {{-- <script src="./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js" type="text/javascript"></script> --}}
    {{-- <script src="./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script> --}}
    {{-- <script src="./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js" type="text/javascript"></script> --}}
    <!-- CORE SCRIPTS-->
    <script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/DataTables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/vendors/summernote/dist/summernote.min.js') }}" type="text/javascript"></script> --}}
    <script src="{{ asset('assets/vendors/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/morris.js/morris.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/raphael/raphael.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    {{-- <script src="./assets/js/scripts/dashboard_1_demo.js" type="text/javascript"></script> --}}
    <script>
        function logout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    </script>
    @yield('js')
</body>

</html>