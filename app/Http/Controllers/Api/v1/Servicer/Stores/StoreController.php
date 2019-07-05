<?php

namespace App\Http\Controllers\Api\v1\Servicer\Stores;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\v1\Stores\Store;
use App\v1\Products\Transformations\ProductTransformable;
use App\v1\Products\Product;
use App\v1\Pumps\Pump;
use Illuminate\Http\Request;
use App\v1\Ratings\ProductReviews;
use Lang;
use Session;
use Config;


class StoreController extends Controller
{

    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";
    use ProductTransformable;

    public function __construct(Request $request)
    {
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }


    /**
     * Display a listing of the stores.
     *
     * @return (array)
     */
    public function allStores(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                'languageType' => 'required',
            ]
        );


        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int)@$request->input('page') + 1) * 10;
        }

        $languageType = $request->input('languageType');

        $response['stores'] = [];
    //search data for contents
        $data = Store::where(['servicerId' => $this->userId, 'isActive' => 1, 'languageType' => $languageType]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('storeTitle', 'LIKE', "%$keyword%")
                    ->Where('servicerId', '=', $this->userId)
                    ->orWhere('storeDescription', 'LIKE', "%$keyword%")
                    ->orWhere('storeLocation', 'LIKE', "%$keyword%")
                    ->orWhere('storePic', 'LIKE', "%$keyword%")
                    ->orWhere('isActive', 'LIKE', "%$keyword%");
            });
        }

        $data = $data->skip($skip)->take($take)->get();
        foreach ($data as $key => $value) {
            $response['stores'][] = [
                'storeId' => $value->id,
                'storeTitle' => $value->storeTitle,
                'storeDescription' => (string)@$value->storeDescription,
                'storeLocation' => (string)@$value->storeLocation,
                'storeLat' => @$value->storeLat,
                'storeLong' => @$value->storeLong,
                'storePic' => config('constants.store_pull_path') . $value->storePic,
                'servicerId' => (string)@$value->servicerId,
                'products_count' => $this->getStoreProductsCount($value->id),
            ];
        }

        $stores_count = Store::where(['servicerId' => $this->userId, 'isActive' => 1])->count();
        $response['stores_count'] = $stores_count;


        $this->success("Success", $response);
    }

    private function getStoreProductsCount($storeid = 0)
    {

        $allproducts = Product::select('id')->where(['store_id' => $storeid, 'status' => 1])->get();
        return $allproducts->count();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addstore(Request $request)
    {

        $requestData = $request->all();
         // validate incoming request
        $this->validation($request->all(), [
            'storeTitle' => 'required|string',
            'storeDescription' => 'required|string',
            'storeLocation' => 'required|string',
            'storeLat' => 'required',
            'storeLong' => 'required',
            'languageType' => 'required|string',
        ]);


        $fileName = "";
        $fullfilePath = "";
        if ($request->hasFile('storePic')) {

            $file = $request->file('storePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
              //Upload File
            $destinationPath = config('constants.store_pic');
            $file->move($destinationPath, $fileName);
            $fullfilePath = $destinationPath . $fileName;
        }

        $languageType = $request->input('languageType');
        $createData = array(
            'storeTitle' => $request->input('storeTitle'),
            'storeDescription' => $request->input('storeDescription'),
            'storeLocation' => $request->input('storeLocation'),
            'storePic' => $fileName,
            'servicerId' => $this->userId,
            'isActive' => 1,
            'storeLat' => $request->input('storeLat'),
            'storeLong' => $request->input('storeLong'),
            'languageType' => $languageType,
        );


        $storeAdded = Store::create($createData)->id;

        if ($storeAdded) {
            $createData['storeId'] = $storeAdded;
            $createData['storePic'] = $fullfilePath;
            $this->success('Store added!', $createData);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {

        $this->validation($request->all(), [
            'storeId' => 'required',
        ]);
        $storeId = $request->input('storeId');
        $store = Store::findOrFail($storeId);
        $this->success('Store info!', $store);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function editStore(Request $request)
    {

        $this->validation($request->all(), [
            'storeId' => 'required',
        ]);
        $input = $request->all();

        $store = Store::findOrFail($input['storeId']);
        $store->storePic = config('constants.store_pull_path') . $store->storePic;
        $store->storeId = $store->id;
        unset($store->id);
        unset($store->adminId);
        unset($store->created_at);
        unset($store->updated_at);
        $this->success('Edit store info', $store);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateStore(Request $request)
    {
        $requestData = $request->all();
    // validate incoming request
        $this->validation($request->all(), [
            'storeId' => 'required|int',
            'storeTitle' => 'required|string',
            'storeDescription' => 'required|string',
            'storeLocation' => 'required|string',
            'storeLat' => 'required',
            'storeLong' => 'required',
        ]);

        $languageType = $request->input('languageType');
        $store = Store::findOrFail($requestData['storeId']);
        $fileName = "";
        if ($request->hasFile('storePic')) {

            $file = $request->file('storePic');
            $fileName = str_random(10) . '.' . $file->getClientOriginalExtension();
              //Upload File
            $destinationPath = config('constants.store_pic');
            $file->move($destinationPath, $fileName);
            $store->storePic = $fileName;
        }

        $store->storeTitle = $request->input('storeTitle');
        $store->storeDescription = $request->input('storeDescription');
        $store->storeLocation = $request->input('storeLocation');
        $store->storeLat = $request->input('storeLat');
        $store->storeLong = $request->input('storeLong');
        $store->languageType = $languageType;
        $store->save();

        $this->success('Store info updated.', $store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteStore(Request $request)
    {

        $this->validation($request->all(), [
            'storeId' => 'required',
        ]);
        $input = $request->all();
        $store = Store::findOrFail($input['storeId']);
        $store->isActive = 0;
        $store->save();
        $this->success('Store deleted!', "");

    }


    /**
     * Display a listing of the stores.
     *
     * @return (array)
     */
    public function storeInfo(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                'storeId' => 'required',
            ]
        );

        $per_page = 10;
        if ($request->input('page') == "") {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int)@$request->input('page') + 1) * 10;
        }

        $languageType = $request->input('languageType');
        $storeId = $request->input('storeId');


        // Get All store products
        $response['products'] = [];
        $data = Product::where(['store_id' => $storeId, 'status' => 1, 'languageType' => $languageType]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('sku', 'LIKE', "%$keyword%")
                    ->orWhere('name', 'LIKE', "%$keyword%")
                    ->orWhere('slug', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%");
            });
        }
        $response['products_count'] = $data->count();
        $data = $data->skip($skip)->take($take)->get();

        foreach ($data as $key => $value) {
            $product = $this->transformProduct($value);
            $product->rating = $this->getAvgRatings($value->id);
            $response['products'][] = $product;
        }

        // Get Store Info
        $response['store'] = null;
        $storeData = Store::where(['servicerId' => $this->userId, 'id' => $storeId])->get();
        foreach ($storeData as $key => $value) {
            $response['store'] = [
                'storeId' => $value->id,
                'storeTitle' => $value->storeTitle,
                'storeDescription' => (string)@$value->storeDescription,
                'storeLocation' => (string)@$value->storeLocation,
                'storeLat' => @$value->storeLat,
                'storeLong' => @$value->storeLong,
                'storePic' => config('constants.store_pull_path') . $value->storePic,
                'servicerId' => (string)@$value->servicerId,
            ];
        }

        // Get Pump Info
        $response['pump'] = null;
        $pumpInfo = Pump::where(['servicerId' => $this->userId, 'storeid' => $storeId, 'status' => 1])->get();
        if (!empty($pumpInfo)) {
            foreach ($pumpInfo as $pump) {
                $pumpinfo['pumpId'] = $pump->pumpId;
                $pumpinfo['pumpTitle'] = $pump->pumpTitle;
                $pumpinfo['pumpDescription'] = $pump->pumpDescription;
                $pumpinfo['pumpAddress'] = $pump->pumpAddress;
                $pumpinfo['pumpLat'] = $pump->pumpLat;
                $pumpinfo['pumpLong'] = $pump->pumpLong;
                $pumpinfo['pumpPrice'] = $pump->pumpPrice;
                $pumpinfo['pumpMassUnit'] = $pump->pumpMassUnit <> null ? $pump->pumpMassUnit : "";
                $pumpinfo['pumpPic'] = config('constants.pump_pull_path') . $pump->pumpPic;
                $response['pump'] = $pumpinfo;
            }
        }

        // Return Response 
        $this->success("Store Information.", $response);
    }

    private function getAvgRatings($productId = 0)
    {
        $productReviews = ProductReviews::select('*')->where('productId', $productId)->get();
        if (!empty($productReviews)) {
            $totalRating = [];
            foreach ($productReviews as $reviews) {
                $totalRating[] = $reviews->customerRating;
                $totalReviews[] = $reviews;
            }
            // Total Star Rating
            $total = array_sum($totalRating);
            $totalAvg = $total / 5;
              return number_format((float)$totalAvg, 1, '.', '');
        } else {
            return 0;
        }

    }

}
