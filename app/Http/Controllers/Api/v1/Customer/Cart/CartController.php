<?php

namespace App\Http\Controllers\Api\v1\Customer\Cart;

use App\Http\Controllers\Controller;
use App\v1\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\v1\Carts\Requests\AddToCartRequest;
use App\v1\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\v1\CustomerCarts\CustomerCart;
use App\v1\ProductAttributes\Repositories\ProductAttributeRepositoryInterface;
use App\v1\Products\Product;
use App\v1\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\v1\Products\Repositories\ProductRepository;
use App\v1\Products\Transformations\ProductTransformable;
use App\v1\Stores\Store;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ProductTransformable;

    public $successStatus = 200;
    public $userId = "";
    public $user_type = "";

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
        ProductAttributeRepositoryInterface $productAttributeRepository,
        Request $request
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->courierRepo = $courierRepository;
        $this->productAttributeRepo = $productAttributeRepository;
        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCart(Request $request)
    {
      /*   $this->validation(
            $request->all(),
            [
                "cartId" => "required",
            ]
        ); */

        //$cartId = $request->input('cartId');
        $customerCart = CustomerCart::where(['customerId' => $this->userId])->first();
        if ($customerCart) {
            if (!empty($customerCart->cartContent)) {
                $customerCart->cartContent = unserialize($customerCart->cartContent);
            } else {
                $customerCart->cartContent = [];
            }
            $this->success('Cart Items.', $customerCart, $this->successStatus);
        } else {
            $this->error('Cart does not exist', []);
        }

    }

    // Validate cart
    public function checkCartItems($product_id = 0)
    {

        // Get cart items
        $cartItems = $this->cartRepo->getCartItems();
        $p_id = $product_id;
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
        return $response;
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function addItemsToCart(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                "cartData" => "required|array",

            ]
        );

        $cartData = $request->input('cartData');
        $checkCart = CustomerCart::where('customerId', $this->userId)->first();
        // If cart exist
        if (!$checkCart) {
            // Process cart data
            $cartRes = $this->processCart($cartData);

            // Save data to Cart DB
            $cartId = uniqid('adb');
            $customerCart = new CustomerCart();
            $customerCart->cartId = $cartId;
            $customerCart->customerId = $this->userId;
            $customerCart->cartContent = $cartRes;
            $customerCart->save();

            $customerCart->cartContent = unserialize($customerCart->cartContent);

            $this->success('Add to cart successfully.', $customerCart);

        } else {
            // If cart does not exist
            $checkCart->cartContent = unserialize($checkCart->cartContent);

            $existedItems = [];
            if (!empty($checkCart->cartContent)) {
                foreach ($checkCart->cartContent['cartItems'] as $existedItem) {
                    $existed['productId'] = $existedItem->id;
                    $existed['quantity'] = $existedItem->qty;
                    $existed['productSize'] = $existedItem->options['product_attribute_id'];
                    $existedItems[] = $existed;
                }
            }
            if (!empty($existedItems)) {
                $final_carddata = array_merge($cartData, $existedItems);
            } else {
                $final_carddata = $cartData;
            }

            $cartRes = $this->processCart($final_carddata);
            $checkCart->cartContent = $cartRes;
            $checkCart->save();

            // unserialize
            $checkCart->cartContent = unserialize($checkCart->cartContent);

            $this->success('Add to cart successfully.', $checkCart);
        }
    }

    public function processCart($cartItems = [])
    {

        if (!empty($cartItems)) {

            $this->cartRepo->clearCart();

            // Add cart items into Cart when user logged in
            foreach ($cartItems as $cartItem) {

                $product_id = $cartItem['productId'];
                $product = $this->productRepo->findProductById($product_id);
                $res = $this->checkCartItems($product_id);
                if ($res['status'] == false) {

                    $response = [];
                    $response['old_servicer'] = $res['old_servicer'];
                    $response['new_servicer'] = $res['new_servicer'];
                    $this->success("Cart with mulltiple service provider products exist.", $response, $this->successStatus);

                }
                $qty = $cartItem['quantity'];
                $productSizes = $cartItem['productSize'];

                $options = [];
                if ($cartItem['productSize']) {

                    $attr = $this->productAttributeRepo->findProductAttributeById($productSizes);

                    $options['product_attribute_id'] = $productSizes;
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

                $this->cartRepo->addToCart($product, $qty, $options);
            }

            $cartItems = $this->cartRepo->getCartItemsTransformed();
            $allItems = [];
            if (!empty($cartItems)) {
                foreach ($cartItems as $items) {
                    $allItems[] = $items;
                }
            }

            $cartResponse = [
                'cartItems' => $allItems,
                'subtotal' => $this->cartRepo->getSubTotal(),
                'tax' => $this->cartRepo->getTax(),
                'shippingFee' => $this->cartRepo->getShippingCharges(),
                'total' => $this->cartRepo->getTotal(2, $this->cartRepo->getShippingCharges()),
            ];

            $serialize_cartContent = serialize($cartResponse);
            return $serialize_cartContent;
        } else {

            $this->error("Cart items not found.", [], $this->successStatus);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCart(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                "cartData" => "required|array",
                "cartId" => "required|string",

            ]
        );
        $cartData = $request->input('cartData');
        $cartId = $request->input('cartId');
        $customerCart = CustomerCart::where(['customerId' => $this->userId, 'cartId' => $cartId])->first();
        if ($customerCart) {
            // Process cart data

            $customerCart->cartContent = unserialize($customerCart->cartContent);

            $updateItems = [];
            if (!empty($customerCart->cartContent)) {
                foreach ($customerCart->cartContent['cartItems'] as $existedItem) {

                    if ($existedItem->id == $cartData[0]['productId']) {

                        $existed['productId'] = $existedItem->id;
                        $existed['quantity'] = $cartData[0]['quantity'];
                        $existed['productSize'] = $existedItem->options['product_attribute_id'];

                    } else {
                        $existed['productId'] = $existedItem->id;
                        $existed['quantity'] = $existedItem->qty;
                        $existed['productSize'] = $existedItem->options['product_attribute_id'];

                    }

                    $updateItems[] = $existed;
                }
            }

            $cartRes = $this->processCart($updateItems);
            $customerCart->cartContent = $cartRes;
            $customerCart->save();

            // unserialize
            $customerCart->cartContent = unserialize($customerCart->cartContent);
            $this->success('Update cart successful.', $customerCart);
        } else {
            $this->error('Cart does not exist', []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeCartItem(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                "rowId" => "required",
                "cartId" => "required",
            ]
        );

        $rowid = $request->input('rowId');
        $cartId = $request->input('cartId');

        $customerCart = CustomerCart::where(['customerId' => $this->userId, 'cartId' => $cartId])->first();

        if ($customerCart) {

            $customerCart->cartContent = unserialize($customerCart->cartContent);
            $updateItems = [];
            if (!empty($customerCart->cartContent)) {
                foreach ($customerCart->cartContent['cartItems'] as $existedItem) {

                    if ($existedItem->rowId != $rowid) {
                        $existed['productId'] = $existedItem->id;
                        $existed['quantity'] = $existedItem->qty;
                        $existed['productSize'] = $existedItem->options['product_attribute_id'];
                        $updateItems[] = $existed;
                    }
                }
            }

            $cartRes = "";
            if (!empty($updateItems)) {
                $cartRes = $this->processCart($updateItems);
            }

            $customerCart->cartContent = $cartRes;
            $customerCart->save();
            // unserialize
            if (!empty($customerCart->cartContent)) {
                $customerCart->cartContent = unserialize($customerCart->cartContent);
            } else {
                $customerCart->cartContent = [];
            }

            $this->success('Removed item from cart successful.', $customerCart);
        } else {
            $this->error('Cart does not exist', []);
        }

    }

    public function deleteCart(Request $request)
    {

        $this->validation(
            $request->all(),
            [
                "cartId" => "required",
            ]
        );
        $cartId = $request->input('cartId');
        $customerCart = CustomerCart::where(['customerId' => $this->userId, 'cartId' => $cartId])->first();
        if ($customerCart) {
            $customerCart->delete();
            $this->success("Cart deleted successful.", "", $this->successStatus);
        } else {
            $this->error("Cart not found.", "");
        }
    }
    
}
