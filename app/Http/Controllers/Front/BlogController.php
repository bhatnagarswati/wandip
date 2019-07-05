<?php

namespace App\Http\Controllers\Front;

use App\Shop\Blogs\Blog;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Validator;

class BlogController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

     
    /**
     * Blog Listing page
     * 
     * 
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $blogs = Blog::where('title', 'LIKE', "%$keyword%")
                ->orWhere('shortDescription', 'LIKE', "%$keyword%")
                ->orWhere('fullDescription', 'LIKE', "%$keyword%")
                ->orWhere('author', 'LIKE', "%$keyword%")
                ->orWhere('metaTitle', 'LIKE', "%$keyword%")
                ->orWhere('metaDescription', 'LIKE', "%$keyword%")
                ->orWhere('metaKeywords', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $blogs = Blog::where(['languageType' => Config::get('app.locale') , 'status' => 1])->latest()->paginate($perPage);
        }
        return view('front.blogs.blogs', compact('blogs'));
    }

    /**
     * Blog Detail Pages
     * 
     * 
     */
    public function blogDetail($id = "")
    {

        $blog = Blog::findOrFail($id);
        $recentblogs = Blog::where(['languageType' => Config::get('app.locale') , 'status' => 1])->orderBy('addedOn', 'desc')->take(5)->get();
        return view('front.blogs.blog_detail', compact('recentblogs', 'blog'));
    }

    

}
