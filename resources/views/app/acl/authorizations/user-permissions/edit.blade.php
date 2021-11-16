<form action="{{ route('user-permission') }}" id="userPermissionForm" method="post" data-id="{{$user->id}}">
    <input type="hidden" name="user_id" value="{{$user->id}}">
    <input type="hidden" name="permission_group_id" value="{{$permission_parent->id}}">
    <div class="row text-center">
        <div class="col-md-12">
            <div class="form-group m-0">
                <label for="checkbox0" style="font-weight: normal;" class="p-2 mt-2 border">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkbox0" class="checkAll"
                            {{ count($permission_parent->permissions) == count($user->permissions) ? 'checked':'' }}
                        >
                        <label for="checkbox0" style="font-weight: normal;">
                            Select all
                        </label>
                    </div>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            @foreach($permission_parent->permissions as $childrenPermission)
                <label for="checkbox{{$childrenPermission->id}}" style="font-weight: normal;" class="p-2 mt-2 border">
                    <div class="icheck-success d-inline">
                        <input type="checkbox" name="permission_id[]" class="check_uncheck"
                               id="checkbox{{$childrenPermission->id}}"
                               value="{{$childrenPermission->id}}"
                               data-id="{{ $childrenPermission->id }}"
                               data-row="{{ $user->id }}"
                            @foreach($user->permissions as $all_permissions)
                                {{$all_permissions->id == $childrenPermission->id ? 'checked':''}}
                            @endforeach
                            @foreach($user->roles as $user_role)
                                @foreach($user_role->permissions as $role_permission)
                                    {{ $role_permission->id == $childrenPermission->id ? 'checked disabled':'' }}
                                @endforeach
                            @endforeach
                        >
                        <label for="checkbox{{$childrenPermission->id}}" style="font-weight: normal;">
                            {{$childrenPermission->name}}
                        </label>
                    </div>
                </label>
            @endforeach
        </div>
    </div>
</form>
