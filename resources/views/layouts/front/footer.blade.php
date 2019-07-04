  <!--footer-->
  <footer class="section">
    <div class="footer_top">
      <div class="container">
        <div class="row">
          <div class="col-md-5">
            <div class="footer_bx footer_about">
              <a href="javascript::void(0)" class="footer_logo"><img src="{{ asset('public/images/logo.png') }}" alt="footer_logo.png" /></a>

              <div class="footer_newsletter">
                <h3>{{ __('common.footer_newsletter') }}</h3>
                 @include('vendor.mailchimp.mailchimp')
              </div>
              <div class="footer_sm">
                <h3>{{ __('common.footer_connect_with_us') }}</h3>
                <ul>
                  <li><a href="https://www.facebook.com" target="_blank" class="sm_icon facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                  <li><a href="https://www.twitter.com" target="_blank"  class="sm_icon twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                  <li><a href="https://www.linkedin.com" target="_blank"  class="sm_icon linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                  <li><a href="https://www.google.com" target="_blank"  class="sm_icon google_plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="footer_bx footer_link">
              <h3>{{ __('common.footer_quicklink') }}</h3>
              <ul>
                <li><a href="{{ url('/home') }}">{{ __('common.footer_home') }}</a></li>
                <li><a href="{{ url('/about-us') }}">{{ __('common.footer_about_us') }}</a></li>
                <li><a href="{{ url('/stations') }}">{{ __('common.footer_stations') }} </a></li>
                <li><a href="{{ url('/shop') }}">{{ __('common.footer_products') }}</a></li>
                <li><a href="{{ url('/blogs') }}">{{ __('common.footer_blog') }}</a></li>
                <li><a href="{{ url('/contact-us') }}">{{ __('common.footer_contact_us') }}</a></li>
                <li><a href="{{ route('accounts') }}">{{ __('common.footer_your_acc') }}</a>  </li>
              </ul>
            </div>
          </div>
          <div class="col-md-3">
            <div class="footer_bx footer_contact">
              <h3>{{ __('common.footer_contact_label') }}</h3>
              <ul>
                <li><span class="fa_icons"><i class="fa fa-phone" aria-hidden="true"></i></span><a href="tel:1234567890">(123) 456 - 7890</a></li>
                <li><span class="fa_icons"><i class="fa fa-envelope-o" aria-hidden="true"></i></span><a href="info@adblue/def.com">info@adblue.com</a></li>
                <li><span class="fa_icons"><i class="fa fa-map-marker" aria-hidden="true"></i></span>Lorem ipsum dolor
                  11 Lorem ipsum, VIC 3131</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright_main">
      <div class="container">
        <div class="copyright_lt">
          <p>Copyright © {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
        <div class="copyright_rt ">
          <p>Powered By -<a href="https://www.imarkinfotech.com/" target="_blank" class="imark">iMark Infotech</a></p>
        </div>
      </div>
    </div>
  </footer>
