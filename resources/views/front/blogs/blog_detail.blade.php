@extends('layouts.front.app') @section('content')

<section class="banner banner_inn" style="background-image: url('{{ asset('public/images/banner_main.jpg')}} ')">
    <div class="container">
        <div class="banner_text" data-aos="fade-right" data-aos-easing="ease" data-aos-delay="1000" data-aos-duration="600">
        <h1>{{ __('common.blog_page_title') }}</h1>
        </div>
    </div>
</section>

<!--blog section-->

<section class="blog_detail_main">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="blog_recent_rt">

                    <div class="search_bar_main">
                        <form method="GET" action="{{ url('/blogs') }}" accept-charset="UTF-8" role="search">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('common.blog_page_search') }}">
                            <button class="search_btn_com searchbar_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <div class="blog_recent_post">
                        <h3 class="wow fadeInDown  animated" data-wow-duration="1s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.1s; animation-name: fadeInDown;">{{ __('common.blog_page_recent_posts') }}</h3>
                        <ul class="recent_post">
                            @if(!empty($recentblogs)) @foreach($recentblogs as $item)

                            @php $blog_image = $item->blogImage <> "" ? $item->blogImage : "no-image.png";  @endphp
                            <li class="wow fadeInDown  animated" data-wow-duration="1s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.1s; animation-name: fadeInDown;">
                                <figure class="recent_post_figure" style="background-image: url('{{ config('constants.blog_pull_path'). $blog_image}}')">
                                    <a href="{{ url('/blog/'.$item->id.'/'.urlencode($item->title))  }}"></a>
                                </figure>
                                <div class="figure-cont">
                                    <h4><a href="{{ url('/blog/'.$item->id.'/'.urlencode($item->title))  }}"> {{ stripslashes(html_entity_decode($item->title)) }}</a></h4>
                                    <div class="blog_content">{{ strip_tags(stripslashes(html_entity_decode($item->shortDescription))) }}</div>
                                </div>
                            </li>
                            @endforeach @endif
                        </ul>
                    </div>

                    <div class="blog_recent_post" style='display:none;'>
                        <h3 class="wow fadeInDown  animated" data-wow-duration="1s" data-wow-delay="0.1s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.1s; animation-name: fadeInDown;">{{ __('common.blog_page_archive') }}</h3>

                        <ul class="archive_list">
                            <li><a href="#">Jan 2018</a></li>
                            <li><a href="#">Nov 2017</a></li>
                            <li><a href="#">Jan 2016</a></li>
                            <li><a href="#">Mar 2015</a></li>
                            <li><a href="#">Mar 2015</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="blog_detail_lt about_text ">
                    @php $blog_image = $blog->blogImage <> "" ? $blog->blogImage : "no-image.png";  @endphp
                    <figure class="blog_detail_figure" style="background-image:url('{{ config('constants.blog_pull_path').$blog_image}}')"></figure>
                    <div class="blog_title_head">
                        <h3>{{ $blog->title }} </h3>
                        <p><strong>{{ date('d M Y', strtotime($blog->addedOn)) }} || {{ __('common.blog_page_by') }} {{ ucfirst($blog->author) }}</strong></p>
                    </div>

                    <div class="blog_description">
                        @php echo  html_entity_decode(stripslashes($blog->fullDescription)) @endphp
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection