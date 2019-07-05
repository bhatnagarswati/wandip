<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Shop\Stores\Store;
use App\Shop\Pages\CmsPage;
use Illuminate\Http\Request;
use Lang;
use File;
use Session;
use Config;


class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $pages = CmsPage::where('title', 'LIKE', "%$keyword%")
                ->orWhere('shortDescription', 'LIKE', "%$keyword%")
                ->orWhere('fullDescription', 'LIKE', "%$keyword%")
                ->orWhere('metaTitle', 'LIKE', "%$keyword%")
                ->orWhere('metaDescription', 'LIKE', "%$keyword%")
                ->orWhere('metaKeywords', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $pages = CmsPage::where('languageType', Config::get('app.locale'))->latest()->paginate($perPage);
        }

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.pages.create');
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
            'shortDescription' => 'required',
            'fullDescription' => 'required',
            'pageType' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileName = "";
        if ($request->hasFile('pagePic')) {

            $file = $request->file('pagePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
              //Upload File
            $destinationPath = config('constants.page_pic');
            $file->move($destinationPath, $fileName);
        }


        $createData = array(
            'title' => addslashes($request->input('title')),
            'shortDescription' => addslashes($request->input('shortDescription')),
            'fullDescription' => addslashes($request->input('fullDescription')),
            'pagePic' => $fileName,
            'status' => $request->input('status'),
            'languageType' => Config::get('app.locale'),
            'pageType' => $request->input('pageType'),
            'metaTitle' => addslashes($request->input('metaTitle')),
            'metaDescription' => addslashes($request->input('metaDescription')),
            'metaKeywords' => addslashes($request->input('metaKeywords')),
        );

        CmsPage::create($createData);

        return redirect('admin/pages')->with('message', 'Page added!');
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
        $page = CmsPage::findOrFail($id);

        return view('admin.pages.show', compact('page'));
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
        $page = CmsPage::findOrFail($id);

        return view('admin.pages.edit', compact('page'));
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

        $page = CmsPage::findOrFail($id);

        // validate incoming request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'status' => 'required',
            'shortDescription' => 'required|string',
            'fullDescription' => 'required|string',
            'pageType' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileName = "";
        if ($request->hasFile('pagePic')) {

            $file = $request->file('pagePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.page_pic');
            $file->move($destinationPath, $fileName);
            $page->pagePic = $fileName;
        }

        $page->title = addslashes($request->input('title'));
        $page->shortDescription = addslashes($request->input('shortDescription'));
        $page->fullDescription = addslashes($request->input('fullDescription'));
        $page->languageType = Config::get('app.locale');
        $page->status = $request->input('status');
        $page->pageType = $request->input('pageType');
        $page->metaTitle = addslashes($request->input('metaTitle'));
        $page->metaDescription = addslashes($request->input('metaDescription'));
        $page->metaKeywords = addslashes($request->input('metaKeywords'));
        $page->save();

        return redirect('admin/pages')->with('message', 'Page updated!');
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
        CmsPage::destroy($id);
        return redirect('admin/pages')->with('message', 'Page deleted!');
    }
}
