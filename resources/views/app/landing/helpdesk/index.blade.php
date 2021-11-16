@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            @can('manage-helpdesk-list')
                <div class="card shadow mt-3">
                    <div class="card-header">
                        <h4 class="m-0 float-left">Help List</h4>
                        @can('manage-helpdesk-ask-question')
                            <a href="{{ route('help.create') }}" class="float-right btn btn-outline-success btn-sm">
                                <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> Ask Question
                            </a>
                        @endcan
                    </div>
                    <div class="card-body torrent-list" id="globalTable">
                        <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Subject</th>
                                <th>Question</th>
                                <th>Date Added</th>
                                <th>Asked By</th>
                                <th>Answered</th>
                                <th>Answered By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody> {{--14 tr--}}
                            @foreach($helps as $help)

                                @can('manage-helpdesk-view-all-question')
                                    <tr data-row="{{ $help->id }}"
                                        @if ($help->asked_by == Auth::user()->id && $help->is_answered == 1)
                                            class="{{ $help->read_at == '' ? 'alert-success':'' }}"
                                        @endif>
                                        <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                        <td class="">

    {{--                                        @if($help->answerer)--}}

                                                @if($help->asked_by == Auth::user()->id && $help->is_answered != 1)
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-danger btn-just-icon deleteData mr-2"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.destroy', $help->id) }}"
                                                        {{--data-delete="{{ $upcoming->id }}"--}}>
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('help.show', $help->id) }}"
                                                   class="text-success"
                                                   data-formtype="newPage">
                                                    <strong> {{ $help->subject }}</strong>
                                                </a>

    {{--                                        @endif--}}

                                        </td>
                                        <td>{!! Str::limit($help->getQuestion(), 100) !!}</td>
                                        <td>{{ \Carbon\Carbon::parse($help->created_at)->format('d M, y - g:i A') }}</td>
                                        <td class="">{!! $help->requester ? '<a href="'.route('profile.show', $help->requester->username).'">'.$help->requester->username.'</a>' : 'Guest' !!}</td>
                                        <td class="text-{!! $help->is_answered == 1 ? 'success':'danger' !!}">
                                            <strong>{!! $help->is_answered == 1 ? 'Yes':'No' !!}</strong>
                                        </td>
                                        <td class="">

                                            @if($help->answerer)
                                                {!! '<a href="'.route('profile.show', $help->answerer->username).'">'.$help->answerer->username.'</a>'  !!}
                                            @else
                                                <div class="text-danger">None</div>
                                            @endif

                                        </td>
                                        <td>

                                            @if($help->answerer)

                                                <a href="{{ route('help.show',$help->id) }}"
                                                   class="btn btn-sm btn-success"
                                                   data-formtype="newPage">
                                                    View Answer
                                                </a>

                                                @can('manage-helpdesk-edit-answer')
                                                    @if(Auth::user()->id == $help->answered_by)
                                                        <a href="JavaScript:void(0)"
                                                           class="btn btn-sm btn-warning editData"
                                                           data-id="{{ $help->id }}"
                                                           data-route="{{ route('help.edit', $help->id) }}"
                                                           data-formtype="newPage">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </a>
                                                    @endif
                                                @elsecan('manage-helpdesk-edit-own-answer')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-warning editData"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.edit', $help->id) }}"
                                                       data-formtype="newPage">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('manage-helpdesk-give-answer')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-warning editData"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.edit', $help->id) }}"
                                                       data-formtype="newPage"
                                                    >
                                                        Answer This Question!
                                                    </a>
                                                @endcan
                                            @endif

                                            @can('manage-helpdesk-delete')
                                                <a href="JavaScript:void(0)"
                                                   class="btn btn-sm btn-danger btn-just-icon deleteData"
                                                   data-id="{{ $help->id }}"
                                                   data-route="{{ route('help.destroy', $help->id) }}"
                                                    {{--data-delete="{{ $upcoming->id }}"--}}>
                                                    <i class="zmdi zmdi-delete"></i>
                                                </a>
                                            @else
                                                @if($help->answered_by == Auth::user()->id && $help->is_answered == 0)
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-danger btn-just-icon deleteData"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.destroy', $help->id) }}"
                                                        {{--data-delete="{{ $upcoming->id }}"--}}>
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('manage-helpdesk-edit-answer')
                                                <a href="JavaScript:void(0)"
                                                   class="btn btn-sm btn-warning btn-just-icon editData"
                                                   data-id="{{ $help->id }}"
                                                   data-route="{{ route('help.edit', $help->id) }}"
                                                   data-formtype="newPage">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @else
                                    @if($help->asked_by == Auth::user()->id)
                                        <tr data-row="{{ $help->id }}" class="{{ $help->read_at == null ? 'alert-danger':'' }}">
                                            <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                            <td class="">

                                                {{--                                        @if($help->answerer)--}}

                                                @if($help->asked_by == Auth::user()->id && $help->is_answered == 0)
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-danger btn-just-icon deleteData mr-2"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.destroy', $help->id) }}"
                                                        {{--data-delete="{{ $upcoming->id }}"--}}>
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('help.show', $help->id) }}"
                                                   class="text-success"
                                                   data-formtype="newPage">
                                                    <strong> {{ $help->subject }}</strong>
                                                </a>

                                                {{--                                        @endif--}}

                                            </td>
                                            <td>{!! Str::limit($help->getQuestion(), 100) !!}</td>
                                            <td>{{ \Carbon\Carbon::parse($help->created_at)->format('d M, y - g:i A') }}</td>
                                            <td class="">{!! $help->requester ? '<a href="'.route('profile.show', $help->requester->username).'">'.$help->requester->username.'</a>' : 'Guest' !!}</td>
                                            <td class="text-{!! $help->is_answered == 1 ? 'success':'danger' !!}">
                                                <strong>{!! $help->is_answered == 1 ? 'Yes':'No' !!}</strong>
                                            </td>
                                            <td class="">

                                                @if($help->answerer)
                                                    {!! '<a href="'.route('profile.show', $help->answerer->username).'">'.$help->answerer->username.'</a>'  !!}
                                                @else
                                                    <div class="text-danger">None</div>
                                                @endif

                                            </td>
                                            <td>

                                                @if($help->answerer)

                                                    <a href="{{ route('help.show',$help->id) }}"
                                                       class="btn btn-sm btn-success"
                                                       data-formtype="newPage">
                                                        View Answer
                                                    </a>

                                                    @can('manage-helpdesk-edit-answer')
                                                        @if(Auth::user()->id == $help->answered_by)
                                                            <a href="JavaScript:void(0)"
                                                               class="btn btn-sm btn-warning editData"
                                                               data-id="{{ $help->id }}"
                                                               data-route="{{ route('help.edit', $help->id) }}"
                                                               data-formtype="newPage">
                                                                <i class="zmdi zmdi-edit"></i>
                                                            </a>
                                                        @endif
                                                    @elsecan('manage-helpdesk-edit-own-answer')
                                                        <a href="JavaScript:void(0)"
                                                           class="btn btn-sm btn-warning editData"
                                                           data-id="{{ $help->id }}"
                                                           data-route="{{ route('help.edit', $help->id) }}"
                                                           data-formtype="newPage">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </a>
                                                    @endcan
                                                @else
                                                    @can('manage-helpdesk-give-answer')
                                                        <a href="JavaScript:void(0)"
                                                           class="btn btn-sm btn-warning editData"
                                                           data-id="{{ $help->id }}"
                                                           data-route="{{ route('help.edit', $help->id) }}"
                                                           data-formtype="newPage"
                                                        >
                                                            Answer This Question!
                                                        </a>
                                                    @endcan
                                                @endif

                                                @can('manage-helpdesk-delete')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-danger btn-just-icon deleteData"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.destroy', $help->id) }}"
                                                        {{--data-delete="{{ $upcoming->id }}"--}}>
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                @else
                                                    @if($help->answered_by == Auth::user()->id && $help->is_answered == 0)
                                                        <a href="JavaScript:void(0)"
                                                           class="btn btn-sm btn-danger btn-just-icon deleteData"
                                                           data-id="{{ $help->id }}"
                                                           data-route="{{ route('help.destroy', $help->id) }}"
                                                            {{--data-delete="{{ $upcoming->id }}"--}}>
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('manage-helpdesk-edit-answer')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-sm btn-warning btn-just-icon editData"
                                                       data-id="{{ $help->id }}"
                                                       data-route="{{ route('help.edit', $help->id) }}"
                                                       data-formtype="newPage">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endif
                                @endcan
                            @endforeach
                            </tbody>
                        </table>
                        {{--<div class="shop_toolbar t_bottom mt-4">
                            <div class="pagination">
                                <ul>
                                    <li class="current">1</li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li class="next"><a href="#">next</a></li>
                                    <li><a href="#">>></a></li>
                                </ul>
                            </div>
                        </div>--}}
                    </div>
                </div>
            @else
                <h1 class="text-center">
                    Noting to show!
                </h1>
            @endcan
        </div>
    </div>

@endsection

@push('script')

    <script>
        // Initialize Datatable
        $(document).ready( function () {
            $('#datatable').DataTable();
        } );
    </script>

@endpush
