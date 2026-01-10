<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                {!! Auth::user()->showAvatar(["class"=>"img-circle"], asset(config('settings.avatar_default'))) !!}
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <i class="fa fa-circle text-success"></i> Online
            </div>
        </div>
        <form  method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
    @php
        $arLink = [
            [
                'icon' => 'fas fa-tachometer-alt',
                'title' => __(' Trang chủ'),
                'href' => 'admin',
            ],
            [
                'icon' => 'fa fa-th',
                'title' => __('Hệ thống'),
                'child' => [
                    [
                        'icon' => 'fas fa-cog',
                        'title' => __(' Cấu hình'),
                        'href' => 'admin/settings',
                        'permission' => 'SettingController'
                    ],
                    [
                        'icon' => 'fas fa-user',
                        'title' => __(' Tài khoản'),
                        'href' => 'admin/users',
                        'permission' => 'UsersController@index'
                    ],
                    [
                        'icon' => 'fas fa-user-lock',
                        'title' => __(' Vai trò'),
                        'href' => 'admin/roles',
                        'permission' => 'RolesController@index'
                    ],

                ]
            ],
            [
                'icon' => 'fa fa-book',
                'title' => __('Bài viết'),
                'child' => [
                    [
                        'icon' => 'fas fa-caret-right',
                        'title' => __(' Tin tức'),
                        'href' => 'admin/news',
                        'permission' => 'NewsController@index'
                    ],
                    [
                        'icon' => 'fas fa-caret-right',
                        'title' => __(' Danh mục'),
                        'href' => 'admin/categories',
                        'permission' => 'CategoryController@index'
                    ]
                ]
            ],
            [
                'icon' => 'fas fa-paint-brush',
                'title' => __(' Giao diện'),
                'child' => [
                
                    [
                        'icon' => 'fas fa-caret-right',
                        'title' => __(' Slider ảnh'),
                        'href' => 'admin/sliders',
                        'permission' => 'SliderController@index'
                    ]
                ]
            ]
        ];
    @endphp
    {{ Menu::sidebar(Auth::user(), $arLink) }}
    </section>
</aside>
