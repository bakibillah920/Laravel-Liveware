@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">

                <div class="card-header">
                    <h4 class="m-0 float-left" style="width: 90% !important;">Notice Box</h4>
                    @can('manage-noticebox')
                        <span class="updateOption">
                            <a href="JavaScript:void(0)"
                               class="btn btn-success btn-just-icon float-right updateNoticeBox"
                               data-formsize="large">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                        </span>

                    @endcan


                </div>
                <div class="card-body noticeBoxWrapper">
                    <div class="noticeBox">
                        <?php
                        $content = file_get_contents(base_path('./resources/views/app/landing/noticeBox/content.blade.php'));
                        $bbcode = new \App\Helpers\Bbcode();
                        $linkify = new \App\Helpers\Linkify();
                        ?>
                            <form action="{{ route('noticeBoxUpdate') }}" id="noticeBoxForm" method="post">
                                @csrf
                                <textarea name="noticeBoxContent" id="noticeBoxContent" class="form-control">
                                    {!! file_get_contents(base_path('./resources/views/app/landing/noticeBox/content.blade.php')) !!}
                                </textarea>
                            </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
