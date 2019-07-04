@extends('layouts.front.app')
@section('content')
<section class="banner banner_inn"  style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
        <div class="container">
             <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000"  data-aos-duration="600">
                <h1>{{ __('common.blog_page_title') }}</h1>
              </div>
        </div>
    </section>
<!--blog section-->   
<section class="blog blog_main"> 
            <div class="container">
                <div class="row">

               
                  @if(!$blogs->isEmpty())
                   @foreach($blogs as $item)
                     <div class="col-md-6">
                      <div class="blog_box">

                        @php $blog_image = $item->blogImage <> "" ? $item->blogImage : "no-image.png";  @endphp
                         <figure class="blog_detail_figure" style="background-image: url('{{ config('constants.blog_pull_path').$blog_image }}')"></figure>
                          <div class="about_text">
                              <div class="blog_title_head">
                                    <h3> <a href="{{ url('/blog/'.$item->id.'/'.urlencode($item->title))  }}"> {{ $item->title }} </a> </h3>
                                    <p><strong>{{ date('d M Y', strtotime($item->addedOn)) }} || {{ __('common.blog_page_by') }}  {{ ucfirst($item->author) }}</strong></p>
                              </div>
                              <p>@php echo strip_tags(stripslashes(html_entity_decode($item->shortDescription))) @endphp</p>
                              <a href="{{ url('/blog/'.$item->id.'/'.urlencode($item->title))  }}" class="link">{{ __('common.blog_page_read_more') }} </a>
                          </div>
                        </div>
                    </div>
                    @endforeach   
                    @else
                    <p class="alert alert-warning">{{ __('common.blog_page_search_not_found') }}</p>
                    @endif
                
                   
                </div>
                <div class="pagination_bx">
                  {!! $blogs->appends(['search' => Request::get('search')])->render() !!}                
                </div>
            </div>
        </section>   
@endsection