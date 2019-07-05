<?php

namespace App\Http\Controllers\Api\v1\Servicer\Orders;

use App\Http\Controllers\Controller;
use App\v1\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\v1\Addresses\Transformations\AddressTransformable;
use App\v1\Couriers\Courier;
use App\v1\Couriers\Repositories\CourierRepository;
use App\v1\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\v1\Customers\Customer;
use App\v1\Countries\Country;
use App\v1\Customers\Repositories\CustomerRepository;
use App\v1\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\v1\OrderStatuses\OrderStatus;
use App\v1\OrderStatuses\Repositories\Interfaces\OrderStatusRepositoryInterface;
use App\v1\OrderStatuses\Repositories\OrderStatusRepository;
use App\v1\Orders\Order;
use App\v1\Brands\Brand;
use \App\v1\ProductAttributes\ProductAttribute;
use App\v1\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\v1\Orders\Repositories\OrderRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Collection;

class OrderController extends Controller
{
    use AddressTransformable;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var OrderStatusRepositoryInterface
     */
    private $orderStatusRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CourierRepositoryInterface $courierRepository,
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        OrderStatusRepositoryInterface $orderStatusRepository, Request $request
    ) {
        $this->orderRepo = $orderRepository;
        $this->courierRepo = $courierRepository;
        $this->addressRepo = $addressRepository;
        $this->customerRepo = $customerRepository;
        $this->orderStatusRepo = $orderStatusRepository;

        //$this->middleware(['permission:update-order, guard:employee'], ['only' => ['edit', 'update']]);

        $this->userId = $request->header('userId') ? $request->header('userId') : "";
        $this->user_type = $request->header('userType') ? $request->header('userType') : "";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrders(Request $request)
    {

        $per_page = 10;
        if ($request->input('page') == 0) {
            $skip = 0;
            $take = 10;
        } else {
            $skip = $per_page * $request->input('page');
            $take = ((int) @$request->input('page') + 1) * 10;
        }

        $data = Order::with('products', 'customer','servicer' ,'address')->where(['servicerId' => $this->userId]);
        if (!empty($request->input('search_key'))) {
            $keyword = $request->input('search_key');
            $data->where(function ($query) use ($keyword) {
                $query->where('reference', 'LIKE', "%$keyword%")
                    ->orWhere('txn_id', 'LIKE', "%$keyword%")
                    ->orWhere('id', 'LIKE', "%$keyword%");
            });
        }

        $orders_count = $data->count();
        $list = $data->orderBy("created_at", "DESC")->skip($skip)->take($take)->get();
        $orders = $this->transFormOrder($list);

        $allOrders = [];
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {

                $order->id = $order->id;
                $order->reference = $order->reference;
                $order->txn_id = $order->txn_id <> null ? $order->txn_id: "";
                $order->invoice = $order->invoice != null ? $order->invoice : "";
                $order->label_url = $order->label_url != null ? $order->label_url : "";
                $order->tracking_number = $order->tracking_number != null ? $order->tracking_number : "";
                $order->customerName = $order->customer->name;
                $order->customerLat = $order->customer->customerLat <> null ? $order->customer->customerLat : "";
                $order->customerLong = $order->customer->customerLong  <> null ? $order->customer->customerLong : "";
                $order->order_status_id = $order->status->id;
                $order->order_status = $order->status->name;

                $order->service_provider = $order->servicer->name;


                unset($order->customer);
                unset($order->courier);
                unset($order->status);
                unset($order->courier_id);
                unset($order->customer_id);
                unset($order->discounts);
                unset($order->servicer);

                if (!empty($order->products)) {
                    $allproducts = [];
                    foreach ($order->products as $product) {
                        $product->id = $product->id;
                        $product->cover = asset("storage/app/public/$product->cover");
                        $product->product_description = $product->pivot->product_name;
                        $product->product_sku = $product->pivot->product_sku;
                        $product->product_quantity = $product->pivot->quantity;
                        $product->product_attribute_id = $product->pivot->product_attribute_id;
                        $product->order_id = $product->pivot->order_id;
                        $product->product_id = $product->pivot->product_id;


                        $product->product_attribute_name = "";
                        $product->product_attribute_value = "";
                        $pattr = ProductAttribute::find($product->pivot->product_attribute_id);
                        if(!is_null($pattr)){
                            foreach($pattr->attributesValues as $it){
                                $product->product_attribute_name =  $it->attribute->name;
                                $product->product_attribute_value = $it->value;
                            }
                        }

                        $brand = Brand::where('id', $product->brand_id)->first();
                        if(!is_null($brand)){
                                $product->brand_name =  $brand->name;
                        }
                        unset($product->length);
                        unset($product->width);
                        unset($product->sku);
                        unset($product->height);
                        unset($product->created_at);
                        unset($product->updated_at);
                        unset($product->sale_price);
                        unset($product->brand_id);
                        unset($product->quantity);
                        unset($product->distance_unit);
                        unset($product->pivot);
                        
                        $allproducts[] = $product;
                    }
                }

                if (!empty($order->address)) {
                        $order->address->country ="";
                        $country = Country::where('id', $order->address->country_id)->first();
                        if(!is_null($country)){
                            $order->address->country = $country->name;
                        }

                        unset($order->address->state_code);
                        unset($order->address->city);
                        unset($order->address->province_id);
                        unset($order->address->deleted_at);
                        
                }            
                $allOrders[$key] = $order;

            }
        }

        $response = [];
        $response['orders'] = $allOrders;
        $response['orders_count'] = $orders_count;

        $this->success("Servicer orders list", $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $orderId
     * @return \Illuminate\Http\Response
     */
    public function show($orderId)
    {
        $order = $this->orderRepo->findOrderById($orderId);
        $order->courier = $this->courierRepo->findCourierById($order->courier_id);
        $order->address = $this->addressRepo->findAddressById($order->address_id);

        $orderRepo = new OrderRepository($order);

        $items = $orderRepo->listOrderedProducts();

        return view('servicer.orders.show', [
            'order' => $order,
            'items' => $items,
            'customer' => $this->customerRepo->findCustomerById($order->customer_id),
            'currentStatus' => $this->orderStatusRepo->findOrderStatusById($order->order_status_id),
            'payment' => $order->payment,
            'user' => auth()->guard('servicer')->user(),
        ]);
    }

    /**
     * @param $orderId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($orderId)
    {
        $order = $this->orderRepo->findOrderById($orderId);
        $order->courier = $this->courierRepo->findCourierById($order->courier_id);
        $order->address = $this->addressRepo->findAddressById($order->address_id);

        $orderRepo = new OrderRepository($order);

        $items = $orderRepo->listOrderedProducts();

        return view('servicer.orders.edit', [
            'statuses' => $this->orderStatusRepo->listOrderStatuses(),
            'order' => $order,
            'items' => $items,
            'customer' => $this->customerRepo->findCustomerById($order->customer_id),
            'currentStatus' => $this->orderStatusRepo->findOrderStatusById($order->order_status_id),
            'payment' => $order->payment,
            'user' => auth()->guard('employee')->user(),
        ]);
    }

    /**
     * @param Request $request
     * @param $orderId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $orderId)
    {
        $order = $this->orderRepo->findOrderById($orderId);
        $orderRepo = new OrderRepository($order);

        if ($request->has('total_paid') && $request->input('total_paid') != null) {
            $orderData = $request->except('_method', '_token');
        } else {
            $orderData = $request->except('_method', '_token', 'total_paid');
        }

        $orderRepo->updateOrder($orderData);

        return redirect()->route('servicer.orders.edit', $orderId);
    }

    /**
     * Generate order invoice
     *
     * @param int $id
     * @return mixed
     */
    public function generateInvoice(int $id)
    {
        $order = $this->orderRepo->findOrderById($id);

        $data = [
            'order' => $order,
            'products' => $order->products,
            'customer' => $order->customer,
            'courier' => $order->courier,
            'address' => $this->transformAddress($order->address),
            'status' => $order->orderStatus,
            'payment' => $order->paymentMethod,
        ];

        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('invoices.orders', $data)->stream();
        return $pdf->stream();
    }

    /**
     * @param Collection $list
     * @return array
     */
    private function transFormOrder(Collection $list)
    {
        $courierRepo = new CourierRepository(new Courier());
        $customerRepo = new CustomerRepository(new Customer());
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus());

        return $list->transform(function (Order $order) use ($courierRepo, $customerRepo, $orderStatusRepo) {
            $order->courier = $courierRepo->findCourierById($order->courier_id);
            $order->customer = $customerRepo->findCustomerById($order->customer_id);
            $order->status = $orderStatusRepo->findOrderStatusById($order->order_status_id);
            return $order;
        })->all();
    }
}
