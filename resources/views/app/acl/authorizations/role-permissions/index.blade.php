@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Assign User Role</h4>
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display table-responsive {{--nowrap--}}" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Roles</th>
                            <th>Permissions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <form data-id="{{ $role->id }}" method="post" class="userRoleForm" enctype="multipart/form-data">
                                @csrf
                                <tr data-row="{{ $role->id }}" style="color: {{ $role->color }};">
                                    <td class="text-center" style="color: {{ $role->color }};">{{$loop->iteration}}</td>
                                    <td style="color: {{ $role->color }};">
                                        {{$role->name}}
                                    </td>
                                    <td style="color: {{ $role->color }};">
                                        @foreach($permission_groups as $permission_group)
                                            {{--@if(count($permission_group->childrenPermissions) > 0)--}}
                                            <a href="JavaScript:void(0)" class="text-dark editRolePermission p-2 m-1 border"
                                               data-route="{{ route('edit-role-permission', ['role_id'=>$role->id,'permission_id'=>$permission_group->id]) }}"
                                               style="display: inline-block;"
                                            >
                                                <div class="icheck-success d-inline d-flex align-items-center">
                                                    <input type="checkbox" onclick="return false;" {{--name="role_id_{{$role->id}}"
                                                           id="role-{{$role->id}}-permissions-{{$permission_group->id}}"
                                                           value="{{$permission_group->id}}"--}}
                                                        @if(count($permission_group->permissions) > 0)
                                                            @foreach($permission_group->permissions as $childrenpermission)
                                                                @foreach($role->permissions as $role_permission)
                                                                    {{$role_permission->id == $childrenpermission->id ? 'checked':''}}
                                                                @endforeach
                                                            @endforeach
                                                        @else
                                                            @foreach($role->permissions as $role_permission)
                                                                {{$role_permission->id == $permission_group->id ? 'checked':''}}
                                                            @endforeach
                                                        @endif
                                                    >
                                                    <label for="" class="m-0 pl-1" style="font-weight: normal;color: {{ $role->color }};">
                                                        {{$permission_group->name}}
                                                    </label>
                                                </div>
                                            </a>
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


    <!-- Modal -->
    <div id="globalModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content custom-modal">

                <span class="load-overlay"></span>

                <div class="modal-header p-2">
                    <h4 class="modal-title"></h4>
                    <a class="btn btn-round btn-white text-danger close globalModalClose fancy-close" data-dismiss="modal">
                        <i class="zmdi zmdi-close"></i>

                    </a>
                </div>

                <div class="modal-body">
                    <span class="loadForm"></span>
                </div>
                <div class="modal-footer">
                    <br />
                    <div class="form-group" align="center">
                        {{--<input type="hidden" name="action" id="action" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />--}}
                        <button type="button" id="closeBtn" class="btn btn-danger globalModalClose float-left pt-2 pb-2 mr-1" data-dismiss="modal">Close</button>
                        <button type="button" id="updateRolePermission" class="btn btn-success pt-2 pb-2 ml-1 updateRolePermission"></button>
                    </div>
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
        // Edit record
        $('body').on('click', '.editRolePermission', function() {
            let route = $(this).data('route');
            let globalModal = $('#globalModal');
            let modalTitle = $(".modal-title");
            let closeBtn = $("#closeBtn");
            let submitBtn = $("#updateRolePermission");

            globalModal.modal({
                backdrop: 'static',
                keyboard: false
            });

            modalTitle.html('Update Role Permissions');
            submitBtn.attr('hidden', false);
            closeBtn.html('Close');
            submitBtn.html('Update');

            $('.load-overlay').append(
                '<div class="overlay-wrapper d-flex align-items-center justify-content-center">\n' +
                '                <div class="overlay">\n' +
                '                    <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>\n' +
                '                    <br>\n' +
                '                    <br>\n' +
                '                    <h4>Your request is being processed!</h4>\n' +
                '                    <h4>Please Wait</h4>\n' +
                '                </div>\n' +
                '            </div>'
            );

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: route,
                // dataType: 'json',
                dataType: 'html',
                success: function (data) {
                    $('.overlay-wrapper').remove();
                    globalModal.find('.loadForm').html(data);
                    // console.log(data);
                },
                error: function (data) {
                    $('.overlay-wrapper').remove();
                    //console.log('Error:', data);
                }
            });
        });


        $('body').on('click', '.updateRolePermission', function(e) {
            e.preventDefault();

            let action = $('#rolePermissionForm').attr('action');
            let globalModal = $('#globalModal');
            let rolePermissionForm = $('#rolePermissionForm');
            let globalTable = $('#globalTable');
            let submitBtn = $('#submitBtn');


            $('.load-overlay').append(
                '<div class="overlay-wrapper d-flex align-items-center justify-content-center">\n' +
                '                <div class="overlay">\n' +
                '                    <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>\n' +
                '                    <br>\n' +
                '                    <br>\n' +
                '                    <h4>Your request is being processed!</h4>\n' +
                '                    <h4>Please Wait</h4>\n' +
                '                </div>\n' +
                '            </div>'
            );


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: action,
                data:new FormData(rolePermissionForm[0]),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success: function (data) {


                    setTimeout(function(){

                        $('.overlay-wrapper').remove();

                        if($.isEmptyObject(data.error)){
                            globalModal.trigger("reset");
                            globalModal.modal('hide');
                            globalTable.replaceWith($('#globalTable',data));

                            $('tr').each(function (index, value){
                                if ($(this).data('row') === $('#rolePermissionForm').data('id')) {
                                    $(this).css('background', '#d8f5e0');
                                    $(this).delay(1500).queue(function (next) {
                                        $(this).css('background', 'transparent');
                                        next();
                                    });
                                }
                            });

                            $("#datatable").DataTable({
                                "responsive": true,
                                "autoWidth": false,
                            });

                        }

                    }, 2*1000);
                },
                error: function (data) {
                    alert('Operation Failed!');
                    submitBtn.html('Save');
                    $('.overlay-wrapper').remove();
                }
            });
        });
    </script>
@endpush

