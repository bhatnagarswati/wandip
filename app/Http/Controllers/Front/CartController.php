<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Carts\Requests\AddToCartRequest;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\ProductAttributes\Repositories\ProductAttributeRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Stores\Store;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ProductTransformable;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepo;

    /**
     * CartController constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     * @param CourierRepositoryInterface $courierRepository
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        CourierRepositoryInterface $courierRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->courierRepo = $courierRepository;
        $this->productAttributeRepo = $productAttributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courier = $this->courierRepo->findCourierById(request()->session()->get('courierId', 1));
        //$shippingFee = $this->cartRepo->getShippingFee($courier);

        return view('front.carts.cart', [
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'shippingFee' => $this->cartRepo->getShippingCharges(),
            'total' => $this->cartRepo->getTotal(2, $this->cartRepo->getShippingCharges()),
        ]);
    }

    // Validate cart
    public function checkCartItems(Request $request)
    {

        // Get cart items
        $cartItems = $this->cartRepo->getCartItems();

        $p_id = $request->input('product_id');
        $check_product = $this->productRepo->findProductById($p_id);
        $check_servicer = Store::with('service_provider')->where('id', $check_product->store_id)->first();
        $check_servicer_id = $check_servicer->service_provider->id;
        $check_servicer_name = $check_servicer->service_provider->name;
        $response = [];
        $flag = true;
        $old_cart_servicer = "";
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $product = $this->productRepo->findProductById($item->id);
                $servicer = Store::with('service_provider')->where('id', $product->store_id)->first();
                $servicer_id = $servicer->service_provider->id;
                $servicer_name = $servicer->service_provider->name;
                if ($servicer_id != $check_servicer_id) {
                    $flag = false;
                    $old_cart_servicer = $servicer_name;
                    break;
                }
            }
        }
        $response = ['status' => $flag, 'old_servicer' => $old_cart_servicer, 'new_servicer' => $check_servicer_name];
        echo json_encode($response);
    }

    public function clearCartItems(Request $request)
    {
        // clear cart items
        $cartItems = $this->cartRepo->clearCart();
        $response = ['status' => true];
        echo json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        $product = $this->productRepo->findProductById($request->input('product'));

        $qty = $request->input('quantity');

        if ($product->attributes()->count() > 0) {
            $productAttr = $product->attributes()->where('default', 1)->first();
            /* if (isset($productAttr->sale_price)) {
        $product->price = $productAttr->price;

        if (!is_null($productAttr->sale_price)) {
        $product->price = $productAttr->sale_price;
        }
        } */
        }

        $options = [];
        if ($request->has('productSizes')) {

            $attr = $this->productAttributeRepo->findProductAttributeById($request->input('productSizes'));
            //$product->price = $attr->price;
            $options['product_attribute_id'] = $request->input('productSizes');
            $product_attr = $attr->attributesValues->toArray();
            if (!empty($product_attr)) {

                $product_price = $product->price;
                $attr_val = $product_attr[0]['value'];
                $final_price = ($product_price * $attr_val * $qty);
                $product->price = $final_price;
                $product_attr[0]['value'] = $product_attr[0]['value'] . " " . $product->mass_unit;

            }
            $options['combination'] = $product_attr;
        }

        $this->cartRepo->addToCart($product, $request->input('quantity'), $options);

        return redirect()->route('cart.index')->with('message', 'Add to cart successful');
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
        $this->cartRepo->updateQuantityInCart($id, $request->input('quantity'));

        request()->session()->flash('message', 'Update cart successful');
        return redirect()->route('cart.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRepo->removeToCart($id);

        request()->session()->flash('message', 'Removed to cart successful');
        return redirect()->route('cart.index');
    }
}
