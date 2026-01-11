<header>
    <div class="header__top container py-1">
        <div class="row">
            <div class="header__top__right col-md-12 d-flex align-items-center justify-content-end">
                <div class="header__top__right__search">
                    <form method="GET" action="{{ route('tim-kiem') }}" class="search-form">
                        <input type="text" name="query" placeholder="Tìm kiếm...">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <ul class="list-inline-block d-flex align-items-center">
                    <a target="_blank" rel="nofollow" href="{!! $settings['follow_facebook']!!}"><i
                                class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a target="_blank" rel="nofollow" href="{!! $settings['follow_twitter'] !!}"><i
                                class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a target="_blank" rel="nofollow" href="{!! $settings['follow_instagram'] !!}"><i
                                class="fab fa-instagram" aria-hidden="true"></i></a>
                    <a target="_blank" rel="nofollow" href="{!! $settings['follow_youtube'] !!}">
                        <i class="fab fa-youtube" aria-hidden="true"></i>
                    </a>
                </ul>
            </div>
        </div>
    </div>
    <div class="header__logo py-2">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <a class="header__logo--link" href="{{ url('/') }}">
                        <img src="{{ url($settings['company_logo']) }}" alt="" class="img-fluid lazyloaded"
                             width="100px" height="80px">
                    </a>
                </div>
                <div class="col-6 col-md-9">
                    <a class="sidebarBtn d-block d-xl-none">
                        <i class="sidebar-icon fas fa-bars"></i>
                    </a>
                    <div class="header__menu">
                        <ul class="list-inline-block clearfix d-none d-xl-block">
                            <li class="active"><a
                                        href="{{ url('trang-chu') }}"
                                        class="hidden-sm hidden-xs"><i
                                            class="fa fa-home"></i></a></li>
                            @forelse($mainMenus as $item)
                                @php($menuChild = \App\Category::with('parent')->where('parent_id', $item->id)->get())
                                @if($item->parent_id == null)
                                    <li class="{{ $menuChild->count() > 0 ? 'pn-dropdown' : '' }} {{ (Request::segment(1) == $item->slug) ? 'active' : '' }}">
                                        <a class="text__white"
                                           href="{{ url($item->slug) }}{{ $item->type_id == 0 ? '' : '.html' }}">{{ $item->title }}</a>
                                        {!! $menuChild->count() > 0 ? '<span class="fa fa-angle-down"></span>' : ''  !!}
                                        @if($menuChild->count() > 0)
                                            <ul class="list-inline-block">
                                                @foreach($menuChild as $itemChild)
                                                    @php($menuChildren = \App\Menu::with('parent')->where('parent_id', $itemChild->id)->get())
                                                    <li>
                                                        <a class="item-child"
                                                           href="{{ url($item->slug . '/' .$itemChild->slug) }}{{ $itemChild->type_id == 0 ? '' : '.html' }}">{{ $itemChild->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    <div class="sidebar">
                        <div class="d-flex justify-content-end mr-2">
                            <a class="sidebarBtn-close" style="top:25px; right:21px;">
                                <i class="sidebar-icon fas fa-times"></i>
                            </a>
                        </div>
                        <ul class="group-item">
                            @forelse($mainMenus as $item)
                                @if($item->parent_id == null)
                                    @php($menuChild = \App\Category::with('parent')->where('parent_id', $item->id)->get())
                                    <li class="nav-item">
                                        @if($menuChild->count() > 0)
                                            <a class="dropdown-btn">{{ $item->title }} <i
                                                        class="fa fa-angle-down dropdown-icon"></i>
                                            </a>
                                            <div class="dropdown-container">
                                                @foreach($menuChild as $itemChild)
                                                    <a class="dropdown-item"
                                                       href="{{ url($item->slug . '/' .$itemChild->slug) }}{{ $itemChild->type_id == 0 ? '' : '.html' }}">
                                                        {{ $itemChild->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <a class="nav-link"
                                               href="{{ url($item->slug) }}{{ $item->type_id == 0 ? '' : '.html' }}">{{ $item->title }}</a>
                                        @endif
                                    </li>
                                @endif

                            @empty
                            @endforelse
                        </ul>


                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
