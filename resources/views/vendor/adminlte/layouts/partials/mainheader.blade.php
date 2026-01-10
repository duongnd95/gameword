<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            {{--{!! Config("settings.app_logo_mini") !!}--}}
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{!! Config("settings.app_logo") !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}">{{ trans('adminlte_lang::message.register') }}</a></li>
                    <li><a href="{{ url('/login') }}">{{ trans('adminlte_lang::message.login') }}</a></li>
                @else
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu" id="user_menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                        {!! Auth::user()->showAvatar(["class"=>"user-image"], asset(config('settings.avatar_default'))) !!}
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                {!! Auth::user()->showAvatar(["class"=>"img-circle"], asset(config('settings.avatar_default'))) !!}
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>{{ Auth::user()->email }}</small>
                                    <small>{{ Auth::user()->agent_id ? optional(Auth::user()->agent)->name : (Auth::user()->company_id ? optional(Auth::user()->company)->name : '') }}</small>
                                    @foreach(Auth::user()->roles as $role)
                                        <span class="badge label-danger">{{ $role->label }}</span>
                                    @endforeach
                                    <br>
                                </p>
                            </li>
                            <li class="user-footer">
                                @can('UsersController@postProfile')
                                <div class="pull-left">
                                    <a href="{{ url('/profile') }}" class="btn btn-sm btn-info"><i class="fa fa-user-o"></i> {{ trans('adminlte_lang::message.profile') }}</a>
                                </div>
                                @endcan
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" id="logout" class="btn btn-sm btn-info"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> {{ trans('adminlte_lang::message.signout') }}
                                    </a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="submit" value="logout" >
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>
