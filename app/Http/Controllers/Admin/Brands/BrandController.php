<?php

namespace App\Http\Controllers\Admin\Brands;

use App\Http\Controllers\Controller;
use App\Shop\Brands\Repositories\BrandRepository;
use App\Shop\Brands\Repositories\BrandRepositoryInterface;
use App\Shop\Brands\Requests\CreateBrandRequest;
use App\Shop\Brands\Requests\UpdateBrandRequest;
use Session;
use Auth;
use Config;
use Validator;

class BrandController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;

    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepo = $brandRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->brandRepo->paginateArrayResults($this->brandRepo->listBrands(['*'], 'name', 'asc')->where('languageType' , Config::get('app.locale'))->all());

        return view('admin.brands.list', ['brands' => $data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * @param CreateBrandRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateBrandRequest $request)
    {

       $adminId = Auth::guard('employee')->user()->id;
         // validate incoming request
       $validator = Validator::make($request->all(), [
           'name' => 'required|string',
       ]);
        
       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       }
        $createData = array(
            'name' => $request->input('name'),
            'adminId' => $adminId,
            'status' => $request->input('status'),
            'languageType' => Config::get('app.locale'),
        );

        $this->brandRepo->createBrand($createData);
        return redirect()->route('admin.brands.index')->with('message', 'Create brand successful!');
    }

	
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brandRepo->findBrandById($id);

        $brandRepo = new BrandRepository($brand);
        return redirect()->route('admin.brands.index');
    }
	
    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('admin.brands.edit', ['brand' => $this->brandRepo->findBrandById($id)]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = $this->brandRepo->findBrandById($id);
        $brand->languageType = Config::get('app.locale');
        $brandRepo = new BrandRepository($brand);
        $brandRepo->updateBrand($request->all());

        return redirect()->route('admin.brands.edit', $id)->with('message', 'Update successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $brand = $this->brandRepo->findBrandById($id);

        $brandRepo = new BrandRepository($brand);
        $brandRepo->dissociateProducts();
        $brandRepo->deleteBrand();

        return redirect()->route('admin.brands.index')->with('message', 'Delete successful!');
    }
}
