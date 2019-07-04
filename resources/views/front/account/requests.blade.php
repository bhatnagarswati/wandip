@extends('layouts.front.app')

@section('content')

<section class="dashboard_main rating_review_main cart-grey">
     <div class="container">
         <div class="dashboard_main_row">
             
            @include('layouts.front.account-sidebar')

           <!---- right panel start --->
           <div class="dashboard_content_main">
                <div class="box-body">
                      @include('layouts.errors-and-messages')
                  </div>  

                     <div class="request_main comn_height">
                      <ul class="nav nav_tabs" id="myTab" role="tablist">
                          <li>
                            <a class="nav_link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('common.requests_page_pending_tab') }}</a>
                          </li>
                          <li>
                            <a class="nav_link"  id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('common.requests_page_delivered_tab') }}</a>
                          </li>
                        </ul>
                         <div class="white_bg request_content">
                          <div class="col_sm_12 col_xl_10 ">
                            <div class="tab-content " id="myTabContent">
                              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                   <div class="request_bx_main">

                                    <!--- Pending Reques start --->

                                    @if(!empty($pending_requests))
                                            @foreach($pending_requests as $pending)
                                            
                                                <div class="request_list_bx">
                                                    <div class="request_content_inn">
                                                        <div class="request_content_row">
                                                            <div class="request_title">
                                                                <p><strong>{{  __('common.requests_page_driver_name') }}</strong></p>
                                                            </div>
                                                            <div class="request_text">
                                                                <p>{{  @$pending->driverinfo->firstName }} {{  @$pending->driverinfo->lastName }}</p>
                                                                </div>
                                                        </div>
                                                            <div class="request_content_row">
                                                            <div class="request_title">
                                                                <p><strong>{{  __('common.requests_page_address') }}</strong></p>
                                                            </div>
                                                            <div class="request_text">
                                                                <p>{{  stripslashes(@$pending->requestedAddress) }}</p>
                                                                </div>
                                                        </div>
                                                            <div class="request_content_row">
                                                            <div class="request_title">
                                                                <p><strong>{{  __('common.requests_page_quantity') }}</strong></p>
                                                            </div>
                                                            <div class="request_text">
                                                                <p>{{  @$pending->requestedQty }} {{  ucfirst(@$pending->requestedMassUnit) }}</p>
                                                                </div>
                                                        </div>
                                                        @if(@$pending->status == 1)
                                                                <a href="javascript::void(0);" class="primary_color driver_chat_btn"><i class="fa fa-comments" aria-hidden="true"></i></a>
                                                        @endif
                                                        
                                                        @if(@$pending->status == 1)
                                                            <a href="javascript::void(0);" class="primary_color edit_btn">{{  __('common.requests_page_confirmed_status') }}</a>
                                                        @else
                                                            <a href="javascript::void(0);" class="primary_color edit_btn">{{  __('common.requests_page_cancelled_status') }}</a>
                                                        @endif
                                                    </div>
                                                </div>

                                        @endforeach

                                    @endif

                                <!--- Pending Reques end --->





                                  </div>
                                  </div>
                              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                 <div class="request_bx_main delivered_request">


                                 <!--- Delivered Reques start --->
                                 @if(!empty($delivered_requests))
                                            @foreach($delivered_requests as $delivered)

                                            <div class="request_list_bx">
                                             <div class="request_content_inn">
                                              <div class="request_content_row">
                                                  <div class="request_title">
                                                     <p><strong>{{  __('common.requests_page_driver_name') }}</strong></p>
                                                   </div>
                                                   <div class="request_text">
                                                     <p>{{  @$delivered->driverinfo->firstName }} {{  @$delivered->driverinfo->lastName }}</p>
                                                    </div>
                                              </div>
                                                 <div class="request_content_row">
                                                  <div class="request_title">
                                                     <p><strong>{{  __('common.requests_page_address') }}</strong></p>
                                                   </div>
                                                   <div class="request_text">
                                                     <p>{{  stripslashes(@$delivered->requestedAddress) }}</p>
                                                    </div>
                                              </div>
                                                 <div class="request_content_row">
                                                  <div class="request_title">
                                                     <p><strong>{{  __('common.requests_page_quantity') }}</strong></p>
                                                   </div>
                                                   <div class="request_text">
                                                     <p>{{  @$delivered->requestedQty }} {{  ucfirst(@$delivered->requestedMassUnit) }}</p>
                                                    </div>
                                              </div>

                                              <a href="javascript::void(0);" class="primary_color driver_chat_btn"><i class="fa fa-comments" aria-hidden="true"></i></a>

                                              <a href="javascript::void(0);" class="primary_color edit_btn">{{  __('common.requests_page_confirmed_status') }}</a>

                                         </div>
                                       </div>


                                       @endforeach

                                        @endif
                                      <!--- Delivered Reques end --->

                                       </div>
                                  </div>
                                </div>
                            </div>
                      </div>
                            </div>
               </div>


           <!---- right panel end --->

         </div>
    </div>
    </section>

@endsection
