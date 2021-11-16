<div>
    <?php
    function offcanvasHighlightWords($text, $word){
        $perg1 = '$';
        $perg2 = '$i';
        $highlight = '<span>\\0</span>';
        $text = preg_replace($perg1. preg_quote($word) .$perg2, $highlight, $text);
        return $text;
    }
    ?>
    {{-- In work, do what you enjoy. --}}
    <input wire:model="offcanvas_query" type="text" name="searchTxt" placeholder="Search..."/>
    <button type="submit" class="bg-dark-silver"><i class="zmdi zmdi-search"></i></button>
    @if(!empty($offcanvas_query) && strlen($offcanvas_query) > 2)
        <div class="bg-white shadow mt-2" style="position: absolute; z-index: 999; width: 100%">
            @if(count($uploads) > 0)
                @foreach($uploads as $upload)
                    <a href="{{ route('landing.categories.show',[$upload->category->parentCategory->slug, $upload->category->slug, $upload->slug]) }}" title="{{ $upload->name }}">
                        <div class="border search-res-box pl-3 pr-3 pt-2 pb-2 d-flex align-items-center">
                            {{--<div class="search-res-img-div">
                                <img class="product-zoom-view-sub search-res-img" src="{{ asset('torrents/'.$upload->image) }}" alt="{{ $upload->name }}"/>
                            </div>--}}
                            <div class="search-res-txt">
                                {!! Str::limit(offcanvasHighlightWords($upload['name'], $offcanvas_query), 150) !!}
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="border {{--rounded-pill mt-1--}} search-res-box pl-3 pr-3 pt-2 pb-2 d-flex align-items-center">
                    No Results!
                </div>
            @endif
        </div>

    @endif
</div>
