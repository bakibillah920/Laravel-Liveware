<audio id="newShoutAudio">
    <source src="{{ asset('audio/newShout.mp3') }}" type="audio/mpeg">
</audio>
<div class="row">
    <div class="col-lg-12" id="shoutBoxContainer">
        <div class="card shadow mt-3">
            <div class="card-header">
                <h4 class="m-0 float-left" style="width: 90% !important;">Shout Box</h4>
                @can('manage-shout-delete')
                    <span class="updateOption">
                        <form action="{{ route('shouts.delete') }}" method="post">
                            @csrf
                            <button type="submit"
                                    class="btn btn-warning btn-just-icon float-right"
                                    data-formsize="large">
                                    <i class="zmdi zmdi-delete"></i>
                            </button>
                        </form>
                    </span>
                @endcan
            </div>
            <div class="card-body" id="shoutBox">
                <?php
                /*$has_shout = \App\Models\Shout::all();
                    if ($has_shout != '')
                    {

                    }*/

                /*$toDelete = \App\Models\Shout::where('created_at', '<', \Carbon\Carbon::now()->subDays(7))->get();
                if (count($toDelete) > 0)
                    {
                        \App\Models\Shout::where('created_at', '<', \Carbon\Carbon::now()->subDays(7))->delete();
                    }*/

                $shouts = \App\Models\Shout::orderBy('id','desc')->get();
                ?>
                <div class="shoutBoxWrapper">
                    <div class="shout-box border mb-3" style="overflow-y: auto; font-size: 14pt;">
                        @foreach($shouts as $shout)
                            <div class="shouts border" data-shoutID="{{ $shout->id }}">
                                @auth()
                                    @if(Auth::user()->id == $shout->user_id)
                                        <div class="row" style="width: 100%;">
                                            <div class="col-lg-10 ml-auto pr-0 d-flex align-self-auto">
                                                <span class="border m-2 ml-auto p-2 float-right">
                                                    <span style="font-size: 10pt; overflow: hidden;">{!! $shout->getShout() !!} </span>
                                                    <hr class="m-0">
                                                    <small class="float-right" style="font-size: 8pt;">
                                                        By: <strong><a href="{{ route('profile.show', $shout->user->username ) }}">{!! $shout->user->username !!}</a></strong> | {!! \Carbon\Carbon::parse($shout->created_at)->diffForHumans() !!}
                                                        @can('manage-shout-delete')
                                                            <form action="{{ route('shouts.delete.single', $shout->id) }}" method="post">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-just-icon"><i class="zmdi zmdi-delete"></i></button>
                                                            </form>
                                                        @endcan
                                                    </small>
                                                </span>
                                                <span class="m-2 float-right">
                                                    <img src="{{ asset('images/avatars/'.$shout->user->avatar) }}" alt="" style="height: 50px !important; width:50px !important; min-width: 50px !important; -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;">
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row" style="width: 100%;">
                                            <div class="col-lg-10 mr-auto pr-0 d-flex align-self-auto">
                                                <span class="m-2 float-right">
                                                    <img src="{{ asset('images/avatars/'.$shout->user->avatar) }}" alt="" style="height: 50px !important; width:50px !important; min-width: 50px !important; -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;">
                                                </span>
                                                <span class="border m-2 ml-0 p-2 float-right">
                                                    <span style="font-size: 10pt; overflow: hidden;">{!! $shout->getShout() !!}</span>
                                                    <hr class="m-0">
                                                    <small class="mr-auto" style="font-size: 8pt;">
                                                        By: <strong><a href="{{ route('profile.show', $shout->user->username ) }}">{!! $shout->user->username !!}</a></strong> | {!! \Carbon\Carbon::parse($shout->created_at)->diffForHumans() !!}
                                                        @can('manage-shout-delete')
                                                            <form action="{{ route('shouts.delete.single', $shout->id) }}" method="post">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-just-icon"><i class="zmdi zmdi-delete"></i></button>
                                                            </form>
                                                        @endcan
                                                    </small>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="row" style="width: 100%;">
                                        {{--<div class="col-lg-8">
                                            <img class="border m-2 ml-4" src="{{ asset('images/avatars/'.$shout->user->avatar) }}" alt="" width="50px" height="50px" style="-webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;">
                                            <span class="border rounded-pill m-2 mr-0 p-2">
                                                {!! $shout->getShout() !!}
                                                <small>{!! $shout->comment !!}</small>
                                            </span>
                                        </div>--}}
                                        <div class="col-lg-10 mr-auto pr-0 d-flex align-self-auto">
                                                <span class="m-2 float-right">
                                                    <img src="{{ asset('images/avatars/'.$shout->user->avatar) }}" alt="" style="height: 50px !important; width:50px !important; min-width: 50px !important; -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;">
                                                </span>
                                            <span class="border m-2 ml-0 p-2 float-right">
                                                    <span style="font-size: 10pt; overflow: hidden;">{!! $shout->getShout() !!}</span>
                                                    <hr class="m-0">
                                                    <small class="mr-auto" style="font-size: 8pt;">By: <strong><a href="{{ route('profile.show', $shout->user->username ) }}">{!! $shout->user->username !!}</a></strong> | {!! \Carbon\Carbon::parse($shout->created_at)->diffForHumans() !!}</small>
                                                </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('shouts.store') }}" method="post" id="shoutForm" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-gourp shoutinputLoader">
                        @auth()
                            @can('manage-shout-create')
                                <textarea name="message" id="message" rows="5" class="form-control"></textarea>
                                <button type="button" class="btn btn-success d-flex align-items-center shoutCreate form-control justify-content-center">
                                    <i class="zmdi zmdi-mail-send pr-2"></i> Send
                                </button>
                            @endcan
                        @else
                        <strong>Please Log In to send message</strong>
                        @endauth
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

