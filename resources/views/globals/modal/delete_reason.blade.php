<div class="modal fade deleteReasonModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">

            <span class="load-overlay"></span>

            <div class="modal-header p-2">
                <h4 class="modal-title text-danger">Delete Reason</h4>
                <a class="btn btn-round btn-white text-danger close fancy-close" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i>
                </a>
            </div>
            <div class="modal-body{{-- scroll-overlay--}}">
                <h4 class="text-center p-3">
                    Please mention the reason you're deleting this content!
                    <br>
                    <br>

                    <form action="{{ route('uploads.destroy' , $upload->id ) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_path" class="form-control" value="{{ Request::url() }}">
                        <textarea name="delete_reason" class="delete_reason form-control mb-3" rows="10"></textarea>
                        <button type="submit" class="btn btn-danger btn-just-icon float-right ml-2" value=""><i class="zmdi zmdi-delete"></i></button>
                        <button type="button" id="closeBtn" class="btn btn-danger float-left pt-2 pb-2 mr-1" data-dismiss="modal">Close</button>
                    </form>
                </h4>
            </div>
            {{--<div class="modal-footer">
                <br />
                <div class="form-group" align="center">
                    <form action="{{ route('uploads.destroy' , $upload->id ) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-just-icon float-right ml-2" value=""><i class="zmdi zmdi-delete"></i></button>
                    </form>
                    <button type="button" id="closeBtn" class="btn btn-danger float-left pt-2 pb-2 mr-1" data-dismiss="modal">Close</button>
                </div>
            </div>--}}
        </div>
    </div>
</div>

