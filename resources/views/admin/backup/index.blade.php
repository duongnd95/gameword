@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ trans('backup.backup') }}
@endsection
@section('contentheader_title')
    {{ trans('backup.backup') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-home"></i> Trang chủ</a></li>
        <li class="active">{{ trans('backup.backup') }}</li>
    </ol>
@endsection

@section('main-content')
    <!-- Default box -->
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ trans('backup.existing_backups') }}</h3>
            <div class="box-tools">
                <label for="only-db">{{ trans('backup.only_db') }}</label>
                <input type="checkbox" name="only-db" id="only-db" checked value="1"/>
                <button id="create-new-backup-button" href="{{ url('admin/backup/create') }}" class="btn btn-success ladda-button btn-sm" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> <span class="hidden-xs">{{ trans('backup.create_a_new_backup') }}</span></span></button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>#</th>
                    <th>{{ trans('backup.location') }}</th>
                    <th>{{ trans('backup.date') }}</th>
                    <th class="text-right">{{ trans('backup.file_size') }}</th>
                    <th class="text-right">{{ trans('backup.actions') }}</th>
                </tr>
                @foreach ($backups as $k => $b)
                    <tr>
                        <th scope="row">{{ $k+1 }}</th>
                        <td>{{ $b['disk'] }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}</td>
                        <td class="text-right">{{ round((int)$b['file_size']/1048576, 2).' MB' }}</td>
                        <td class="text-right">
                            @if ($b['download'])
                                <a class="btn btn-xs btn-default" href="{{ url('admin/backup/download/') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="fa fa-cloud-download"></i> {{ trans('backup.download') }}</a>
                            @endif
                            <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ url('admin/backup/delete/'.$b['file_name']) }}?disk={{ $b['disk'] }}"><i class="fa fa-trash-o"></i> {{ trans('backup.delete') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

@endsection

@section('scripts-footer')
    <link href="{{ asset('plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ladda Buttons (loading buttons) -->
    <script src="{{ asset('plugins/ladda/spin.min.js') }}"></script>
    <script src="{{ asset('plugins/ladda/ladda.min.js') }}"></script>

    <script>
        jQuery(document).ready(function($) {
            // capture the Create new backup button
            $("#create-new-backup-button").click(function(e) {
                e.preventDefault();
                var create_backup_url = $(this).attr('href');
                // Create a new instance of ladda for the specified button
                var l = Ladda.create( document.querySelector( '#create-new-backup-button' ) );

                // Start loading
                l.start();

                // Will display a progress bar for 10% of the button width
                l.setProgress( 0.3 );

                setTimeout(function(){ l.setProgress( 0.6 ); }, 2000);
                // do the backup through ajax
                axios({
                    method:'put',
                    url:create_backup_url,
                    data: {only_db: $('#only-db').is(":checked")?1:0},
                    responseType:'html'
                })
                    .then(function (result) {
                        l.setProgress( 0.9 );
                        // Show an alert with the result
                        if (result.data.indexOf('failed') >= 0) {
                            alert("{{ trans('backup.create_warning_message') }}");
                        }
                        else
                        {
                            alert("{{ trans('backup.create_confirmation_message') }}");
                        }

                        // Stop loading
                        l.setProgress( 1 );
                        l.stop();

                        // refresh the page to show the new file
                        setTimeout(function(){ location.reload(); }, 1000);
                    })
                    .catch(function (error) {
                        l.setProgress( 0.9 );
                        // Show an alert with the result
                        alert("{{ trans('backup.create_error_message') }}");
                        // Stop loading
                        l.stop();
                    });
            });

            // capture the delete button
            $("[data-button-type=delete]").click(function(e) {
                e.preventDefault();
                var delete_button = $(this);
                var delete_url = $(this).attr('href');

                if (confirm("{{ trans('backup.delete_confirm') }}") == true) {
                    axios({
                        method:'delete',
                        url:delete_url,
                        responseType:'text'
                    })
                        .then(function (result) {
                            // Show an alert with the result
                            alert("{{ trans('backup.delete_confirmation_message') }}");
                            // delete the row from the table
                            delete_button.parentsUntil('tr').parent().remove();
                        })
                        .catch(function (error) {
                            // Show an alert with the result
                            alert("{{ trans('backup.delete_error_message') }}");
                        });
                } else {
                    alert("{{ trans('backup.delete_cancel_message') }}");
                }
            });

        });
    </script>
@endsection
