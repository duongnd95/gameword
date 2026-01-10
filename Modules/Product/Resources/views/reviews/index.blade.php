@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::reviews.title') }}
@endsection
@section('contentheader_title')
{{ __('product::reviews.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li class="active">{{ __('product::reviews.title') }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/reviews', 'class' => 'pull-left', 'role' => 'search']) !!}
            <div class="input-group" style="width: 200px;">
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!}
           
        </div>
    </div>
    @php($index = ($reviews->currentPage()-1)*$reviews->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
            <tr>
                <th class="text-center" style="width: 3.5%;">{{ trans('message.index') }}</th>
                <th class="text-center" style="width: 2.5%;">
                    <input type="checkbox" name="chkAll" id="chkAll"/>
                </th>
                <th>{{ trans('product::reviews.product') }}</th>
                <th>{{ trans('product::reviews.rating') }}</th>
                <th>@sortablelink('name',trans('product::reviews.name'))</th>
                <th>{{ trans('product::reviews.review') }}</th>
                <th class="text-center">{{ trans('product::reviews.active') }}</th>
                <th>@sortablelink('updated_at',trans('theme::categories.updated_at'))</th>
                <th></th>
            </tr>
            @foreach($reviews as $item)
            <tr>
                <td class="text-center">{{ ++$index }}</td>
                <td class="text-center">
                    <input type="checkbox" name="chkId" id="chkId" value="{{ $item->id }}" data-id="{{ $item->id }}"/>
                </td>
                <td>{{ optional($item->product)->name }}</td>
                <td>
                @for ($i = 0; $i < $item->rating; $i++)
                  <i class="far fa-star text-yellow"></i>
                @endfor
                </td>   
                <td><span data-toggle="tooltip" data-placement="top" title="{{ $item->title }}">{{ $item->name }}</span></td>
                <td>{{ Str::limit($item->review, 50) }}</td>
                <td class="text-center">
                    {!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}
                </td>
                <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                <td style="display: flex">
                    @can('ReviewController@show')
                        {!! Form::open(['method' => 'GET', 'url' => '/admin/reviews/' . $item->id, 'class' => 'pd-2'])  !!}
                        <input type="hidden" name="back_url" value="{{ url()->full() }}">
                        {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                            'type' => 'submit',
                            'class' => 'btn btn-info btn-xs',
                            'title' => __('message.view')
                        )) !!}
                        {!! Form::close() !!}
                    @endcan
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix">
        <div class="row">
            <div id="btn-act" class="col-sm-6 text-left">
                @can('ReviewController@destroy')
                    <a href="#" data-action="deleteReview" class="btn-act btn btn-danger btn-sm" title="{{ __('message.delete') }}">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                @endcan
                @can('ReviewController@active')
                    <a href="#" data-action="activeReview" class="btn-act btn btn-primary btn-sm" title="{{ __('message.active') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="col-sm-6 text-right">
                {!! $reviews->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts-footer')
    <script type="text/javascript">
        $(function() {
            $('#chkAll').on('click', function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });

        $('#btn-act').on('click', '.btn-act', function(e){
            e.preventDefault();
            let action = $(this).data('action');
            ajaxListReview(action);
        });
        function ajaxListReview(action){
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '', successAlert = '', classAlert = '';
            switch (action) {
                case 'activeReview':
                    actTxt = 'duyệt';
                    successAlert = '{{ trans('product::reviews.updated_success') }}';
                    classAlert = 'alert-success';
                    break;
                case 'deleteReview':
                    actTxt = 'xóa';
                    successAlert = '{{ trans('product::reviews.deleted_success') }}';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0){
                let notificationConfirm = 'Bạn có muốn '+actTxt+' đánh giá này không?';
                let notification = confirm(notificationConfirm);
                if (notification){
                    var arrId = '';
                    $("input[name='chkId']:checked").map((val,key) => {
                        arrId += key.value + ',';
                    });
                    axios.get('{{url('/ajax')}}/'+action, {
                        params: {
                            ids: arrId
                        }
                    })
                        .then((response) => {
                            if (response.data.success === 'ok'){
                                $('#alert').html('<div class="alert '+classAlert+'">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                                    successAlert +
                                    ' </div>');
                                location.reload(true);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            }else{
                let notificationAlert = 'Vui lòng chọn đánh giá để '+actTxt+'!';
                alert(notificationAlert);
            }
        }
    </script>
@endsection