@extends('layouts.app')

@section('content')
    @if($user->username == 'System')
        <div class="alert alert-danger text-center">
            No user exists with this name!
        </div>
    @else
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mt-3">
                    <div class="card-header">

                        <h4 class="m-0 float-left"><a href="{{ route('profile.show', $user->username) }}" class="text-info">{{ $user->username }}</a> - Profile</h4>

                        @if(Auth::user()->id == $user->id)
                            <a href="{{ route('profile.edit', $user->username) }}" type="button" id="submitBtn" class="ml-2 mr-2 float-right btn btn-outline-info btn-sm" title="Edit Profile">
                                <i class="zmdi zmdi-edit zmdi-hc-lg"></i>
                            </a>
                        @else
                            <a href="{{ route('mail-box.sendMail', $user->id) }}" type="button" id="submitBtn" class="ml-2 mr-2 float-right btn btn-outline-success btn-sm" title="Send PM">
                                <i class="zmdi zmdi-email zmdi-hc-lg"></i>
                            </a>
                        @endif

                        <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm">
                            <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                        </a>

                    </div>
                    <div class="card-body torrent-list">
                        <div class="row">
                            <div class="col-lg-3">
                                <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/avatars/'.$user->avatar) }}" alt="" width="100%">
                            </div>
                            <div class="col-lg-9">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Username:</td>
                                        <td>{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td>Role:</td>
                                        <td>{{ $user->roles[0]->name }}</td>
                                    </tr>
                                    @if(Auth::user()->id == $user->id)
                                        <tr>
                                            <td>Email:</td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Age:</td>
                                        <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->diffInYears():'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Gender:</td>
                                        <td>{{ $user->gender ? $user->gender:'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Joined:</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Online:</td>
                                        <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d M, Y g:i A') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($user->uploads) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">

                            <h4 class="m-0 float-left">Uploads</h4>

                        </div>
                        <div class="card-body torrent-list">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Torrent Name</th>
                                    {{--                                <th class="text-center"><i class="zmdi zmdi-download zmdi-hc-lg"></i></th>--}}
                                    <th class="torrent-list-rating text-center">IMDB Rating</th>
                                    <th class="torrent-list-size text-center">Size</th>
                                    <th class="torrent-list-time text-center">Time</th>
                                </tr>
                                </thead>
                                <tbody> {{--14 tr--}}
                                @if(Auth::user()->id == $user->id)
                                    @foreach($authUserUploads as $upload)
                                        <?php $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->torrent) ); ?>
                                        <tr class="border-bottom">
                                            <td width="50%" class="torrent-list-title">
                                                <a href="{{ route('landing.categories.show',[$upload->category->parentCategory->slug, $upload->category->slug, $upload->slug]) }}" class="hover-title" title="Torrent Name"
                                                   onmouseover=" return overlib('<img src={!! asset('torrents/'.$upload->image) !!} ' +
                                                                        'width=200 border=0>', CENTER, HAUTO, VAUTO);" onmouseout="return nd();"
                                                >
                                                    <div class=" d-flex align-items-center">
                                                        <span class="list-view-icon" style="height: 25px !important; width: 25px !important;">
                                                            @if($upload->category->parentCategory->icon)
                                                                @if(pathinfo(public_path('images/categories/'.$upload->category->icon), PATHINFO_EXTENSION) === 'svg')
                                                                    {!! file_get_contents(public_path('images/categories/'.$upload->category->parentCategory->icon)) !!}
                                                                @else
                                                                    <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/categories/'.$upload->category->parentCategory->icon) }}" alt="{{ $upload->category->parentCategory->name }}">
                                                                @endif
                                                            @else
                                                                {!! file_get_contents(public_path('images/categories/Question Mark.svg')) !!}
                                                            @endif
                                                        </span>
                                                        <h5 class="mt-1 ml-2">
                                                            {!! Str::limit($upload->name, 150) !!}
                                                        </h5>
                                                    </div>

                                                </a>
                                            </td>
                                            {{--<td class="torrent-list-time text-center">
                                                <?php
                                                ?>
                                                @auth()
                                                    <a href="{!! asset('torrents/'.$upload->torrent) !!}" download target="_blank">
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                @else
                                                    <a href="JavaScript:void(0)" data-toggle="modal" data-target="#loginNoticeModal"
                                                    >
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                    @include('globals.modal.login_notice')
                                                @endauth
                                            </td>--}}
                                            <td width="12%" class="torrent-list-time text-center">
                                                <?php
                                                if ($upload->imdbKey)
                                                {
                                                    $IMDB = new IMDB($upload->imdbKey->key);
                                                    if ($IMDB->isReady) {
                                                        echo $IMDB->getRating() .'/10';
                                                    } else {
                                                        echo 'Movie not found. 😞';
                                                    }
                                                }else {
                                                    echo '😞';
                                                }
                                                ?>
                                            </td>
                                            <td width="10%" class="torrent-list-size text-center">
                                                {!! $torrent->size( 2 ) !!}
                                            </td>
                                            <td width="12%" class="torrent-list-time text-center">
                                                {!! \Carbon\Carbon::parse($upload->created_at)->format('M d, Y') !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($uploads->where('is_anonymous', '!=', 'on') as $upload)
                                        <?php $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->torrent) ); ?>
                                        <tr class="border-bottom">
                                            <td width="50%" class="torrent-list-title">
                                                <a href="{{ route('landing.categories.show',[$upload->category->parentCategory->slug, $upload->category->slug, $upload->slug]) }}" class="hover-title" title="Torrent Name"
                                                   onmouseover=" return overlib('<img src={!! asset('torrents/'.$upload->image) !!} ' +
                                                                        'width=200 border=0>', CENTER, HAUTO, VAUTO);" onmouseout="return nd();"
                                                >
                                                    <div class=" d-flex align-items-center">
                                                        <span class="list-view-icon" style="height: 25px !important; width: 25px !important;">
                                                            @if($upload->category->parentCategory->icon)
                                                                @if(pathinfo(public_path('images/categories/'.$upload->category->icon), PATHINFO_EXTENSION) === 'svg')
                                                                    {!! file_get_contents(public_path('images/categories/'.$upload->category->parentCategory->icon)) !!}
                                                                @else
                                                                    <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/categories/'.$upload->category->parentCategory->icon) }}" alt="{{ $upload->category->parentCategory->name }}">
                                                                @endif
                                                            @else
                                                                {!! file_get_contents(public_path('images/categories/Question Mark.svg')) !!}
                                                            @endif
                                                        </span>
                                                        <h5 class="mt-1 ml-2">
                                                            {!! Str::limit($upload->name, 150) !!}
                                                        </h5>
                                                    </div>

                                                </a>
                                            </td>
                                            {{--<td class="torrent-list-time text-center">
                                                @auth()
                                                    <a href="{!! asset('torrents/'.$upload->torrent) !!}" download target="_blank">
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                @else
                                                    <a href="JavaScript:void(0)" data-toggle="modal" data-target="#loginNoticeModal"
                                                    >
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                    @include('globals.modal.login_notice')
                                                @endauth
                                            </td>--}}
                                            <td width="12%" class="torrent-list-time text-center">
                                                <?php
                                                if ($upload->imdbKey)
                                                {
                                                    $IMDB = new IMDB($upload->imdbKey->key);
                                                    if ($IMDB->isReady) {
                                                        echo $IMDB->getRating() .'/10';
                                                    } else {
                                                        echo 'Movie not found. 😞';
                                                    }
                                                }else {
                                                    echo '😞';
                                                }
                                                ?>
                                            </td>
                                            <td width="10%" class="torrent-list-size text-center">
                                                {!! $torrent->size( 2 ) !!}
                                            </td>
                                            <td width="12%" class="torrent-list-time text-center">
                                                {!! \Carbon\Carbon::parse($upload->created_at)->format('M d, Y') !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                            @if(Auth::user()->id == $user->id || count($user->uploads->where('is_anonymous', '!=', 'on')) > 50)
                                {!! $uploads->links() !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--<div class="row">
            <div class="col-lg-12">
                <div class="card shadow mt-3">
                    <div class="card-header">

                        <h4 class="m-0 float-left">Role Wise Category Permissions</h4>

                    </div>
                    <div class="card-body torrent-list">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-hover table-responsive table-responsive border" width="100%">
                                    <thead>
                                    <tr>
                                        <td>Role</td>
                                        <td>Category Permissions</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(\App\Models\Role::where('slug','!=','developer')->where('slug','!=','admin')->get() as $role)
                                        <tr>
                                            <td>
                                                {!! $role->name == $user->roles[0]->name ? '<strong class="text-info"> '.$role->name.' </span>':$role->name !!}
                                            </td>
                                            <td>
                                                <?php
                                                    $categories = \App\Models\Category::all();
                                                ?>
                                                @foreach($categories as $category)
                                                    <?php $permission = $role->permissions->where('slug','=','navcategory-'.$category->slug)->first(); ?>
                                                        @if($permission)
                                                            <span class="border border-success p-2"><i class="zmdi zmdi-check-circle text-success"></i> {{ $category->name }}  {!! count($category->childCategories) > 0 ? '<i class="zmdi zmdi-caret-down"></i>':'' !!}</span>
                                                        @else
                                                            <span class="border border-danger p-2"><i class="zmdi zmdi-close-circle text-danger"></i> {{ $category->name }} {!! count($category->childCategories) > 0 ? '<i class="zmdi zmdi-caret-down"></i>':'' !!}</span>
                                                        @endcan
                                                @endforeach

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    @endif
@endsection
