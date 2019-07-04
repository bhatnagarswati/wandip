<!-- <section class="subscribe-section t100"> -->
    @if(session()->has('message'))
    <div class="box no-border">
        <div class="box-tools">
            <p class="alert alert-success alert-dismissible">
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </p>
        </div>
    </div>
    @elseif(session()->has('error'))
    <div class="box no-border">
        <div class="box-tools">
            <p class="alert alert-danger alert-dismissible">
                {{ session()->get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </p>
        </div>
    </div>
    @endif

    <form action="{{route('mailchimp.store')}}" method="post">
    <div class="newleter_form">
       
            {{csrf_field()}}
            <input type="email" name="email"  required ='required' class="newsletter-input form-control" placeholder="{{ __('common.footer_textbox_placeholder') }}"
                value="">
            <button type="submit" class="btn blue-btn">{{ __('common.footer_subscribe_btn') }}</button>
       
    </div>
    </form>

<!-- </section> -->