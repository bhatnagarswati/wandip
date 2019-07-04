(function ($) {
  'use strict';


  //Avoid pinch zoom on iOS 
  document.addEventListener('touchmove', function (event) {
    if (event.scale !== 1) {
      event.preventDefault();
    }
  }, false);



  function customQuantity() {
    /** Custom Input number increment js **/
    jQuery(
      '<div class="pt_QuantityNav"><div class="pt_QuantityButton pt_QuantityUp">+</div><div class="pt_QuantityButton pt_QuantityDown">-</div></div>'
    ).insertAfter(".pt_Quantity input");
    jQuery(".pt_Quantity").each(function () {
      var spinner = jQuery(this),
        input = spinner.find('.form-control'),
        btnUp = spinner.find(".pt_QuantityUp"),
        btnDown = spinner.find(".pt_QuantityDown"),
        min = input.attr("min"),
        max = input.attr("max"),
        valOfAmout = input.val(),
        newVal = 0;

      btnUp.on("click", function () {
        var oldValue = parseFloat(input.val());

        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });
      btnDown.on("click", function () {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });
    });
  }
  customQuantity();

})(jQuery)


//owl 2 js
var owl = jQuery('.our_project_main .owl-carousel');
owl.owlCarousel({
  margin: 20,
  loop: false,
  nav: false,
  dots: true,
  autoplay: true,
  rewind: true,
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 3
    },

    1000: {
      items: 4
    }
  }
})


jQuery(window).on('scroll', function () {

  if (jQuery(window).scrollTop() >= 300) {
    jQuery('header').addClass('animated fadeInDown sticky');
    jQuery('header .navbar').addClass('fadeInDown');

  }
  else {
    jQuery('header').removeClass('animated fadeInDown sticky');
    jQuery('header .navbar').removeClass('fadeInDown sticky');

  }
});




new WOW().init();

AOS.init();


jQuery(document).ready(function () {
  jQuery(".search_btn_main").click(function (event) {
    event.preventDefault();
    jQuery(".search_pop").addClass("search_pop_open");
  });
  jQuery(".close_btn").click(function (event) {
    event.preventDefault();
    jQuery(".search_pop").removeClass("search_pop_open");
  });


  jQuery('.pop_up_main').click(function () {
    jQuery('pop_up_main').addClass('bounce');
  });

  jQuery('.product_sidebar_link .card .collapsed').click(function () {
    var self = jQuery(this);
    setTimeout(function () {
      jQuery('.product_sidebar_link .card .collapsed').removeClass('active');
      self.addClass('active');
    }, 100);
  });

  jQuery('.angle_btn').mouseenter(function () {
    jQuery('.angle_btn .fa.fa-angle-left').addClass('left_arrow');
    jQuery('.angle_btn .fa.fa-fighter-jet').addClass('left_jhaj');
  });
  jQuery('.angle_btn').mouseleave(function () {
    jQuery('.angle_btn .fa.fa-angle-left').removeClass('left_arrow');
    jQuery('.angle_btn .fa.fa-fighter-jet').removeClass('left_jhaj');
    jQuery('.angle_btn .fa.fa-fighter-jet').removeClass('left_jhaj_forwd');
  });

  jQuery('.angle_btn').on('focus', function () {
    jQuery('.angle_btn .fa.fa-fighter-jet').addClass('left_jhaj_forwd');
  });

  //var lemg = jQuery("body").find('.product_detait_product ');

  if (jQuery('.product_detail_slider').length > 0) {

    jQuery(function () {
      //SyntaxHighlighter.all();
    });

    jQuery(window).load(function () {
      jQuery('.product_detail_slider #carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 140,
        itemMargin: 5,
        asNavFor: '#slider'
      });

      jQuery('.product_detail_slider #slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
        start: function (slider) {
          jQuery('body').removeClass('loading');
        }
      });
    });
  }


});



var btn = jQuery('#top_arrow');

jQuery(window).scroll(function () {
  if (jQuery(window).scrollTop() > 500) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function (e) {
  e.preventDefault();
  jQuery('html, body').animate({ scrollTop: 0 }, '300');
});



jQuery(function () {
  jQuery('.select_country .selectpicker').selectpicker();
});


/* custom pop up*/

/*video stop pop*/

jQuery(".video_pop").on("hidden.bs.modal", function () {

  var _this = this,
    youtubeSrc = jQuery(_this).find("iframe").attr("src");

  if (jQuery(_this).find("iframe").length > 0) {                     // checking if there is iframe only then it will go to next level
    jQuery(_this).find("iframe").attr("src", "");                // removing src on runtime to stop video
    jQuery(_this).find("iframe").attr("src", youtubeSrc);        // again passing youtube src value to iframe
  }
});





jQuery('.firstCap').on('keypress', function (event) {
  var $this = jQuery(this),
    thisVal = $this.val(),
    FLC = thisVal.slice(0, 1).toUpperCase();
  con = thisVal.slice(1, thisVal.length);
  jQuery(this).val(FLC + con);
});


jQuery(window).on("load", function (e) {
  //mobileElements(); 
  jQuery('.mainloader').hide();


});

jQuery('#videoPly').on('click', function (ev) {

  jQuery("iframe")[0].src += "&autoplay=1";

  ev.preventDefault();

});





/*slicke slider*/


//Upload images 
jQuery(function () {
  jQuery(".user-img input:file").on('change', function () {
    var fileName = $(this).val();
    if (fileName.length > 0) {
      jQuery(this).parent().children('span').html(fileName);
    } else {
      jQuery(this).parent().children('span').html("Choose file");
    }
  });
  //file input preview
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        jQuery('.user-img img').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  jQuery('body').find(".user-img input[type='file']").on('change', function () {
    readURL(this);
  });
});



(function (jQuery) {
  jQuery(window).on("load", function () {
    var ChatBox = jQuery('.chat-box-sec');
    if (ChatBox.length > 0) {

      ChatBox.mCustomScrollbar({
        theme: "minimal",
      });
    };

    var ChatBox = jQuery('.inbox-list');
    if (ChatBox.length > 0) {
      ChatBox.mCustomScrollbar({
        theme: "minimal",
      });
    };
  });
})(jQuery);


// Submit customer  route request
function send_req() {

  var flag = true;

  var routeId = $('#create_req').attr('req-ro');
  var requested_address = $('#requested_address').val();
  var route_address = $('#route_address').val();
  var requested_qty = $('#requested_qty').val();

  if (requested_address == "") {
    $('#requested_address').addClass('route_error');
    flag = false;
  }
  if (requested_qty == "") {
    $('#requested_qty').addClass('route_error');
    flag = false;
  }
  if (route_address == "") {
    $('#route_address').addClass('route_error');
    flag = false;
  }

  var security_token = $('#_token').val();
  var base_url = $('#_base').val();

  if (flag == true) {

    $.ajax({
      method: "POST",
      dataType: "json",
      url: base_url + "/submit_route_req",
      data: { 'routeId': routeId, 'r_address': requested_address, 'req_route': route_address, 'requested_qty': requested_qty, '_token': security_token }
    })
      .done(function (res) {
        if (res.status == true) {
          $('#showResponse').html(res.message);

          setTimeout(function () {
            $('#showResponse').html("");
            $('#create_req').modal('hide');
            location.reload();
          }, 2000);

        } else {
          $('#showResponse').html(res.message);
          setTimeout(function () {
            $('#showResponse').html("");
            $('#create_req').modal('hide');
            location.reload();
          }, 2000);
          
        }
      });

  } else {
    return false;
  }

}


function cancel_req() {

  var security_token = $('#cancel_request #_token').val();
  var base_url = $('#cancel_request #_base').val();
  var routeId = $('#cancel_request').attr('req-ro');

  $.ajax({
    method: "POST",
    dataType: "json",
    url: base_url + "/cancel_route_req",
    data: { 'routeId': routeId, '_token': security_token }
  })
    .done(function (res) {
      if (res.status == true) {
        $('#showResponse').html(res.message);

        setTimeout(function () {
          $('#showResponse').html("");
          $('#cancel_request').modal('hide');
          location.reload();
        }, 3000);
      } else {
        $('#showResponse').html(res.message);
      }
    });

}


function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

// Function to chnage amount on product page

function calAmt() {

  if ($('#productAttribute :selected').val() != "") {
    $('#final_per_unit').hide();
    var psize = 0;
    if ($('#productAttribute')) {
      psize = $('#productAttribute :selected').text();
    }
    var amt = $('#pamt').val();
    var qty = $('#p_quantity').val();
    if (qty != null && qty != '') {
      qty = qty;
    } else {
      qty = 1;
    }
    var fprice = parseFloat(amt) * parseFloat(qty);
    if (psize != 0) {
      //console.log(parseFloat(psize));
      fprice = parseFloat(fprice) * parseFloat(psize);
    }
    var final_price = fprice.toFixed(2);
    //console.log(final_price);
    $('#final_amonut').html(final_price);
  } else {
    var amt = $('#pamt').val();
    $('#final_per_unit').show();
    $('#final_amonut').html(amt);
  }

}
 