<script>
    $(function () {
        $("#list li").click(function (e) {
            e.preventDefault();
            var category_id = $(this).data('id');
            $('#category-js').attr('value', category_id);
            var order = $('select[name="order"] option:selected').val();
            filterAjaxProduct(category_id,order);
        });
        $('#list-filter').on('click', 'a.page-link', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let category_id = $('#category-js').val();
            var order = $('select[name="order"] option:selected').val();
            filterAjaxProduct(category_id,order,page);
        });
    });
    function filterAjaxCategory() {
        var order = $('select[name="order"] option:selected').val();
        var category_id = $('#category-js').val();
        filterAjaxProduct(category_id,order);
    }
    function filterAjaxProduct(id,order,page) {
        if(!page) page=1;
        $.ajax({
            type: 'GET',
            url: '{{ url('/product/ajax/filter') }}',
            data: {
                category_id: id,
                order: order,
                page: page
            },
            success: function (res) {
                $('html, body').animate({
                    scrollTop: $("#list-filter").offset().top-50
                });
                $('#list-filter').html(res);
            },
            error: function () {
                alert('Có lỗi xảy ra, vui lòng kiểm tra lại!');
            }
        });
    }
</script>