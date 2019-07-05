<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\Shop\Brands\Repositories\BrandRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Requests\CreateProductRequest;
use App\Shop\Products\Requests\UpdateProductRequest;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Stores\Store;
use App\Shop\Tools\UploadableTrait;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    use ProductTransformable,
        UploadableTrait;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepo;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attributeValueRepository;

    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;

    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     * @param ProductAttribute $productAttribute
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, AttributeRepositoryInterface $attributeRepository, AttributeValueRepositoryInterface $attributeValueRepository, ProductAttribute $productAttribute, BrandRepositoryInterface $brandRepository
    ) {
        $this->productRepo = $productRepository;
        $this->categoryRepo = $categoryRepository;
        $this->attributeRepo = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->productAttribute = $productAttribute;
        $this->brandRepo = $brandRepository;

        $this->middleware(['permission:create-product, guard:employee'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-product, guard:employee'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-product, guard:employee'], ['only' => ['destroy']]);
        $this->middleware(['permission:view-product, guard:employee'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->productRepo->listProducts('id')->where('languageType', Config::get('app.locale'));

        if (request()->has('q') && request()->input('q') != '') {
            $list = $this->productRepo->searchProduct(request()->input('q'));
        }

        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        return view('admin.products.list', [
            'products' => $this->productRepo->paginateArrayResults($products, 25),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0)->toTree();

        return view('admin.products.create', [
            'categories' => $categories,
            'brands' => $this->brandRepo->listBrands(['*'], 'name', 'asc'),
            'attributes' => $this->attributeRepo->listAttributes(),
            'default_weight' => env('SHOP_WEIGHT'),
            'weight_units' => Product::MASS_UNIT,

            'product' => new Product,
            'stores' => Store::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->except('_token', '_method');
        $data['slug'] = str_slug($request->input('name'));
        if ($request->hasFile('cover') && $request->file('cover') instanceof UploadedFile) {
            $data['cover'] = $this->productRepo->saveCoverImage($request->file('cover'));
        }

        // Add Sku dynamically.
        $data['sku'] = date("Ymds");
        $pprice = $data['price'];
        $productSizes = "";
        if ($request->has('productSizes')) {
            $productSizes = $data['productSizes'];
        }

        unset($data['productSizes']);
        unset($data['attribute']);

        $data['languageType'] = Config::get('app.locale');

        $product = $this->productRepo->createProduct($data);
        // Add Attributes
        if (!empty($productSizes)) {
            foreach ($productSizes as $sizes) {
                $attributeData = [];
                $attributeData['attributeValue'] = $sizes;
                $attributeData['productAttributeQuantity'] = ($sizes * 1000);
                // will not use product
                $attributeData['productAttributePrice'] = ($sizes * $pprice);
                $attributeData['default'] = 0;
                $this->saveProductCombinations($attributeData, $product);
            }

        }

        $productRepo = new ProductRepository($product);
        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }

        // Update product for sku
        $update_product_sku = date("Ymds") . 's' . $data['store_id'] . $data['mass_unit'] . 'p' . $product->id . 'b' . $data['brand_id'];
        $skudata['sku'] = $update_product_sku;
        $productRepo->updateProduct($skudata);
        //

        return redirect()->route('admin.products.edit', $product->id)->with('message', 'Create successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $product = $this->productRepo->findProductById($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $product = $this->productRepo->findProductById($id);

        $productAttributes = $product->attributes()->get();

        $qty = $productAttributes->map(function ($item) {
            return $item->quantity;
        })->sum();

        $existedAttributes = [];
        if (!empty($productAttributes)) {
            foreach ($productAttributes as $pa) {
                foreach ($pa->attributesValues as $item) {
                    $existedAttributes[] = $item->value;
                }
            }
        }

        $categories = $this->categoryRepo->listCategories('name', 'asc')->toTree();

        return view('admin.products.edit', [
            'product' => $product,
            'images' => $product->images()->get(['src']),
            'categories' => $categories,
            'selectedIds' => $product->categories()->pluck('category_id')->all(),
            'attributes' => $this->attributeRepo->listAttributes(),
            'productAttributes' => $productAttributes,
            'existedproductAttributes' => $existedAttributes,
            'qty' => $qty,
            'brands' => $this->brandRepo->listBrands(['*'], 'name', 'asc'),
            'weight' => $product->weight,
            'default_weight' => $product->mass_unit,
            'weight_units' => Product::MASS_UNIT,
            'stores' => Store::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Shop\Products\Exceptions\ProductUpdateErrorException
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productRepo->findProductById($id);
        $productRepo = new ProductRepository($product);

        $productAttributes = $product->attributes()->get();

        $data = $request->except(
            'categories', '_token', '_method', 'default', 'image', 'productAttributeQuantity', 'productAttributePrice', 'attributeValue', 'combination'
        );

        $existedproductAttributes = $product->attributes()->select('id')->get()->toArray();

        $productSizes = "";
        $existedAttributes = "";
        if ($request->has('productSizes')) {
            $pprice = $data['price'];
            $existedAttributes = array_column($existedproductAttributes, 'id');
            $productSizes = $data['productSizes'];
            $existingresults = @array_intersect(@$existedAttributes, @$productSizes);
            $resultstodelete = @array_diff(@$existedAttributes, @$productSizes);
            $resultstoadd = @array_diff(@$productSizes, @$existedAttributes);
            // Add New Attribues
            if (!empty(@$resultstoadd)) {
                foreach ($resultstoadd as $sizes) {

                    $attributeData = [];
                    $attributeData['attributeValue'] = $sizes;
                    $attributeData['productAttributeQuantity'] = ($sizes * 1000);
                    // will not use product
                    $attributeData['productAttributePrice'] = ($sizes * $pprice);
                    $attributeData['default'] = 0;
                    $this->saveProductCombinations($attributeData, $product);
                }
            }
            // Delete Attribues
            if (!empty(@$resultstodelete)) {
                $this->removeProductCombination($resultstodelete, $id);
            }
        }

        $data = $request->except('categories', 'productSizes', '_token', 'attribute', '_method', 'productSizes', 'default', 'image', 'productAttributeQuantity', 'productAttributePrice', 'attributeValue', 'combination');

        $data['slug'] = str_slug($request->input('name'));
        $data['languageType'] = Config::get('app.locale');
        $update_product_sku = date("Ymds") . 's' . $data['store_id'] . $data['mass_unit'] . 'p' . $id . 'b' . $data['brand_id'];
        $data['sku'] = $update_product_sku;

        if ($request->hasFile('cover')) {
            $data['cover'] = $productRepo->saveCoverImage($request->file('cover'));
        }

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }

        $productRepo->updateProduct($data);

        return redirect()->route('admin.products.edit', $id)
            ->with('message', 'Update successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $product = $this->productRepo->findProductById($id);
        $product->categories()->sync([]);
        $productAttr = $product->attributes();

        $productAttr->each(function ($pa) {
            DB::table('attribute_value_product_attribute')->where('product_attribute_id', $pa->id)->delete();
        });

        $productAttr->where('product_id', $product->id)->delete();

        $productRepo = new ProductRepository($product);
        $productRepo->removeProduct();

        return redirect()->route('admin.products.index')->with('message', 'Delete successful');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->productRepo->deleteFile($request->only('product', 'image'), 'uploads');
        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeThumbnail(Request $request)
    {
        $this->productRepo->deleteThumb($request->input('src'));
        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return boolean
     */
    private function saveProductCombinations($fields, Product $product): bool
    {

        $quantity = $fields['productAttributeQuantity'];
        $price = $fields['productAttributePrice'];

        $sale_price = null;
        if (isset($fields['sale_price'])) {
            $sale_price = $fields['sale_price'];
        }

        $attributeValues = $fields['attributeValue'];
        $productRepo = new ProductRepository($product);

        $hasDefault = $productRepo->listProductAttributes()->where('default', 1)->count();

        $default = 0;
        if (isset($fields['default'])) {
            $default = $fields['default'];
        }

        if ($default == 1 && $hasDefault > 0) {
            $default = 0;
        }

        $productAttribute = $productRepo->saveProductAttributes(
            new ProductAttribute(compact('quantity', 'price', 'sale_price', 'default'))
        );

        // save the combinations
        return collect($attributeValues)->each(function ($attributeValueId) use ($productRepo, $productAttribute) {
            $attribute = $this->attributeValueRepository->find($attributeValueId);
            return $productRepo->saveCombination($productAttribute, $attribute);
        })->count();
    }

    /**
     *
     * @param  $arrtibuteId (int)
     * @param  $productId (int)
     * @return boolean
     *
     */
    private function removeProductCombination($attributeId = [], $productId = 0)
    {
        $product = $this->productRepo->findProductById($productId);
        $productAttributes = $product->attributes()->get();
        if (!empty($attributeId)) {
            foreach ($attributeId as $attrId) {
                $pa = $productAttributes->where('id', $attrId)->first();
                $pa->attributesValues()->detach();
                $pa->delete();
            }
        }
        return true;

    }

    /**
     * @param array $data
     *
     * @return
     */
    private function validateFields(array $data)
    {
        $validator = Validator::make($data, [
            'productAttributeQuantity' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }

}
