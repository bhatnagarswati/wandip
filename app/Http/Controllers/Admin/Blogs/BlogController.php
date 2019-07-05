<?php

namespace App\Http\Controllers\Admin\Blogs;

use App\Http\Controllers\Controller;
use App\Shop\Blogs\Blog;
use App\Shop\Stores\Store;
use Config;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $blogs = Blog::where('title', 'LIKE', "%$keyword%")
                ->orWhere('shortDescription', 'LIKE', "%$keyword%")
                ->orWhere('fullDescription', 'LIKE', "%$keyword%")
                ->orWhere('metaTitle', 'LIKE', "%$keyword%")
                ->orWhere('metaDescription', 'LIKE', "%$keyword%")
                ->orWhere('metaKeywords', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $blogs = Blog::where('languageType', Config::get('app.locale'))->latest()->paginate($perPage);
        }

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'status' => 'required',
            'shortDescription' => 'required|string',
            'fullDescription' => 'required|string',
            'author' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileName = "";
        if ($request->hasFile('blogImage')) {
            $file = $request->file('blogImage');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.blog_pic');
            $file->move($destinationPath, $fileName);
        }
        $createData = array(
            'title' => addslashes($request->input('title')),
            'shortDescription' => addslashes($request->input('shortDescription')),
            'fullDescription' => addslashes($request->input('fullDescription')),
            'status' => $request->input('status'),
            'author' => $request->input('author'),
            'blogImage' => $fileName,
            'languageType' => Config::get('app.locale'),
            'addedOn' => date('Y-m-d H:i:s'),
            'metaTitle' => addslashes($request->input('metaTitle')),
            'metaDescription' => addslashes($request->input('metaDescription')),
            'metaKeywords' => addslashes($request->input('metaKeywords')),
        );

        Blog::create($createData);
        return redirect('admin/blogs')->with('message', 'Blog added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $page = Blog::findOrFail($id);

        // validate incoming request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'status' => 'required',
            'shortDescription' => 'required|string',
            'fullDescription' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page->title = addslashes($request->input('title'));
        $page->shortDescription = addslashes($request->input('shortDescription'));
        $page->fullDescription = addslashes($request->input('fullDescription'));
        $page->languageType = Config::get('app.locale');
        $page->status = $request->input('status');
        $page->author = $request->input('author');
        $page->metaTitle = addslashes($request->input('metaTitle'));
        $page->metaDescription = addslashes($request->input('metaDescription'));
        $page->metaKeywords = addslashes($request->input('metaKeywords'));
        $page->save();

        return redirect('admin/blogs')->with('message', 'Blog updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Blog::destroy($id);
        return redirect('admin/blogs')->with('message', 'Blog deleted!');
    }
}
