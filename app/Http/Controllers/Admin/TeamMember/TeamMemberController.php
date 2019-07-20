<?php

namespace App\Http\Controllers\Admin\TeamMember;

use App\Shop\Teams\Team;
use Illuminate\Http\Request;
use Config;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return "Hello";
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $teams = Team::where('name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $teams = Team::where('languageType', Config::get('app.locale'))->latest()->paginate($perPage);
        }

        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileName = "";

        if ($request->hasFile('teamImage')) {
            $file = $request->file('teamImage');

            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.team_pic');
            $file->move($destinationPath, $fileName);
        }

        $createData = array(
            'name' => addslashes($request->input('name')),
            'description' => addslashes($request->input('description')),
            'status' => $request->input('status'),
            'image' => $fileName,
            'languageType' => Config::get('app.locale'),
            'addedOn' => date('Y-m-d H:i:s'),
            'metaTitle' => addslashes($request->input('metaTitle')),
            'metaDescription' => addslashes($request->input('metaDescription')),
            'metaKeywords' => addslashes($request->input('metaKeywords')),
            'facebook_link' => addslashes($request->input('facebook_link')),
            'twitter_link' => addslashes($request->input('twitter_link')),
            'linkedin_link' => addslashes($request->input('linkedin_link')),
        );

        Team::create($createData);
        return redirect('admin/teams')->with('message', 'Team Member added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = Team::findOrFail($id);

        // validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('teamImage')) {
            $file = $request->file('teamImage');

            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.team_pic');
            $file->move($destinationPath, $fileName);
            $page->image = $fileName;
        }

        $page->name = addslashes($request->input('name'));
        $page->description = addslashes($request->input('description'));

        $page->languageType = Config::get('app.locale');
        $page->status = $request->input('status');

        $page->metaTitle = addslashes($request->input('metaTitle'));
        $page->metaDescription = addslashes($request->input('metaDescription'));
        $page->metaKeywords = addslashes($request->input('metaKeywords'));

        $page->facebook_link = addslashes($request->input('facebook_link'));
        $page->twitter_link = addslashes($request->input('twitter_link'));
        $page->linkedin_link = addslashes($request->input('linkedin_link'));

        $page->save();

        return redirect('admin/teams')->with('message', 'Team Member updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Team::destroy($id);
        return redirect('admin/teams')->with('message', 'Team deleted!');
    }
}
