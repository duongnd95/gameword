@section('scripts-footer')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/css/fileinput.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/css/fileinput-rtl.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/js/fileinput.min.js"></script>
    <script>
        function showModalStatus(idApprove, idBooking) {
            $('.btn-status' + idBooking).prepend('<i class="fas fa-spinner fa-pulse"></fa>');
            axios.get('/bookings/ajax/getModalStatus')
                .then(function (res) {
                    $('#btn-status' + idBooking + ' .fas').remove();
                    var data = res.data.data;
                    let html = '';
                    $.each(data, function (index, val) {
                        var active = val.id == idApprove ? 'selected' : '';
                        html += '<option data-color="'+val.color+'" value="'+val.id+'" '+active+' >'+val.name+'</option>';
                    });
                    $('#get-status').append(
                        '<div id="modal-status-js" class="modal fade">'
                        + '<div class="modal-dialog" role="document">'
                        + '<div class="modal-content">'
                        + '<div class="modal-header">'
                        + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                        + '<h4 class="modal-title" id="myModalLabel">'+$('#txt-approve').val()+'</h4>'
                        + '</div>'
                        + '<div class="modal-body">'
                        + '<select name="approve" class="form-control" id="drp-approve">'
                        + html
                        + '</select>'
                        + '<input type="hidden" value="'+idBooking+'" id="booking_id" />'
                        + '</div>'
                        + '<div class="modal-footer">'
                        + '<button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>'
                        + '<button  type="button" class="btn btn-primary btn-change-status" > Cập nhật</button>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                    );
                    $('#modal-status-js').modal('show').on('hide.bs.modal', function () {
                        $('#modal-status-js').remove();
                    });
                })
                .catch(function () {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
        }
        $('#get-status').on('click', '.btn-change-status', function (e) {
            e.preventDefault();
            $(this).prepend('<i class="fas fa-spinner fa-pulse"></fa>');
            let idBooking = $("#booking_id").val();
            let approve = $("#drp-approve").val();
            let name = $('#drp-approve :selected').text();
            let color = $('#drp-approve :selected').data('color');
            $.ajax({
                type: "PUT",
                url: "{{ url('/bookings/ajaxStatus/updateModalStatus/') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': idBooking,
                    'approve_id': approve
                },
                success: function(res) {
                    if (res.success) {
                        $('.btn-status' + idBooking).remove();
                        $('#btn-status'+ idBooking).append('<button type="button" class="label btn btn-status'+idBooking+'" style="background-color: '+color+'"\n' +
                            'onclick="showModalStatus('+approve+','+idBooking+')">'+name+'</button>');
                        $('#modal-status-js').modal('hide');
                    }
                },
                error: function () {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            });
        });
        $("#input-b6").fileinput({
            showUpload: false,
            dropZoneEnabled: false,
            maxFileCount: 1,
            mainClass: "input-group-sm",
            elErrorContainer: '#kartik-file-errors',
            allowedFileExtensions: ["xlsx", "xls", "csv"]
        });

    </script>
@endsection