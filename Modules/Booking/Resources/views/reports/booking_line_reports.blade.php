@php( $select = array(\DB::raw('DATE(bookings.created_at) as date'), \DB::raw('COUNT(bookings.id) as number'),
                    \DB::raw('SUM(total_price) as total'), \DB::raw('COUNT(bookings.customer_id) as people')))
@php($groupBy = \DB::raw('DATE(bookings.created_at)'))
@php($bookings = Modules\Booking\Entities\Booking::byRole()->select($select))
@php($bookings = $bookings->groupBy($groupBy)->orderBy($groupBy)->get())
<table class="table table-bordered table-hover">
    <tbody>
    <tr>
        <th>Ngày</th>
        <th class="text-center">Số Đơn Hàng</th>
        <th class="text-center">Số Người Đặt Hàng</th>
        <th class="text-center">Doanh Thu</th>
    </tr>
    @foreach($bookings as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
            <td class="text-center">{{ $item->people }}</td>
            <td class="text-center">{{ $item->number }}</td>
            <td class="text-center">{{ number_format($item->total) }}
                &nbsp;{{ trans('theme::frontend.unit') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>