@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">

                <div class="card-header">
                    <h4 class="m-0 float-left" style="width: 90% !important;">DMCA</h4>
                    @can('manage-dmca')
                        <span class="updateOption">
                            <a href="JavaScript:void(0)"
                               class="btn btn-success btn-just-icon float-right updateDmca"
                               data-formsize="large">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                        </span>

                    @endcan


                </div>
                <div class="card-body dmcaWrapper">
                    <div class="dmca">
                        <?php
                        $content = file_get_contents(base_path('./resources/views/app/landing/dmca/content.blade.php'));
                        $bbcode = new \App\Helpers\Bbcode();
                        $linkify = new \App\Helpers\Linkify();
                        ?>
                            <form action="{{ route('dmca.update') }}" id="dmcaForm" method="post">
                                @csrf
                                <textarea name="dmcaContent" id="dmcaContent" class="form-control">
                                    {!! $content !!}
                                </textarea>
                            </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
