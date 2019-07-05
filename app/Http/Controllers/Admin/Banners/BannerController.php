<?php

namespace App\Http\Controllers\Admin\Banners;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Shop\Stores\Store;
use App\Shop\Banners\Banner;
use Illuminate\Http\Request;
use Lang;
use File;
use Session;
 

class BannerController extends Controller
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
            $banners = Banner::where('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $banners = Banner::latest()->paginate($perPage);
        }
	 
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.banners.create');
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
           'bannerType' => 'required',
           'sortOrder' => 'required',
           'bannerImage' => 'required',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }
       
        $fileName = "";
        if ($request->hasFile('bannerImage')) {

              $file = $request->file('bannerImage');
              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.banner_pic');
              $file->move($destinationPath, $fileName);
        }

       $createData = array(
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'bannerImage' => $fileName,
            'status' =>  $request->input('status'),
            'bannerType' => $request->input('bannerType'),
            'sortOrder' => $request->input('sortOrder'),
        );
        
        Banner::create($createData);

        return redirect('admin/banners')->with('message', 'Banner added!');
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
        $banner = Banner::findOrFail($id);

        return view('admin.banners.show', compact('banner'));
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
        $banner = Banner::findOrFail($id);

        return view('admin.banners.edit', compact('banner'));
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
        
        $banner = Banner::findOrFail($id);

        // validate incoming request
        $validator = Validator::make($request->all(), [
           'title' => 'required|string',
           'status' => 'required',
           'bannerType' => 'required',
           'sortOrder' => 'required',
           
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }

        $fileName = "";
        if ($request->hasFile('bannerImage')) {

              $file = $request->file('bannerImage');

            if (file_exists(config('constants.banner_pic')."/".$banner->bannerImage)) {
                unlink(config('constants.banner_pic')."/".$banner->bannerImage);
            }

              $fileName = str_random(10).'.'.$file->getClientOriginalExtension();
              //Upload File
              $destinationPath = config('constants.banner_pic');
              $file->move($destinationPath, $fileName);
              $banner->bannerImage =  $fileName;
        }

        $banner->title =  $request->input('title');
        $banner->description =  $request->input('description');
        $banner->bannerType =  $request->input('bannerType');
        $banner->sortOrder =  $request->input('sortOrder');
        $banner->status =  $request->input('status');
        $banner->save();

        return redirect('admin/banners')->with('message', 'Banner updated!');
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
       
        $banner = Banner::findOrFail($id);

        if (file_exists(config('constants.banner_pic')."/".$banner->bannerImage)) {
            unlink(config('constants.banner_pic')."/".$banner->bannerImage);
        }
         Banner::destroy($id);
        return redirect('admin/banners')->with('message', 'Banner deleted!');
    }
}
