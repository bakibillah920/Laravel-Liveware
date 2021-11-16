@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Mail Box</h4>
                    <a href="JavaScript:void(0)" class="float-right btn btn-outline-success btn-sm createData"
                       data-route="{{ route('mail-box.create') }}"
                       data-formtype="newPage"
                       data-formsize="small">
                        <i class="zmdi zmdi-email zmdi-hc-lg"></i> New Mail
                    </a>
                    <a href="{{ route('mail-box.index') }}" class="float-right mr-2 btn btn-outline-info btn-sm">
                        <i class="zmdi zmdi-mall zmdi-hc-lg"></i> Inbox
                    </a>
                    {{--<a href="JavaScript:void(0)" class="float-right mr-2 btn btn-outline-warning btn-sm"
                       onclick="event.preventDefault();
                       document.getElementById('readAllPM').submit();"
                    >
                        <i class="zmdi zmdi-email zmdi-hc-lg"></i> Mark all as read
                    </a>

                    <form id="readAllPM" action="{{ route('mail-box.readAll') }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                    </form>--}}


                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
{{--                            <th>Sender</th>--}}
                            <th>To</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($pms as $pm)
                            <?php

                            if(count($pm->pm_replay) > 0){ $count = " (".count($pm->pm_replay).") "; }else{ $count = ''; }
                            if($pm->receiver->username == 'System'){ $route = "JavaScript:void(0)"; }else{ $route = route('profile.show', $pm->receiver->username ); }

                            ?>
                            <tr data-row="{{ $pm->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
{{--                                <td class="">{!! '<a href="'.route('profile.show', $pm->sender->username ).'">' .$pm->sender->username .'</a>' .'<br> Sent: '. \Carbon\Carbon::parse($pm->created_at)->format('d-M-y - g:i A') !!}</td>--}}
                                <td class="">{!! '<a href="'. $route .'">' .$pm->receiver->username .$count .'</a>' .'<br> Read: '. \Carbon\Carbon::parse($pm->read_at)->format('d-M-y - g:i A') !!}</td>
                                <td class="">
                                    <a href="JavaScript:void(0)" title="Open Mail"
                                       class="text-info showData"
                                       data-id="{{ $pm->id }}"
                                       data-route="{{ route('mail-box.show', $pm->id) }}">
                                        {{ Str::limit($pm->subject, 50) }}
                                    </a>
                                </td>
                                <td class="">{!! Str::limit($pm->getDescription(), 100) !!}</td>
                                <td class=""><span class="badge badge-{{ $pm->read_at == null ? 'danger':'success' }}">{{ $pm->read_at == null ? 'Unread':'Read' }}</span></td>
                                {{--<td class="text-center" width="150px">
                                    <table>
                                        <tr style="background: transparent; border: none;">
                                            @if($pm->read_at == null)
                                            <td style="border: none;">
                                                <form action="{{ route('mail-box.read', $pm->id) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Mark As Read"
                                                            class="btn btn-outline-info btn-just-icon"
                                                            data-id="{{ $pm->id }}"
                                                            data-route="{{ route('mail-box.read', $pm->id) }}">
                                                        <i class="zmdi zmdi-email-open"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                            <td style="border: none;">
                                                <a href="JavaScript:void(0)" title="Open Mail"
                                                   class="btn btn-outline-success btn-just-icon showData"
                                                   data-id="{{ $pm->id }}"
                                                   data-route="{{ route('mail-box.show', $pm->id) }}">
                                                    <i class="zmdi zmdi-eye"></i>
                                                </a>
                                            </td>
                                            <td style="border: none;">
                                                <a href="JavaScript:void(0)" title="Delete Mail"
                                                   class="btn btn-outline-danger btn-just-icon deleteData"
                                                   data-id="{{ $pm->id }}"
                                                   data-route="{{ route('mail-box.destroy', $pm->id) }}">
                                                    <i class="zmdi zmdi-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>--}}

                                <td style="border: none;">
                                    @if($pm->read_at == null)

                                        <a href="{{ route('mail-box.read', $pm->id) }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('markRead_{{ $pm->id }}').submit();">
                                            <button type="submit" title="Mark As Read"
                                                    class="btn btn-outline-info btn-just-icon"
                                                    data-id="{{ $pm->id }}"
                                                    data-route="{{ route('mail-box.read', $pm->id) }}">
                                                <i class="zmdi zmdi-email-open"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('mail-box.read', $pm->id) }}" id="markRead_{{ $pm->id }}" method="post" style="display: none;">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                    @endif
                                    <a href="JavaScript:void(0)" title="Open Mail"
                                       class="btn btn-outline-success btn-just-icon showData"
                                       data-id="{{ $pm->id }}"
                                       data-route="{{ route('mail-box.show', $pm->id) }}">
                                        <i class="zmdi zmdi-eye"></i>
                                    </a>
                                    @if($pm->receiver->username == "System")
                                    @else
                                        <a href="{{ route('mail-box.replayMail', [$pm->receiver->id, $pm->id]) }}" id="submitBtn" title="Replay PM"
                                           class="btn btn-outline-warning btn-just-icon">
                                            <i class="zmdi zmdi-mail-reply"></i>
                                        </a>
                                    @endif
                                    <a href="JavaScript:void(0)" title="Delete Mail"
                                       class="btn btn-outline-danger btn-just-icon deleteData"
                                       data-id="{{ $pm->id }}"
                                       data-route="{{ route('mail-box.destroy', $pm->id) }}">
                                        <i class="zmdi zmdi-close"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
