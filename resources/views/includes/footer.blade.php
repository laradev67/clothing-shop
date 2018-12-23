<footer>
    <div class="container">
        <div class="row">

            {{-- Navigation --}}
            <nav class="col-6 col-md-3">
                <h4>@lang('navigation.menu')</h4>
                <ul>
                    <li><a href="/">@lang('navigation.home')</a></li>
                    <li><a href="/items?category=men">@lang('navigation.men')</a></li>
                    <li><a href="/items?category=women">@lang('navigation.women')</a></li>
                    <li><a href="/search">@lang('navigation.search')</a></li>
                </ul>
            </nav>

            {{-- Last items --}}
            @isset($last_items_for_footer)
                <nav class="col-6 col-md-3">
                    <h4>@lang('navigation.last_posts')</h4>
                    <ul>
                        @foreach ($last_items_for_footer as $item)
                            <li>
                                <a href="/item/{{ $item->category }}/{{ $item->id }}"
                                    title="{{ $item->title }}"
                                >
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endisset

            {{-- Categories women --}}
            @isset($categories_women)
                <nav class="col-6 col-md-3">
                    <h4>@lang('navigation.women')</h4>
                    <ul>
                        @foreach ($categories_women as $item)
                        <li>
                            <a href="/items?category={{ $item['category'] }}&type={{ $item['type_id'] }}" 
                                title="{{ $item['type_id'] }}"
                            >
                                {{ $item['type']['name'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
            @endisset

            {{-- Categories men --}}
            @isset($categories_men)
                <nav class="col-6 col-md-3">
                    <h4>@lang('navigation.men')</h4>
                    <ul>
                        @foreach ($categories_men as $item)
                        <li>
                            <a href="/items?category={{ $item['category'] }}&type={{ $item['type_id'] }}" 
                                title="{{ $item['type']['name'] }}"
                            >
                                {{ $item['type']['name'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
            @endisset
        </div>
        <div class="row">
            <h6 class="col-12 pt-5 text-center copyright">
                {{ date('Y') }} &copy; {{ config('app.name') }}
            </h6>
        </div>
    </div>
</footer>