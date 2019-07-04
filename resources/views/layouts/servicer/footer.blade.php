<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>{{ __('dashboard.app_version_text') }}</b> {{  __('dashboard.app_version_val') }}
    </div>
    <strong>{{  __('dashboard.copyright_text') }} {{ date('Y') }} - {{ date('Y') + 1 }} <a
            href="{{config('app.url')}}">{{config('app.name')}}</a>.</strong> {{ __('dashboard.copy_right') }}
</footer>
<?php
$servicerId = Auth::guard('servicer')->user()->id;
$stripeConnectId = Auth::guard('servicer')->user()->stripeConnectId;
if(empty($stripeConnectId)){
?>
@section('js')
<script>
$('#stripeConnect').modal({
    backdrop: 'static',
    //  keyboard: false
});
</script>
@endsection
<?php
}
?>

<!-- Modal -->
<div class="modal fade" id="stripeConnect" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style='border:none;'>
            </div>
            <div class="modal-body text-center">
                <h4>{{ __('dashboard.stipe_message_for_service_provider')  }} </h4>
                <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id={{ env('STRIPE_CLIENTID') }}&state={{ $servicerId }}&scope=read_write">
                 <img src="{{ url('/' )}}/public/images/blue-on-light.png">
                </a>
            </div>
            <div class="modal-footer" style='border:none;'>

            </div>
        </div>
    </div>
</div>