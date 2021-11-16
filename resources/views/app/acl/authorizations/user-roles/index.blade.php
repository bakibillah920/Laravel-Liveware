@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Assign User Role</h4>
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th width="10px">Serial</th>
                            <th>Users</th>
                            <th>Roles</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <form data-id="{{ $user->id }}" method="post" class="userRoleForm" enctype="multipart/form-data">
                                @csrf
                                <tr data-row="{{ $user->id }}">
                                    <td class="text-center" width="10px">{{$loop->iteration}}</td>
                                    <td>
                                        <a href="{{ route('profile.show', $user->username) }}">
                                            {{$user->username}}
                                            <br>
                                            {{$user->email}}
                                        </a>
                                    </td>
                                    <td>
                                        @foreach($roles as $role)
                                            <label for="user-{{$user->id}}-role-{{$role->id}}" style="font-weight: normal;" class="p-2 mt-2 border">
                                                <div class="icheck-success d-inline" disabled>
                                                    <input type="radio" name="role_id_{{$user->id}}[]"
                                                           class="updateUserRole"
                                                           id="user-{{$user->id}}-role-{{$role->id}}"
                                                           value="{{$role->id}}"
                                                           data-id="{{ $role->id }}"
                                                           data-row="{{ $user->id }}"
                                                           data-route="{{ route('user-role', ['user_id'=>$user->id,'role_id'=>$role->id]) }}"
                                                        {!! $user->roles[0]->id == $role->id ? ' checked="true"':'' !!}
                                                    >
                                                    <label for="user-{{$user->id}}-role-{{$role->id}}" style="font-weight: normal;">
                                                        {{$role->name}}
                                                    </label>
                                                </div>
                                            </label>
                                        @endforeach
                                    </td>
                                </tr>
                            </form>

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
        $('#datatable').DataTable();
    </script>
    <script>
        $('body').on('click', '.updateUserRole', function(e) {
            e.preventDefault();

            let userRoleForm = $('.userRoleForm');
            let action = $(this).data('route');
            let globalTable = $('#globalTable');
            let dataRow = $(this).data('row');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: action,
                data:new FormData(userRoleForm[0]),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success: function (data) {
                    if($.isEmptyObject(data.error)){
                        globalTable.replaceWith($('#globalTable',data));
                        $('tr').each(function (index, value){
                            if ($(this).data('row') === dataRow) {
                                $(this).css('background', '#d8f5e0');
                                $(this).delay(1500).queue(function (next) {
                                    $(this).css('background', 'transparent');
                                    next();
                                });
                            }
                        });
                        $('#datatable').DataTable();
                    }
                },
                error: function (data) {
                    alert('Operation Failed!');
                }
            });
        });
    </script>
@endpush

