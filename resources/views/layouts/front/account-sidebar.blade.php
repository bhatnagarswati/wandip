<div class="dashboard_sidebar_main">
    <div class="dashboard_sidebar dashboard_sidebar_profile white_bg">
        <div class="user-img">
            <div class="logoContainer">
                <figure>

                @php 

                    if(!empty($customer->profilePic)){
                        $profilepic =  config('constants.customer_pull_path').$customer->profilePic;
                    }else{
                        $profilepic = url('/')."/public/img/no-image.png";
                    }
                @endphp



                    <img src="{{  $profilepic  }}">
                </figure>
            </div>
            
        </div>
        <div class="profile_name">
            <p>{{ __('common.ac_page_hello')  }},</p>
            <h4>{{$customer->name}}</h4>
        </div>
    </div>

    <div class="dashboard_sidebar_nav sidebar_nav white_bg">
        <ul>
            <li><span class="svg_icon payment-icon"><img src="{{ asset('public/images/paymentl-icon.png') }}"
                        alt="paymentl-icon.png" /></span><a @if(request()->segment(1) == 'accounts' || request()->segment(2) == '' ) class='active' @endif
                    href="{{ url('/accounts')  }}">{{ __('common.ac_page_dashboard')  }}</a></li>
            <li><span class="svg_icon menu-icon"><img src="{{ asset('public/images/menu-icon.png') }}"
                        alt="menu-icon.png" /></span><a @if(request()->segment(2) == 'orders') class='active' @endif
                    href="{{ url('/accounts/orders')  }}">{{ __('common.ac_page_odrers')  }}</a></li>
            <li><span class="svg_icon  bell-icon"><img src="{{ asset('public/images/bell-icon.png') }}"
                        alt="bell-icon.png" /></span><a href="javascript::void(0)">{{ __('common.ac_page_notifications')  }}</a></li>
            <li><span class="svg_icon chat-icon"><img src="{{ asset('public/images/chat-icon.png') }}"
                        alt="chat-icon.png" /></span><a href="#">{{ __('common.ac_page_inbox')  }}</a></li>
            <li><span class="svg_icon folder-icon"><img src="{{ asset('public/images/folderl-icon.png') }}"
                        alt="folderl-icon.png" /></span><a @if(request()->segment(2) == 'requests') class='active' @endif
                    href="{{ url('/accounts/requests')  }}">{{ __('common.ac_page_requests')  }}</a></li>
            <li><span class="svg_icon payment-icon"><img src="{{ asset('public/images/paymentl-icon.png') }}"
                        alt="paymentl-icon.png" /></span><a  @if(request()->segment(2) == 'payments') class='active' @endif
                    href="{{ url('/accounts/payments')  }}">{{ __('common.ac_page_payments')  }}</a></li>
            <li><span class="svg_icon map-icon"><img src="{{ asset('public/images/mapl-icon.png') }}"
                        alt="mapl-icon.png" /></span><a  @if(request()->segment(2) == 'addresses') class='active' @endif
                    href="{{ url('/accounts/addresses')  }}">{{ __('common.ac_page_addresses')  }}</a></li>
            <li><span class="svg_icon logout-icon"><img src="{{ asset('public/images/logout-icon.png') }}"
                        alt="logout-icon.png" /></span><a
                    href="{{ url('/logout')  }}">{{ __('common.ac_page_logout')  }}</a></li>
        </ul>
    </div>
</div>