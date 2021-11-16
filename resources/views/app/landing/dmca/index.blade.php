@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">

                <div class="card-header">
                    <h4 class="m-0 float-left" style="width: 90% !important;">DMCA</h4>
                    @can('manage-rules')
                        <span class="updateOption">
                            <a href="{{ route('dmca.edit') }}"
                               class="btn btn-warning btn-just-icon float-right updateDmca"
                               data-formsize="large">
                                <i class="zmdi zmdi-edit"></i>
                            </a>
                        </span>
                    @endcan

                </div>
                <div class="card-body dmcaWrapper">
                    <div class="rules">
                        <?php
                        $content = file_get_contents(base_path('./resources/views/app/landing/dmca/content.blade.php'));
                        $bbcode = new \App\Helpers\Bbcode();
                        $linkify = new \App\Helpers\Linkify();
                        ?>
                        <form action="{{ route('dmca.update') }}" id="rulesForm" method="post">
                            @csrf
                            <div>
                                {!! $bbcode->parse($linkify->linky($content)) !!}
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
