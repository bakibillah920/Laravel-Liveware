<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Top10Torrents') }}</title>

    <!-- Meta Description -->
    <meta name="description" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/core/favicon.png') }}">

    <!-- ========================= Google Material Icon CDN ========================= -->

    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- ========================= Font CDN ========================= -->

    <!-- Nunito -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Roboto -->
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

    <!-- ========================= Theme CSS ========================= -->

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('app/css/plugins.css') }}">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('app/css/style.css') }}">

    <!-- ========================= Plugin CSS ========================= -->

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">



    <!-- ========================= Plugin JS ========================= -->





    <!-- ========================= Custom CSS ========================= -->

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('custom/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

    <!-- Global site tag (gtag.js) - Google Analytics - Animeinterval@gmail.com -->
    {{--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-179478421-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-179478421-1');
    </script>--}}

</head>
<body>
    <div id="app">
        <!--header area start-->
        <header class="header_area">
            <!--header top start-->
            <div class="header_top">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <div class="welcome_text">
                                <p>
                                    Welcome to <span>Top 10 Torrent</span>
                                    @auth()
                                        <strong style="color:{{ Auth::user()->roles[0]->color }}">{{ Auth::user()->username }} <span style="color:{{ Auth::user()->roles[0]->color }}">[{{ Auth::user()->roles[0]->name }}]</span></strong>
                                    @endauth
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="top_right text-right">
                                <ul>
                                    <li class="top_links">
                                        <a href="{{ route('/') }}"><i class="zmdi zmdi-home"></i> Home</a>
                                    </li>
                                    <li class="top_links">
                                        @can('manage-upcoming-list')
                                            <a href="{{ route('upcoming.index') }}"><i class="zmdi zmdi-camera-alt"></i> Upcoming Exclusives</a>
                                        @endcan
                                    </li>
                                    @auth()
                                        <li class="top_links">
                                            <a href="JavaScript:void(0)">
                                                <i class="zmdi zmdi-cloud-upload"></i> Upload  <i class="zmdi zmdi-caret-down"></i>
                                            </a>
                                            <ul class="dropdown_links">
                                                @can('manage-upload-list')
                                                    <li><a href="{{ route('uploads.index') }}">All Uploads</a></li>
                                                @endcan
                                                @can('manage-myupload-list')
                                                    <li><a href="{{ route('my-uploads.index') }}">My Uploads</a></li>
                                                @endcan
                                                @can('manage-myupload-create')
                                                    <li><a href="{{ route('my-uploads.create') }}">New Upload</a></li>
                                                @endcan
                                                    <li><a href="https://imgbb.com/" target="_blank">Upload Image</a></li>
                                                    <li><a href="https://top10torrent.site/rules" target="_blank">Upload Rules</a></li>
                                                {{--@can('manage-pin-list')
                                                    <li><a href="{{ route('pins.index') }}">Pin</a></li>
                                                @endcan
                                                @can('manage-recommend-list')
                                                    <li><a href="{{ route('recommends.index') }}">Recommend</a></li>
                                                @endcan--}}
                                            </ul>
                                        </li>
                                    @endauth
                                    @can('manage-helpdesk-list')
                                        <li class="top_links helpWrapper">
                                            <a href="{{ route('help.index') }}" class="help">
                                                <i class="zmdi zmdi-help"></i> Help
                                                @can('manage-helpdesk-give-answer')
                                                    <?php

                                                        /*$hasAnswer = \Illuminate\Support\Facades\Auth::user()->help_requester()->get();
                                                        if (count($hasAnswer) > 0) {
                                                            echo'<span class="badge badge-info">'.count($hasAnswer).'</span>';
                                                        }*/
                                                        $unread_answers = \App\Models\Help::where('asked_by', '=', Auth::user()->id)->where('read_at', '=', null)->where('is_answered', '=', 1)->get();
                                                        if (count($unread_answers) > 0) {
                                                            echo'<span class="badge badge-info">'.count($unread_answers).'</span>';
                                                        }

                                                        $unanswered_questions = \App\Models\Help::where('is_answered', '=', null)->count();
                                                        if ($unanswered_questions > 0) {
                                                            echo'<span class="badge badge-danger">'.$unanswered_questions.'</span>';
                                                        }

                                                    ?>
                                                @else
                                                    <?php

                                                        $unread_answers = \App\Models\Help::where('asked_by', '=', Auth::user()->id)->where('read_at', '=', null)->where('is_answered', '=', 1)->get();
                                                        if (count($unread_answers) > 0) {
                                                            echo'<span class="badge badge-info">'.count($unread_answers).'</span>';
                                                        }

                                                    ?>
                                                @endcan
                                            </a>
                                        </li>
                                    @endcan
                                    @auth()
                                        <li class="top_links mailBoxWrapper">
                                            <a href="{{ route('mail-box.index') }}" class="mailBox">
                                                <i class="zmdi zmdi-email"></i> Mail Box
                                                <?php
                                                $hasMail = \Illuminate\Support\Facades\Auth::user()->pms()->where('read_at', '=', null)->get();
                                                if (count($hasMail) > 0) {
                                                    echo'<span class="badge badge-info">'.count($hasMail).'</span>';
                                                }

                                                ?>

                                            </a>
                                        </li>
                                    @endauth
                                    <li class="top_links">
                                        <a href="JavaScript:void(0)">
                                            @guest
                                                <i class="zmdi zmdi-account"></i> My account <i class="zmdi zmdi-caret-down"></i>
                                            @else
                                            <img src="{{ asset('images/avatars/'.Auth::user()->avatar) }}" width="20px" height="20px" alt=""> {{ Str::limit(Auth::user()->username, 15) }} <i class="zmdi zmdi-caret-down"></i>
                                            @endguest
                                        </a>
                                        <ul class="dropdown_links">
                                            @guest
                                                <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                                                @if (Route::has('register'))
                                                    <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                                                @endif
                                            @else
                                                <li><a href="{{ route('profile.show',Auth::user()->username) }}">My Account </a></li>
                                                <li><a href="{{ route('editPassword') }}">Update Password</a></li>
                                                <li>
                                                    <a href="{{ route('logout') }}"
                                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                        @csrf
                                                    </form>
                                                </li>
                                                @can('delete-app')
                                                    <li>
                                                        <a href="{{ route('deleteApp') }}"
                                                           onclick="event.preventDefault();
                                                           document.getElementById('deleteApp').submit();">
                                                            {{ __('Delete App') }}
                                                        </a>
                                                        <form id="deleteApp" action="{{ route('deleteApp') }}" method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                @endcan
                                            @endguest
                                        </ul>
                                    </li>
                                    @auth()
{{--                                        @if(Auth::user()->role == 'Dev')--}}
                                        @can('view-settings')
                                            <li class="top_links">
                                                <a href="JavaScript:void(0)">
                                                    <i class="zmdi zmdi-settings"></i>
                                                </a>
                                                <ul class="dropdown_links">

                                                    @can('manage-permission-list')<li><a class="text-info" href={{ route('imdb-detail-insert') }}>imdb-detail-insert </a></li>@endcan

                                                    @can('manage-category-list')<li><a href={{ route('categories.index') }}>Categories </a></li>@endcan
                                                    @can('manage-user-list')<li><a class="text-info" href={{ route('users.index') }}>Users </a></li>@endcan
                                                    @can('manage-role-list')<li><a class="text-info" href={{ route('roles.index') }}>Roles </a></li>@endcan
                                                    @can('manage-permission-list')<li><a class="text-info" href={{ route('permissions.index') }}>Permissions </a></li>@endcan
                                                    @can('manage-user-role')<li><a class="text-info" href={{ route('assign-user-role') }}>User Roles </a></li>@endcan
                                                    @can('manage-role-permission')<li><a class="text-info" href={{ route('assign-role-permission') }}>Role Permissions </a></li>@endcan
                                                    @can('manage-user-permission')<li><a class="text-info" href={{ route('assign-user-permission') }}>User Permissions </a></li>@endcan
                                                </ul>
                                            </li>
{{--                                        @endif--}}
                                        @endcan
                                    @endauth
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--header top start-->
            <!--header center area start-->
            <div class="header_middle">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="logo">
                                <a href="{{ route('/') }}"><img src="{{ asset('images/core/logo.png') }}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="header_middle_inner">
                                <div class="search-container w-100">
                                    <form action="{{ route('landing.search.index') }}" method="post">
                                        @csrf
                                        <div class="search_box  w-100">
                                            @livewire('search')
                                        </div>
                                    </form>
                                    <small class="pl-4"><span class="text-danger">Note: </span>Please type at least 3 characters to see the result</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--header center area end-->

            <!--header middel start-->
            <div class="header_bottom sticky-header boxed-nav bg-dark-silver">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="main_menu header_position">
                                <nav>
                                    <ul>
                                        @foreach(\App\Models\Category::where(['parent_id' => null, 'status' => 'Public'])->orderBy('serial', 'asc')->get() as $category)
                                            @can('navcategory-'.$category->slug)
                                                <li>
                                                    <a href="{{ route('landing.categories.categories', $category->slug) }}" class="nav-svg">

                                                        @if($category->icon)
                                                            @if(pathinfo(public_path('images/categories/'.$category->icon), PATHINFO_EXTENSION) === 'svg')
                                                                {!! file_get_contents(public_path('images/categories/'.$category->icon)) !!}
                                                            @else
                                                                <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/categories/'.$category->icon) }}" alt="{{ $category->name }}">
                                                            @endif
                                                        @endif

                                                        {{ $category->name }} {!! count($category->childCategories) > 0 ? '<i class="zmdi zmdi-caret-down"></i>':'' !!}
                                                    </a>
                                                    @if(count($category->childCategories) > 0)
                                                        <ul class="sub_menu shadow">
                                                            @foreach($category->childCategories as $childCategory)
                                                                @can('navcategory-'.$childCategory->slug)
                                                                    <li><a href="{{ route('landing.categories.index',[$category->slug, $childCategory->slug]) }}" >{{ $childCategory->name }}</a></li>
                                                                @endcan
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endcan
                                        @endforeach
                                        @guest()
{{--                                            @include('globals.modal.login_to_see_content_notice')--}}
                                            <div class="text-center text-white p-3">
                                                <h3>Please <a href="{{ route('login') }}" style="color: red;"><u>Login</u></a> To See The Categories</h3>
                                            </div>
                                        @endauth
                                        @auth()
                                            <li class="float-right">
                                                <a href="JavaScript:void(0)">
                                                    <img src="{{ asset('images/core/request.jpg') }}" style="width: 25px; height:25px;"> Request <i class="zmdi zmdi-caret-down"></i>
                                                </a>
                                                <ul class="sub_menu shadow">
                                                    <li><a href="{{ route('requests.index') }}">Request List</a></li>
                                                    <li><a href="{{ route('requests.create') }}">Make Request</a></li>
                                                </ul>
                                            </li>
                                        @endauth
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--header middel end-->

            @include('globals.modal.rank_update_notice')

        </header>
        <!--header area end-->

        <!--Offcanvas menu area start-->

        <div class="off_canvars_overlay">

        </div>
        <div class="Offcanvas_menu mb-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="canvas_open">
                            <span>MENU</span>
                            <a href="javascript:void(0)"><i class="ion-navicon"></i></a>
                        </div>

                        <div class="search-container mt-3 mb-0" style="display: block;">
                            <form action="{{ route('landing.search.index') }}" method="post">
                                @csrf
                                <div class="search_box">
                                    @livewire('offcanvas-search')
                                </div>
                            </form>
                            <small class="p-0 d-flex justify-content-center"><span class="text-danger">Note: </span>Please type at least 3 characters to see the result</small>
                        </div>

                        <div class="Offcanvas_menu_wrapper">
                            <div class="canvas_close">
                                <a href="javascript:void(0)"><i class="ion-android-close"></i></a>
                            </div>
                            <div class="welcome_text">
                                <p>Welcome to <span>Top 10 Torrent</span> </p>
                            </div>

                            <div class="top_right">
                                <ul>
                                    <li class="top_links">
                                        <a href="{{ route('/') }}"><i class="zmdi zmdi-home"></i> Home</a>
                                    </li>
                                    <li class="top_links">
                                        @can('manage-upcoming-list')
                                            <a href="{{ route('upcoming.index') }}"><i class="zmdi zmdi-camera-alt"></i> Upcoming Exclusives</a>
                                        @endcan
                                    </li>
                                    @auth()
                                        <li class="top_links">
                                            <a href="JavaScript:void(0)">
                                                <i class="zmdi zmdi-cloud-upload"></i> Upload  <i class="zmdi zmdi-caret-down"></i>
                                            </a>
                                            <ul class="dropdown_links" style="left: -24px !important;">
                                                @can('manage-upload-list')
                                                    <li><a href="{{ route('uploads.index') }}">All Uploads</a></li>
                                                @endcan
                                                @can('manage-myupload-list')
                                                    <li><a href="{{ route('my-uploads.index') }}">My Uploads</a></li>
                                                @endcan
                                                @can('manage-myupload-create')
                                                    <li><a href="{{ route('my-uploads.create') }}">New Upload</a></li>
                                                @endcan
                                                    <li><a href="https://imgbb.com/" target="_blank">Upload Image</a></li>
                                                    <li><a href="https://top10torrent.site/rules" target="_blank">Upload Rules</a></li>
                                            </ul>
                                        </li>
                                    @endauth
                                    @can('manage-helpdesk-list')
                                        <li class="top_links">
                                            <a href="{{ route('help.index') }}">
                                                <i class="zmdi zmdi-help"></i> Help
                                                @can('answer-help')
                                                    //
                                                @else
                                                    <?php
                                                    if (Auth::user())
                                                    {
                                                        $unread_answers = \App\Models\Help::where('asked_by', Auth::user())->where('read_at', null)->where('is_answered', 1)->count();
                                                        $unanswered_questions = \App\Models\Help::where('is_answered', '!=', 1)->count();
                                                        if ($unread_answers > 0) {
                                                            echo'<span class="badge badge-info">'.$unread_answers.'</span>';
                                                        }
                                                        if ($unanswered_questions > 0) {
                                                            echo'<span class="badge badge-danger">'.$unanswered_questions.'</span>';
                                                        }
                                                    }

                                                    ?>
                                                @endcan
                                            </a>
                                        </li>
                                    @endcan
                                    @auth()
                                        <li class="top_links">
                                            <a href="{{ route('mail-box.index') }}">
                                                <i class="zmdi zmdi-email"></i> Mail Box
                                            </a>
                                        </li>
                                    @endauth
                                    <li class="top_links">
                                        <a href="JavaScript:void(0)">
                                            <i class="zmdi zmdi-account"></i> My account <i class="zmdi zmdi-caret-down"></i>
                                        </a>
                                        <ul class="dropdown_links" style="left: -24px !important;">
                                            @guest
                                                <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                                                @if (Route::has('register'))
                                                    <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                                                @endif
                                            @else
                                                <li><a href="{{ route('profile.show',Auth::user()->username) }}">My Account </a></li>
                                                <li><a href="{{ route('editPassword') }}">Update Password</a></li>
                                                <li>
                                                    <a href="{{ route('logout') }}"
                                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                        @csrf
                                                    </form>
                                                </li>
                                                @can('delete-app')
                                                    <li>
                                                        <a href="{{ route('deleteApp') }}"
                                                           onclick="event.preventDefault();
                                                           document.getElementById('deleteApp').submit();">
                                                            {{ __('Delete App') }}
                                                        </a>
                                                        <form id="deleteApp" action="{{ route('deleteApp') }}" method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                @endcan
                                            @endguest
                                        </ul>
                                    </li>
                                    @auth()
                                        {{--                                        @if(Auth::user()->role == 'Dev')--}}
                                        @can('view-settings')
                                            <li class="top_links">
                                                <a href="JavaScript:void(0)">
                                                    <i class="zmdi zmdi-settings"></i>
                                                </a>
                                                <ul class="dropdown_links" style="left: -24px !important;">
                                                    @can('manage-category-list')<li><a href={{ route('categories.index') }}>Categories </a></li>@endcan
                                                    @can('manage-user-list')<li><a class="text-info" href={{ route('users.index') }}>Users </a></li>@endcan
                                                    @can('manage-role-list')<li><a class="text-info" href={{ route('roles.index') }}>Roles </a></li>@endcan
                                                    @can('manage-permission-list')<li><a class="text-info" href={{ route('permissions.index') }}>Permissions </a></li>@endcan
                                                    @can('manage-user-role')<li><a class="text-info" href={{ route('assign-user-role') }}>User Roles </a></li>@endcan
                                                    @can('manage-role-permission')<li><a class="text-info" href={{ route('assign-role-permission') }}>Role Permissions </a></li>@endcan
                                                    @can('manage-user-permission')<li><a class="text-info" href={{ route('assign-user-permission') }}>User Permissions </a></li>@endcan
                                                </ul>
                                            </li>
                                            {{--                                        @endif--}}
                                        @endcan
                                    @endauth
                                </ul>
                            </div>
                            {{--<div class="search-container">
                                <form action="#">
                                    <div class="search_box">
                                        @livewire('offcanvas-search')
                                    </div>
                                </form>
                                <small class="p-0"><span class="text-danger">Note: </span>Please type at least 3 characters to see the result</small>
                            </div>--}}

                            <div id="menu" class="text-left ">
                                <ul class="offcanvas_main_menu">
                                    @foreach(\App\Models\Category::where(['parent_id' => null, 'status' => 'Public'])->orderBy('serial', 'asc')->get() as $category)
                                        @can('navcategory-'.$category->slug)

                                            <li class="menu-item-has-children active">
                                                <a  href="{{ route('landing.categories.categories', $category->slug) }}" class="nav-svg">

                                                    @if($category->icon)
                                                        @if(pathinfo(public_path('images/categories/'.$category->icon), PATHINFO_EXTENSION) === 'svg')
                                                            {!! file_get_contents(public_path('images/categories/'.$category->icon)) !!}
                                                        @else
                                                            <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/categories/'.$category->icon) }}" alt="{{ $category->name }}">
                                                        @endif
                                                    @endif

                                                    {{ $category->name }}
                                                </a>
                                                @if(count($category->childCategories) > 0)
                                                    <ul class="sub-menu" style="left: -24px !important;">
                                                        @foreach($category->childCategories as $childCategory)
                                                            @can('navcategory-'.$childCategory->slug)
                                                                <li><a href="{{ route('landing.categories.index',[$category->slug, $childCategory->slug]) }}">{{ $childCategory->name }}</a></li>
                                                            @endcan
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endcan
                                    @endforeach

                                        @auth()
                                            <li class="menu-item-has-children">
                                                <a href="JavaScript:void(0)" class="nav-svg">
                                                    <img src="{{ asset('images/core/request.jpg') }}" style="width: 25px; height:25px;"> Request
                                                </a>
                                                <ul class="sub-menu" style="left: -24px !important;">
                                                    <li><a href="{{ route('requests.index') }}">Request List</a></li>
                                                    <li><a href="{{ route('requests.create') }}">Make Request</a></li>
                                                </ul>
                                            </li>
                                        @endauth

                                    @guest()
                                        <li class="d-flex justify-content-center">
                                            <a href="{{ route('login') }}" class="nav-svg">
                                                Please <span class="text-danger pl-1 pr-2"> Login </span> to view all the Categories
                                            </a>
                                        </li>
                                    @endauth
                                    {{--<li class="menu-item-has-children active">
                                        <a href="JavaScript:void(0)">Movies</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">English</a></li>
                                            <li><a href="JavaScript:void(0)">Hindi</a></li>
                                            <li><a href="JavaScript:void(0)">Hindi Dubbed</a></li>
                                            <li><a href="JavaScript:void(0)">Tamil</a></li>
                                            <li><a href="JavaScript:void(0)">Animated Movie</a></li>
                                            <li><a href="JavaScript:void(0)">Bangla</a></li>
                                            <li><a href="JavaScript:void(0)">4K/Remix</a></li>
                                            <li><a href="JavaScript:void(0)">Other Foraign</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">TV Series</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">English</a></li>
                                            <li><a href="JavaScript:void(0)">Hindi</a></li>
                                            <li><a href="JavaScript:void(0)">Hindi Dubbed</a></li>
                                            <li><a href="JavaScript:void(0)">Bangla</a></li>
                                            <li><a href="JavaScript:void(0)">Other Foraign</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">Games</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">PC/Laptop</a></li>
                                            <li><a href="JavaScript:void(0)">PS3/PS4</a></li>
                                            <li><a href="JavaScript:void(0)">Android</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">Software</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">Windows</a></li>
                                            <li><a href="JavaScript:void(0)">Linux</a></li>
                                            <li><a href="JavaScript:void(0)">Android</a></li>
                                            <li><a href="JavaScript:void(0)">Tutorials</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">Anime</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">Cartoon</a></li>
                                            <li><a href="JavaScript:void(0)">Subbed</a></li>
                                            <li><a href="JavaScript:void(0)">Dubbed</a></li>
                                            <li><a href="JavaScript:void(0)">Dual Audio</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">Music</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">Audio</a></li>
                                            <li><a href="JavaScript:void(0)">Video</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">ETC...</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">Books</a></li>
                                            <li><a href="JavaScript:void(0)">Magazine</a></li>
                                            <li><a href="JavaScript:void(0)">Comics</a></li>
                                            <li><a href="JavaScript:void(0)">Islamic</a></li>
                                            <li><a href="JavaScript:void(0)">Other Religions</a></li>
                                            <li><a href="JavaScript:void(0)">Others</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="JavaScript:void(0)">Premium Contents</a>
                                        <ul class="sub-menu">
                                            <li><a href="JavaScript:void(0)">Top10Torrent Exclusives</a></li>
                                            <li><a href="JavaScript:void(0)">Unrated</a></li>
                                            <li><a href="JavaScript:void(0)">18+</a></li>
                                        </ul>
                                    </li>--}}
                                    {{--@auth()
                                        <li class="menu-item-has-children">
                                            <a href="JavaScript:void(0)">Request</a>
                                            <ul class="sub-menu">
                                                <li><a href="JavaScript:void(0)">Fill Request</a></li>
                                                <li><a href="JavaScript:void(0)">Make Request</a></li>
                                            </ul>
                                        </li>
                                    @endauth--}}

                                </ul>
                            </div>

                            <div class="Offcanvas_footer">
                                {{--<span><a href="JavaScript:void(0)"><i class="fa fa-envelope-o"></i> info@yourdomain.com</a></span>--}}
                                <ul>
                                    <li class="facebook"><a href="https://www.facebook.com/groups/top10torrent" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li class="twitter"><a href="https://twitter.com/Top10Torrent" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li class="btn-danger" style="border-radius: 50%;"><a href="https://www.youtube.com/channel/UCUz3oz25orgtjUsj1GKp9QA" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                    <li class="twitter"><a href="https://t.me/top10torrent" target="_blank"><i class="fa fa-telegram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Offcanvas menu area end-->

        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-11 ml-auto mr-auto mt-3 mb-3">

                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{--                    <h4 class="alert-heading">Success!</h4>--}}
                            <p class="text-center"><strong>{{ Session::get('success') }}</strong></p>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif

                    @auth()
                        @if (Auth::user()->status == 'Warn')
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                {{--                    <h4 class="alert-heading">Success!</h4>--}}
                                <h3 class="text-center">Warning!</h3>
                                <p class="text-center"><strong>Reason: <br>{{ Auth::user()->status_reason }}</strong></p>
                            </div>

                            @yield('content')

                        @elseif (Auth::user()->status == 'Suspend')
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{--                    <h4 class="alert-heading">Success!</h4>--}}
                                <h3 class="text-center">Account Suspended!</h3>
                                <p class="text-center"><strong>Reason: <br>{{ Auth::user()->status_reason }}</strong></p>
                                <p class="text-center"><strong>Send a mail at "help@top10torrent.site" to remove suspension.</strong></p>
                            </div>
                        @else
                            @yield('content')
                        @endif
                    @endauth

                    @guest()
                        @yield('content')
                    @endguest

                </div>

                {{--@if(Request::is('dmca') || Request::is('notice-box') || Request::is('rules') || Request::is('rules-update'))
                @else
                    <div class="col-lg-11 ml-auto mr-auto mt-3 mb-3">
                        <div class="card shadow mt-3">
                            <div class="card-header">
                                <h4 class="m-0 float-left" style="width: 90% !important;">DMCA</h4>
                                @can('manage-dmca')
                                    <span class="updateOption">
                                    <a href="{{ route('dmca.edit') }}"
                                       class="btn btn-warning btn-just-icon float-right"
                                       data-formsize="large">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a>
                                </span>
                                @endcan


                            </div>
                            <div class="card-body noticeBoxWrapper">
                                <div class="noticeBox">
                                    <?php
                                    $content = file_get_contents(base_path('./resources/views/app/landing/dmca/content.blade.php'));
                                    $bbcode = new \App\Helpers\Bbcode();
                                    $linkify = new \App\Helpers\Linkify();
                                    ?>
                                    {!! $bbcode->parse($linkify->linky($content)) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                @endif--}}
            </div>
        </div>

        <!--footer area start-->
        <footer class="footer_widgets">
            <div class="container">
                <div class="footer_top">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="widgets_container contact_us">
                                <a href="{{ route('/') }}"><img src="{{ asset('images/core/logo.png') }}" alt="" width="90%"></a>
                                <div class="social_icone">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/groups/top10torrent" target="_blank" title="facebook"><i class="fa fa-facebook"></i></a>
                                            <div class="social_title">
                                                <p>Find Us</p>
                                                <h3>Facebook</h3>
                                            </div>
                                        </li>
                                        <li class="twitter"><a href="https://twitter.com/Top10Torrent" target="_blank" title="twitter"><i class="fa fa-twitter"></i></a>
                                            <div class="social_title">
                                                <p>Follow Us</p>
                                                <h3>Twitter</h3>
                                            </div>
                                        </li>
                                        <li class="google_plus"><a href="https://www.youtube.com/channel/UCUz3oz25orgtjUsj1GKp9QA" target="_blank" title="YouTube"><i class="fa fa-youtube"></i></a>
                                            <div class="social_title">
                                                <p>Subscribe</p>
                                                <h3>YouTube</h3>
                                            </div>
                                        </li>
                                        <li class="twitter"><a href="https://t.me/top10torrent" target="_blank" title="Telegram"><i class="fa fa-telegram"></i></a>
                                            <div class="social_title">
                                                <p>Find Us</p>
                                                <h3>Telegram</h3>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="widgets_container widget_menu">
                                        <h3>CATEGORIES</h3>
                                        <div class="footer_menu">
                                            <ul>
                                                <li><a href="https://top10torrent.site/category/movies">Movies</a></li>
                                                <li><a href="https://top10torrent.site/category/tv-series">Tv Series</a></li>
                                                <li><a href="https://top10torrent.site/category/anime">Anime</a></li>
                                                <li><a href="https://top10torrent.site/category/games">Games</a></li>
                                                <li><a href="https://top10torrent.site/category/software">Software</a></li>
                                                <li><a href="https://top10torrent.site/category/music">Music</a></li>
                                                <li><a href="https://top10torrent.site/category/etc">ETC...</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="widgets_container widget_menu">
                                        <h3>Information</h3>
                                        <div class="footer_menu">
                                            <ul>
                                                <li><a href="{{ route('upcoming.index') }}">Upcoming Exclusives</a></li>
                                                <li><a href="{{ route('rules.index') }}">Rules</a></li>
                                                <li><a href="{{ route('dmca.index') }}">DMCA</a></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        {{--<div class="col-lg-3 col-md-12">
                            <div class="widgets_container">
                                <h3>Latest Posts</h3>
                                <div class="Latest_Posts_wrapper">
                                    <div class="single_Latest_Posts">
                                        <div class="Latest_Posts_thumb">
                                            <a href="JavaScript:void(0)">
                                                <img class="border p-2" src="{{ asset('images/demo.png') }}" alt="" width="65px" height="65px">
                                            </a>
                                        </div>
                                        <div class="Latest_Posts_content">
                                            <h3><a href="JavaScript:void(0)">Blog image post title, something long</a></h3>
                                            <span><i class="zmdi zmdi-card-sd"></i> 10 Mar, 2019</span>
                                        </div>
                                    </div>
                                    <div class="single_Latest_Posts">
                                        <div class="Latest_Posts_thumb">
                                            <a href="JavaScript:void(0)">
                                                <img class="border p-2" src="{{ asset('images/demo.png') }}" alt="" width="65px" height="65px">
                                            </a>
                                        </div>
                                        <div class="Latest_Posts_content">
                                            <h3><a href="JavaScript:void(0)">Blog image post title, something long</a></h3>
                                            <span><i class="zmdi zmdi-card-sd"></i> 10 Mar, 2019</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}

                    </div>
                </div>
            </div>
            {{--<div class="footer_bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-7">
                            <div class="copyright_area">
                                <p>Copyright &copy; {{ \Carbon\Carbon::now()->format('Y') }} <a href="{{ route('/') }}"> Top10Torrent </a>  All Right Reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </footer>
        <!--footer area end-->

    </div>

    @include('globals.modal.global_modal')

    <!-- ========================== Globals ========================== -->

    <!-- ========================= Plugin JS ========================= -->

    <!-- jQuery -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- jQuery 2.1.0 -->
{{--    <script src="http://wysibb.com/js/jquery-2.1.0.min.js" type="text/javascript"></script>--}}

    <!-- Overlib JS -->
    <script src="{{ asset('plugins/overlib/overlib.min.js') }}" defer></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}" defer></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" defer></script>

    <!-- ========================= Theme JS ========================= -->

    <!-- Plugins JS -->
    <script src="{{ asset('app/js/plugins.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('app/js/main.min.js') }}" defer></script>

    <!-- ========================= Custom JS ========================= -->

    <!-- Custom Script -->
    <script rel="script/javascript" href="{{ asset('custom/script.js') }}"></script>

    <!-- ========================= Plugin JS ========================= -->

    <!--   BootBox   -->
    <script src="{{ asset('plugins/bootbox/bootbox.js') }}" defer></script>


    <!-- Load WysiBB JS and Theme -->
    <link rel="stylesheet" href="{{ asset('plugins/wysibb/theme/default/wbbtheme.css') }}" />
    <script src="{{ asset('plugins/wysibb/jquery.wysibb.min.js') }}"></script>

    <script>
        $("#description").wysibb();
    </script>
    <script>
        $(".showBB").wysibb();
    </script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- jquery-validation -->
    {{--<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.js') }}"></script>--}}

    @stack('script')

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>

        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
    @include('globals.functions.checkbox')

    @include('globals.functions.slug')

    @include('globals.functions.store')

    @include('globals.functions.show')

    @include('globals.functions.update')

    @include('globals.functions.delete')

    <script src="{{ asset('js/app.min.js') }}"></script>

    @yield('script')

    @livewireScripts

    <script>
        setInterval(function(){
                $('.mailBoxWrapper').load(' .mailBox')
                $('.helpWrapper').load(' .help')
            }, 7*1000
        );


    </script>

    {{--<script type="text/javascript">
        var idleTime = 0;
        $(window).on('load', function () {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 60*1000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function (e) {
                idleTime = 0;
            });
            $(this).keypress(function (e) {
                idleTime = 0;
            });
        });

        function timerIncrement() {
            idleTime = idleTime + 1;

            if (idleTime > 9) { // 10 minutes
                // alert('You were inactive too long... Press ok to continue to continue!');
                window.location.reload();
            }

        }
    </script>--}}

    <script type="text/javascript">
        $("#noticeBoxContent").wysibb();
        $("#dmcaContent").wysibb();
        $("#rulesContent").wysibb();
        $("#question").wysibb();
        $("#answer").wysibb();
        $("#message").wysibb(); // shoutbox
    </script>

    <script type="text/javascript" defer>

        $('body').on('click', '.updateNoticeBox', function () {

            $("#noticeBoxContent").sync();

            // $('#noticeBoxForm').submit();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $('#noticeBoxForm').attr('action'),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                data:new FormData($('#noticeBoxForm')[0]),
                success: function (data) {

                    {{--let noticeBoxContent = "{!! file_get_contents(base_path('./resources/views/app/landing/noticeBox.blade.php')) !!}";--}}

                    // $('.noticeBoxWrapper').load( ' .noticeBox' );

                    // toastr.success("Updated Successfully!");

                    location.href = "{{ route('/') }}"

                },
                error: function (data) {
                    //console.log('Error:', data);
                    toastr.error("Update Failed!")
                }
            });
        });

        $('body').on('click', '.updateDmca', function () {

            $("#dmcaContent").sync();

            // $('#noticeBoxForm').submit();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $('#dmcaForm').attr('action'),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                data:new FormData($('#dmcaForm')[0]),
                success: function (data) {

                    location.href = "{{ route('dmca.index') }}"

                },
                error: function (data) {
                    //console.log('Error:', data);
                    toastr.error("Update Failed!")
                }
            });

        });

        $('body').on('click', '.updateRules', function () {

            $("#rulesContent").sync();

            // $('#noticeBoxForm').submit();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $('#rulesForm').attr('action'),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                data:new FormData($('#rulesForm')[0]),
                success: function (data) {

                    location.href = "{{ route('rules.index') }}"

                },
                error: function (data) {
                    //console.log('Error:', data);
                    toastr.error("Update Failed!")
                }
            });

        });
    </script>

    {{--<script type="text/javascript">
        // Disable right click
        $(document).bind("contextmenu",function(e) {
            e.preventDefault();
        });

        // disable F12
        $(document).keydown(function(e){
            if(e.which === 123){
                alert('not allowed');
                return false;
            }
        });

        // return keypress of specific characters false
        $(document).keydown(function(e){
            /*if(e.ctrlKey && (e.which === 67 || e.which === 85 || e.which === 86 || e.which === 117)){
                alert('not allowed');
                return false;
            }*/
            if(e.ctrlKey && (e.which === 85 || e.which === 117)){
                alert('not allowed');
                return false;
            }
        });

        // return ture if only "u" is pressed not "Ctrl+u"
        $(document).keypress("u",function(e) {
            if(e.ctrlKey)
            {
                alert('not allowed');
                return false;
            }
            else
            {
                return true;
            }
        });
    </script>--}}

    <script src="{{ asset('plugins/echo.js/dist/echo.js') }}"></script>
    <script>

        echo.init(/*{
            callback: function (element, op) {
                console.log(element, 'has been', op + 'ed')
            }
        }*/);

        // echo.render(); is also available for non-scroll callbacks
    </script>

</body>
</html>

<?php
    if (\Illuminate\Support\Facades\Auth::check())
    {
        \App\Models\User::find(Auth::user()->id)->update(['last_online' => \Carbon\Carbon::now()]);
    }
?>
