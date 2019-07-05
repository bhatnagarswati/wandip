<?php

namespace App\Http\Controllers\Api\v1\Servicer\Brands;

use App\Http\Controllers\Controller;
use App\v1\Brands\Repositories\BrandRepository;
use App\v1\Brands\Repositories\BrandRepositoryInterface;
use App\v1\Brands\Requests\CreateBrandRequest;
use App\v1\Brands\Requests\UpdateBrandRequest;
use App\v1\Brands\Brand;
use Illuminate\Http\Request;
use Session;
use Auth;
use Validator;
use Config;
 
class BrandController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;
    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";
    
    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    

    public function __construct(BrandRepositoryInterface $brandRepository, Request $request)
    {

        $this->brandRepo = $brandRepository;
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    
     public function allBrands(Request $request)
    {

            $this->validation($request->all(),[
                'languageType' => 'required|string',
            ]);
               
            $per_page = 10;
            if($request->input('page') == "")
            {
                $skip = 0;
                $take = 10;
            } else
            {
                $skip = $per_page * $request->input('page');
                $take = ( (int) @$request->input('page') + 1) * 10;
            }
            $response['brands'] = [];
            $language = $request->input('languageType');
            //search data for contents
            $data = Brand::where(['brands.status' => 1, 'brands.languageType' => $language]);
            if(!empty($request->input('search_key')))
            {
                $keyword = $request->input('search_key');
                $data->where(function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
                });
            }
            $response['brands_count'] = $data->skip($skip)->take($take)->count();
            $data = $data->skip($skip)->take($take)->get();

            if(!empty($data))
            {
                foreach($data as $key => $value)
                {
                    $response['brands'][] = $value;
                }
            }
             
            $this->success("All Brands", $response);
    }

    

    /**
     * @param CreateBrandRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
     public function addBrand(CreateBrandRequest $request)
    {
          // validate incoming request
       $this->validation($request->all(),[
           'name' => 'required|string',
           'languageType' => 'required|string',
       ]);
        
       
        $language = $request->input('languageType');
        $createData = array(
            'name' => $request->input('name'),
            'servicerId' => $this->userId,
            'status' => 0,
            'languageType' => $language,
        );

        $brand = $this->brandRepo->createBrand($createData);
        $response = $this->getBrandInfo(@$brand->id, 'info', $request);
        $this->success("Create brand successful!", $response);
    }
	
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBrandInfo($brandId = 0, $type = 'api', Request $request)
    {
	    if($type == 'api'){
	       $this->validation($request->all(), [
	        'brandId' => 'required',
	        ]);
	       $brandId =  $request->input('brandId');

	    }
	    $brandInfo = [];
	    $brandInfo = Brand::findOrFail($brandId);
        if($brandInfo)
        {
            unset($brandInfo->updated_at);
            unset($brandInfo->created_at);
        }
    
        if($type == 'api'){
            $this->success('Pump Info', $brandInfo);
        } else
        {
            return $brandInfo;
        }
    }

    

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\v1\Brands\Exceptions\UpdateBrandErrorException
     */
    public function updateBrand(UpdateBrandRequest $request)
    {

    	$this->validation($request->all(),[
           'brandId' => 'required',
           'name' => 'required|string',
           'languageType' => 'required|string',
        ]);
        
    	$id =  $request->input('brandId');
    	$name =  $request->input('name');
    	$language = $request->input('languageType');
        $brand = $this->brandRepo->findBrandById($id);        
        $brand->name = $name;
       	$brand->languageType = $language;
       	$brand->save();
        $response = $this->getBrandInfo($id, 'info', $request);
        $this->success("Updated successful!", $response);
    }

     
}
