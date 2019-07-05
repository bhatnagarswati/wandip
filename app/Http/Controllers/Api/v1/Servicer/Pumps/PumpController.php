<?php

namespace App\Http\Controllers\Api\v1\Servicer\Pumps;

use App\Http\Controllers\Controller;
use App\v1\Pumps\Pump;
use App\v1\Stores\Store;
use Config;
use Illuminate\Http\Request;

class PumpController extends Controller
{

    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";

    public function __construct(Request $request)
    {

        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function allPumps(Request $request)
    {

        // validate incoming request
        $this->validation($request->all(),
            [
                'languageType' => 'required',
            ]);

        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $languageType = $request->input('languageType');

        $response['pumps'] = [];
        //search data for contents
        $data = Pump::with('stores')->where(['pumps.servicerId' => $this->userId, 'pumps.status' => 1, 'languageType' => $languageType]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('pumpTitle', 'LIKE', "%$keyword%")
                    ->orWhere('pumpDescription', 'LIKE', "%$keyword%")
                    ->orWhere('pumpAddress', 'LIKE', "%$keyword%");
            });
        }
        $response['pumps_count'] = $data->count();
        $data = $data->skip($skip)->take($take)->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $response['pumps'][] = [
                    'pumpId' => $value->pumpId,
                    'pumpTitle' => $value->pumpTitle,
                    'pumpDescription' => (string) @$value->pumpDescription,
                    'pumpAddress' => (string) @$value->pumpAddress,
                    'pumpLat' => @$value->pumpLat,
                    'pumpLong' => @$value->pumpLong,
                    'pumpPrice' => (string) @$value->pumpPrice,
                    'pumpMassUnit' => $value->pumpMassUnit != null ? $value->pumpMassUnit : "",
                    'pumpPic' => config('constants.pump_pull_path') . $value->pumpPic,
                    'storeId' => @$value->stores->id != null ? @$value->stores->id : "",
                    'storeTitle' => (string) @$value->stores->storeTitle,
                    'storeDescription' => (string) @$value->stores->storeDescription,

                ];
            }
        }

        $this->success("All Pumps", $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function servicerStores($storeId = 0)
    {
        $storeids = Pump::pluck('storeId');
  
        $stores = Store::select('id as storeid', 'storeTitle')->where(['isActive' => 1,
            'servicerId' => $this->userId])->whereNotIn('id', $storeids)->get();
        $this->success("All Servicer Stores", $stores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addPump(Request $request)
    {
        $requestData = $request->all();

        // validate incoming request
        $this->validation($request->all(),
            [
                'storeId' => 'required|string',
                'pumpTitle' => 'required|string',
                'pumpAddress' => 'required|string',
                'pumpDescription' => 'required|string',
                'pumpLat' => 'required',
                'pumpLong' => 'required',
                'pumpPrice' => 'required',
                'pumpMassUnit' => 'required|string',
                'languageType' => 'required',
            ]);

        $fileName = "";
        if ($request->hasFile('pumpPic')) {

            $file = $request->file('pumpPic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
            //Upload File
            $destinationPath = config('constants.pump_pic');
            $file->move($destinationPath, $fileName);
        }
        $createData = array(
            'storeId' => $request->input('storeId'),
            'servicerId' => $this->userId,
            'pumpTitle' => $request->input('pumpTitle'),
            'pumpAddress' => $request->input('pumpAddress'),
            'pumpDescription' => $request->input('pumpDescription'),
            'pumpLat' => $request->input('pumpLat'),
            'pumpLong' => $request->input('pumpLong'),
            'pumpPrice' => $request->input('pumpPrice'),
            'pumpMassUnit' => $request->input('pumpMassUnit'),
            'pumpPic' => $fileName,
            'status' => 1,
            'languageType' => $request->input('languageType'),
        );

        $pump_res = Pump::create($createData);
        $pumpInfo = [];
        if ($pump_res) {
            $pumpInfo = $this->getPumpInfo($pump_res->pumpId, 'info', $request);
        }
        $this->success('Pump added!', $pumpInfo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function getPumpInfo($pumpId = 0, $type = 'api', Request $request)
    {
        if ($type == 'api') {
            $this->validation($request->all(), [
                'pumpId' => 'required',
            ]);
            $pumpId = $request->input('pumpId');

        }
        $pumpInfo = [];
        $pumpInfo = Pump::findOrFail($pumpId);
        if ($pumpInfo) {
            $storeId = $pumpInfo->storeId;
            $storeTitle = Store::where('id', $storeId)->select('storeTitle')->first();
            $pumpInfo->storeTitle = $storeTitle->storeTitle;
            $pumpInfo->pumpMassUnit = $pumpInfo->pumpMassUnit != null ? $pumpInfo->pumpMassUnit : "";
            $pumpInfo->pumpPic = config('constants.pump_pull_path') . $pumpInfo->pumpPic;
            unset($pumpInfo->servicerId);
            unset($pumpInfo->adminId);
            unset($pumpInfo->deleted_at);
            unset($pumpInfo->updated_at);
            unset($pumpInfo->created_at);
        }

        if ($type == 'api') {
            $this->success('Pump Info', $pumpInfo);
        } else {
            return $pumpInfo;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updatePump(Request $request)
    {

        $pumpId = $request->input('pumpId');

        // validate incoming request
        $this->validation($request->all(),
            [
                'storeId' => 'required',
                'pumpTitle' => 'required|string',
                'pumpDescription' => 'required|string',
                'pumpAddress' => 'required|string',
                'pumpLat' => 'required',
                'pumpLong' => 'required',
                'pumpMassUnit' => 'required|string',
                'pumpPrice' => 'required|string',
                'languageType' => 'required',
            ]);

        $pump = Pump::findOrFail($pumpId);
        if ($pump) {
            $fileName = "";
            if ($request->hasFile('pumpPic')) {
                $file = $request->file('pumpPic');
                $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
                //Upload File
                $destinationPath = config('constants.pump_pic');
                $file->move($destinationPath, $fileName);
                $pump->pumpPic = $fileName;
            }

            $pump->storeId = $request->input('storeId');
            $pump->pumpTitle = $request->input('pumpTitle');
            $pump->servicerId = (int) $this->userId;
            $pump->pumpDescription = $request->input('pumpDescription');
            $pump->pumpAddress = $request->input('pumpAddress');
            $pump->pumpLat = $request->input('pumpLat');
            $pump->pumpLong = $request->input('pumpLong');
            $pump->pumpPrice = $request->input('pumpPrice');
            $pump->pumpMassUnit = $request->input('pumpMassUnit');
            $pump->languageType = $request->input('languageType');
            $pump->save();
            $pumpInfo = $this->getPumpInfo($pumpId, 'info', $request);
            $this->success('Pump updated!', $pumpInfo);
        } else {
            $this->success('Pump not found!', null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deletePump(Request $request)
    {
        $pumpId = $request->input('pumpId');
        $this->validation($request->all(), [
            'pumpId' => 'required',
        ]);
        Pump::destroy($pumpId);
        $this->success('Pump deleted!', "");

    }

}
