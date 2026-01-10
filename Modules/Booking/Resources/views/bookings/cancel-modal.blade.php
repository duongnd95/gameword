<div class="modal fade cancel-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        {!! Form::open([
                'method'=>'PUT',
                'url' => isset($action) ? $action : "",
                'style' => 'display:inline'
            ]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ __('booking::bookings.cancel') }}</h4>
            </div>
            <div class="modal-body">
                <textarea name="cancel_note" class="form-control" placeholder="{{ __('booking::bookings.cancel_note') }}"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('message.close') }}</button>
                {!! Form::button(__('booking::bookings.cancel'), array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger',
                    'title' => __('booking::bookings.cancel'),
                    'onclick'=>'return confirm("'.__('booking::bookings.confirm_cancel').'")'
            )) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>