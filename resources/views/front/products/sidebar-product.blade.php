<div class="sidebar_title product_sidebar_bx  product_sidebar_title">
    <h3>{{  __('common.product_sidebar_filter') }}</h3>
</div>
<form method='post' action="{{ url('/shop') }}">

    <div class="product_sidebar_bx product_price_bx">
        <h4>{{  __('common.product_sidebar_byprice') }}</h4>
        <!-- <img src="{{ asset('public/images/range.png')}}" alt="range.png" /> -->
        <div id="rangeslider" class="range-slider-main"></div>
        <input type="hidden" name="minval" id="minval" value='{{ @$minprice }}'>
        <input type="hidden" name="maxval" id="maxval" value='{{ @$maxprice }}'>
    </div>

    <div class="product_sidebar_bx product_type">
        <h4>{{  __('common.product_sidebar_bycategory') }}</h4>

        <div class="product_type_checkbox checkbox_outer">

            @foreach($categories as $category)
            <div class="custom_checkbox">
                <label><input name='bycategories[]' @php if(!empty($bycategories)){ if(in_array($category->id ,
                    $bycategories)){ echo "checked";} } @endphp type="checkbox" value='{{ $category->id }}' class="category"/><span
                        class="check_text"></span>{{ $category->name }}</label>
            </div>

            @endforeach
        </div>
    </div>


    <div class="product_sidebar_bx product_brand_bx">
        <h4>{{  __('common.product_sidebar_bybrands') }}</h4>
        <div class="product_sidebar_list">
            @if(!empty($brands))
            @foreach($brands as $brand)
            <div class="custom_checkbox">
                <label><input name='bybrands[]' @php if(!empty($bybrands)){ if(in_array($brand->id ,
                    $bybrands)){ echo "checked";} } @endphp type="checkbox" value='{{ $brand->id }}' class="brand" /><span
                        class="check_text"></span>{{ $brand->name }}</label>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="product_sidebar_bx product_service_bx">
        <h4>{{  __('common.product_sidebar_byservice') }}</h4>
        <div class="product_type_checkbox checkbox_outer">
            <div class="custom_checkbox">
                <label><input type="checkbox" @php if(!empty($byservice)){ if(in_array('home_delivery' ,
                    $byservice)){ echo "checked";} } @endphp name='byservice[]' value="home_delivery" class="service"/><span
                        class="check_text"></span>{{  __('common.product_sidebar_byservice_home') }}</label>
            </div>
            <div class="custom_checkbox">
                <label><input type="checkbox" @php if(!empty($byservice)){ if(in_array('pick_up' ,
                    $byservice)){ echo "checked";} } @endphp name='byservice[]' value="pick_up" class="service" /><span
                        class="check_text"></span>{{  __('common.product_sidebar_byservice_pick') }}</label>
            </div>
        </div>
        <br />
        <input type="hidden" id="hd_shop_sort_filter" name='shop_sort_filter' value="@php if(!empty($sortOrder)){ echo $sortOrder; }else{ echo 'sort_asc'; } @endphp">
        <input type="submit" name='filter_search' id='filter_btn' value="Filter Products" class="btn blue-btn creative_btn">
    </div>
    {{ csrf_field() }}
</form>

@section('js')

 
<script>
$('#shop_sort_filter').on('change', function() {
    $('#hd_shop_sort_filter').val($(this).val());
    $('#filter_btn').trigger('click');

});

/****** Range slider on product page */
// range slider

function collision($div1, $div2) {
  var x1 = $div1.offset().left;
  var w1 = 40;
  var r1 = x1 + w1;
  var x2 = $div2.offset().left;
  var w2 = 40;
  var r2 = x2 + w2;

  if (r1 < x2 || x1 > r2)
      return false;
  return true;
}
// Fetch Url value 
var getQueryString = function (parameter) {
  var href = window.location.href;
  var reg = new RegExp('[?&]' + parameter + '=([^&#]*)', 'i');
  var string = reg.exec(href);
  return string ? string[1] : null;
};



// // slider call
$('#rangeslider').slider({
  range: true,
  min: 1,
  max: 10000,
  step: 1,
  values: [getQueryString('minval') ? getQueryString('minval') : {{ @$minprice }} , getQueryString('maxval') ? getQueryString('maxval') :{{ @$maxprice }}],

  slide: function (event, ui) {

      $('.ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[ 0 ]);
      $('.ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[ 1 ]);
      $('.price-range-both').html('<i>$' + ui.values[ 0 ] + ' - </i>$' + ui.values[ 1 ]);

      // get values of min and max
      $("#minval").val(ui.values[0]);
      $("#maxval").val(ui.values[1]);
      
      if (ui.values[0] == ui.values[1]) {
          $('.price-range-both i').css('display', 'none');
      } else {
          $('.price-range-both i').css('display', 'inline');
      }

      if (collision($('.price-range-min'), $('.price-range-max')) == true) {
          $('.price-range-min, .price-range-max').css('opacity', '0');
          $('.price-range-both').css('display', 'block');
      } else {
          $('.price-range-min, .price-range-max').css('opacity', '1');
          $('.price-range-both').css('display', 'none');
      }

  },
    change: function(event, ui) {
    //console.log('dd');
    var minval = $('#minval').val();
    var maxval = $('#maxval').val();
    var category = [];
    $.each($(".category:checked"), function(){
        category.push($(this).val());
    });
    var brand = [];
    $.each($(".brand:checked"), function(){
        brand.push($(this).val());
    });
    var service = [];
    $.each($(".service:checked"), function(){
        service.push($(this).val());
    });
    //console.log(minval);
    //console.log(maxval);
    // console.log(category);
    // console.log(brand);
    // console.log(service);
    filterAjax(minval, maxval, category, brand, service);

  }

});

$('.ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#rangeslider').slider('values', 0) + ' - </i>' + $('#rangeslider').slider('values', 1) + '</span>');

$('.ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#rangeslider').slider('values', 0) + '</span>');

$('.ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#rangeslider').slider('values', 1) + '</span>');


$('.category').click(function () {
    //console.log('dd');
    var minval = $('#minval').val();
    var maxval = $('#maxval').val();
    var category = [];
    $.each($(".category:checked"), function(){
        category.push($(this).val());
    });
    var brand = [];
    $.each($(".brand:checked"), function(){
        brand.push($(this).val());
    });
    var service = [];
    $.each($(".service:checked"), function(){
        service.push($(this).val());
    });
    filterAjax(minval, maxval, category, brand, service);
});
$('.brand').click(function () {
    var minval = $('#minval').val();
    var maxval = $('#maxval').val();
    var category = [];
    $.each($(".category:checked"), function(){
        category.push($(this).val());
    });
    var brand = [];
    $.each($(".brand:checked"), function(){
        brand.push($(this).val());
    });
    var service = [];
    $.each($(".service:checked"), function(){
        service.push($(this).val());
    });

    filterAjax(minval, maxval, category, brand, service);
});
$('.service').click(function () {
    var minval = $('#minval').val();
    var maxval = $('#maxval').val();
    var category = [];
    $.each($(".category:checked"), function(){
        category.push($(this).val());
    });
    var brand = [];
    $.each($(".brand:checked"), function(){
        brand.push($(this).val());
    });
    var service = [];
    $.each($(".service:checked"), function(){
        service.push($(this).val());
    });
    filterAjax(minval, maxval, category, brand, service);
});

function filterAjax(minval, maxval, category, brand, service){
    $.ajax({
        type:"POST",        
        url: '{{ url('/shop') }}',
        data:{minval:minval,maxval:maxval,bycategories:category,bybrands:brand,byservice:service,shop_sort_filter:"sort_asc",filter_search:"Filter Products","_token": "{{ csrf_token() }}",},    // multiple data sent using ajax
        success: function (data) {
            //console.log(data.html);
            $('.products_list_outer').html(data.html);
            $('.product_count p').html(data.product_count+' Product');
          }
    });
    return false;
}

</script>

@endsection