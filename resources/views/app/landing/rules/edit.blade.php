@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">

                <div class="card-header">
                    <h4 class="m-0 float-left" style="width: 90% !important;">Rules</h4>
                    @can('manage-rules')
                        <span class="updateOption">
                            <a href="JavaScript:void(0)"
                               class="btn btn-success btn-just-icon float-right updateRules"
                               data-formsize="large">
                                <i class="zmdi zmdi-check"></i>
                            </a>
                        </span>
                    @endcan

                </div>
                <div class="card-body dmcaWrapper">
                    <div class="rules">
                        <?php
                        $content = file_get_contents(base_path('./resources/views/app/landing/rules/content.blade.php'));
                        $bbcode = new \App\Helpers\Bbcode();
                        $linkify = new \App\Helpers\Linkify();
                        ?>
                        <form action="{{ route('rules.update') }}" id="rulesForm" method="post">
                            @csrf
                            <textarea name="rulesContent" id="rulesContent" class="form-control">
                                {!! $content !!}
                            </textarea>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
