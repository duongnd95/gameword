<div class="col-xl-2 col-lg-3 col-md-5 col-sm-4 col-12 filter-product">
    <select name="order" class="select-filter" onchange="filterAjaxCategory()" style="width: 100%;">
        <option value="date" {{ Request::get('order') == 'date'? "selected" : "" }}>
            Mới nhất
        </option>
        <option value="price" {{ Request::get('order') == 'price'? "selected" : "" }}>
            Giá thấp đến cao
        </option>
        <option value="price-desc" {{ Request::get('order') == 'price-desc'? "selected" : "" }}>
            Giá cao xuống thấp
        </option>
    </select>
    <input type="hidden" name="category_id" id="category-js" value="{{ isset($category) ? $category->id : '' }}">
</div>