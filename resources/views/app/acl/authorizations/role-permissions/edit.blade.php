<form action="{{ route('role-permission') }}" id="rolePermissionForm" method="post" data-id="{{$role->id}}">
    <input type="hidden" name="role_id" value="{{$role->id}}">
    <input type="hidden" name="permission_group_id" value="{{$permission_parent->id}}">
    <div class="row text-center">
        <div class="col-md-12" {{ count($permission_parent->permissions) > 0 ? '':'hidden' }}>
            <div class="form-group m-0">
                <label for="checkbox0" style="font-weight: normal;" class="p-2 mt-2 border">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkbox0" class="checkAll"
                            {{ count($permission_parent->permissions) == count($role->permissions) ? 'checked':'' }}
                        >
                        <label for="checkbox0" style="font-weight: normal;">
                            Select all
                        </label>
                    </div>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            @if(count($permission_parent->permissions) > 0)
            @foreach($permission_parent->permissions as $childrenPermission)
                <label for="checkbox{{$childrenPermission->id}}" style="font-weight: normal;" class="p-2 mt-2 border">
                    <div class="icheck-success d-inline">
                        <input type="checkbox" name="permission_id[]" class="check_uncheck"
                               id="checkbox{{$childrenPermission->id}}"
                               value="{{$childrenPermission->id}}"
                               data-id="{{ $childrenPermission->id }}"
                               data-row="{{ $role->id }}"
                            @foreach($role->permissions as $all_permissions)
                            {{$all_permissions->id == $childrenPermission->id ? 'checked':''}}
                            @endforeach
                        >
                        <label for="checkbox{{$childrenPermission->id}}" style="font-weight: normal;">
                            {{$childrenPermission->name}}
                        </label>
                    </div>
                </label>
            @endforeach
            @else
                <label for="checkbox{{$permission_parent->id}}" style="font-weight: normal;" class="p-2 mt-2 border">
                    <div class="icheck-success d-inline">
                        <input type="checkbox" name="permission_id[]" class="check_uncheck"
                               id="checkbox{{$permission_parent->id}}"
                               value="{{$permission_parent->id}}"
                               data-id="{{ $permission_parent->id }}"
                               data-row="{{ $role->id }}"
                            @foreach($role->permissions as $all_permissions)
                                {{$all_permissions->id == $permission_parent->id ? 'checked':''}}
                            @endforeach
                        >
                        <label for="checkbox{{$permission_parent->id}}" style="font-weight: normal;">
                            {{$permission_parent->name}}
                        </label>
                    </div>
                </label>
            @endif
        </div>
    </div>
</form>
