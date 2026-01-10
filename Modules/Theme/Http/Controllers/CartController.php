<?php

namespace Modules\Theme\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Product;
use Modules\Booking\Entities\Customer;
use Modules\Booking\Entities\Booking;
use App\Events\MailOrderEvent;
use App\Cart;
use Session;
use Illuminate\Routing\Controller;

class CartController extends Controller
{


    public function resetSession(Request $request)
    {
        $request->session()->flush();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */


    public function addToCart(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);

        $cart = Session::has('cart') ? session()->get('cart') : [];

        //Nếu ko có sản phẩm chuyển về trang 404
        if (!$product) {
            abort(404);
        }
        if (!isset($cart[$id])) {
            $cart[$id] = [
                'item' => $product,
                'quantity' => 1
            ];
        } else {
            $cart[$id]['quantity'] += 1;
        }
        //Nếu giỏ hàng rỗng đây là sp đầu tiên
//        if(is_null($cart)) {
//            $cart = [
//                "item" => $product,
//                "quantity" => 1,
//            ];
//            $cart = [
//                    $id => [
//                        "item" => $product,
////                        "name" => $product->name,
//                        "quantity" => 1,
////                        "price" => $product->price,
////                        "image" => $product->image
//                    ]
//            ];
        //Nếu giỏ hàng rỗng thì kiểm tra xem sp có tồn tại ko, sau đó tăng số lượng
//        }else if(isset($cart)) {
//            $cart['quantity']++;
//        }
        /*else{
        //Nếu sp ko tồn tại trong giỏ hàng thì thêm vào giỏ hàng với số lượng = 1
            $cart[$id] = [
                "item" => $product,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }*/
        session()->put('cart', $cart);
        //Đếm số lượng sp trong giỏ hàng
        $carts = new Cart();
        $amount = $carts->getTotalQuantity();

        return response()->json([
            'data' => $amount,
            'message' => 'Thêm sản phẩm thành công !',
        ]);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            //Đếm số lượng sp trong giỏ hàng
            $carts = new Cart();
            $amount = $carts->getTotalQuantity();
            //Get total price cart
            $totalPrice = $carts->getTotalPriceCart();

            return response()->json([
                'data' => $cart,
                'amount' => $amount,
                'total' => $totalPrice,
                'message' => 'Cập nhật sản phẩm thành công!',
            ]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            //Đếm số lượng sp trong giỏ hàng
            $cart = new Cart();
            $amount = $cart->getTotalQuantity();
            //Get total price cart
            $totalPrice = $cart->getTotalPriceCart();
            //session()->flash('success', 'Xóa sản phẩm thành công!');
        }

        return response()->json([
            'total' => $totalPrice,
            'amount' => $amount,
            'message' => 'Xóa sản phẩm thành công!',
        ]);
    }

    //Quick Buy
    public function quickbuy(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits_between:10,11',
            'address' => 'required|min:5',
        ],
            [
                'name.required' => 'Họ và tên không được để trống!',
                'email.required' => 'Địa chỉ email không được để trống!',
                'email.email' => 'Địa chỉ email không hợp lệ!',
                'phone.required' => 'Số điện thoại không được để trống!',
                'phone.numeric' => 'Số điện thoại không hợp lệ!',
                'phone.digits_between' => 'Số điện thoại phải từ 10-11 số!',
                'address.required' => 'Địa chỉ giao hàng không được để trống!',
                'address.min' => 'Địa chỉ giao hàng ít nhất 5 kí tự!',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $requestData = $request->all();
        $customer = new Customer();
        $customer = $customer->where('phone', trim($requestData['phone']))->orWhere('email', trim($requestData['email']))->first();
        if ($customer) {
            if (empty($requestData['email'])) $requestData['email'] = $customer->email;
            if (empty($requestData['address'])) $requestData['address'] = $customer->address;
            $customer->update($requestData);
        } else {
            $customer = Customer::create($requestData);
        }
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'total_price' => $requestData['totalPrice'],
            'note' => $requestData['note'],
            'approve_id' => 8,
        ]);

        $booking->services()->attach($requestData['product_id'], ['price' => $requestData['price'], 'quantity' => $requestData['qty']]);
        //Send mail
        $order = [
            0 => [
                'item' => Product::findOrFail($requestData['product_id']),
                'quantity' => $requestData['qty'],
            ]
        ];
        event(new MailOrderEvent($order,$booking->id));

        return response()->json([
            'data' => $booking->code,
            'success' => 'ok',
            'message' => 'Đặt hàng thành công!',
        ]);

    }

    //Order cart
    public function order(Request $request)
    {

        $request->validate([
            'customer.name' => 'required|min:5',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|numeric|digits_between:10,11',
            'customer.address' => 'required|min:5',
        ],
            [
                'customer.name.required' => 'Họ và tên không được để trống!',
                'customer.email.required' => 'Địa chỉ email không được để trống!',
                'customer.email.email' => 'Địa chỉ email không hợp lệ!',
                'customer.phone.required' => 'Số điện thoại không được để trống!',
                'customer.phone.numeric' => 'Số điện thoại không hợp lệ!',
                'customer.phone.digits_between' => 'Số điện thoại phải từ 10-11 số!',
                'customer.address.required' => 'Địa chỉ giao hàng không được để trống!',
                'customer.address.min' => 'Địa chỉ giao hàng ít nhất 5 kí tự!',
            ]);
        $requestData = $request->all();

        //create or update customer
        $customer = new Customer();
        $customer = $customer->where('phone', trim($requestData['customer']['phone']))->orWhere('email', trim($requestData['customer']['email']))->first();
        if ($customer) {
            if (empty($requestData['customer']['email'])) $requestData['customer']['email'] = $customer->email;
            if (empty($requestData['customer']['address'])) $requestData['customer']['address'] = $customer->address;
            $customer->update($requestData['customer']);
        } else {
            $customer = Customer::create($requestData['customer']);
        }

        //Insert cart to booking
        $carts = session()->get('cart');
        //Get total price cart
        $totalPrice = 0;
        foreach ($carts as $id => $cart) {
            $totalPrice += $cart['item']['price'] * $cart['quantity'];
        }
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'total_price' => $totalPrice,
            'note' => $requestData['note'],
        ]);
        foreach ($carts as $key => $product) {
            $booking->services()->attach($key, ['price' => $product['item']['price'], 'quantity' => $product['quantity']]);
        }

        //Send mail
        $order = session('cart');

        Session::put('order_success', $booking->id);
        event(new MailOrderEvent($order, $booking->id));
        Session::flash('flash_message', __('theme::frontend.cart.order_success'));
        return redirect('thanh-toan');
    }

    public function addItem(Request $request)
    {
        $product = Product::find($request->id);
        $carts = new Cart();
        $carts->addItem($product);

        return redirect('/gio-hang');
    }

    //Add many cart
    public function addManyCart(Request $request)
    {
        $id = $request->id;
        $qty = $request->qty;
        $product = Product::find($id);
        $cart = session()->get('cart');
        //Nếu giỏ hàng rỗng đây là sp đầu tiên
        if (!$cart) {
            $cart = [
                $id => [
                    "item" => $product,
                    "name" => $product->name,
                    "quantity" => $qty,
                    "price" => $product->price,
                    "image" => $product->image
                ]
            ];

            session()->put('cart', $cart);
            //Nếu giỏ hàng rỗng thì kiểm tra xem sp có tồn tại ko, sau đó tăng số lượng
        } else if (isset($cart[$id])) {

            $cart[$id]['quantity'] += $qty;
            session()->put('cart', $cart);

        } else {
            //Nếu sp ko tồn tại trong giỏ hàng thì thêm vào giỏ hàng với số lượng = 1
            $cart[$id] = [
                "item" => $product,
                "name" => $product->name,
                "quantity" => $qty,
                "price" => $product->price,
                "image" => $product->image
            ];
            session()->put('cart', $cart);
        }

        //Đếm số lượng sp trong giỏ hàng
        $carts = new Cart();
        $amount = $carts->getTotalQuantity();

        return response()->json([
            'data' => $amount,
            'message' => 'Thêm sản phẩm thành công !',
        ]);
    }

    public function removeItem(Request $request)
    {
        $carts = new Cart();
        $carts->removeItem($request->id);

        return \response()->json(['carts' => $carts->getCart(), 'totalQty' => $carts->getTotalQty(), 'totalPrice' => $carts->getTotalPrice()]);
    }

    public function changeQtyItem(Request $request)
    {
        $id = $request->id;
        $qty = $request->qty;

        $tour = Tour::find($id);
        if ($tour->limit) {
            $people_exist = Theme::getExistPeopleLimitTour($tour);
            if ((int)$qty > (int)$people_exist) return \response()->json(['error_limit' => 'Số lượng giới hạn còn lại của sản phẩm ' . $tour->name . ' là: ' . $people_exist . ', vui lòng nhập số lượng bằng, nhỏ hơn số lượng giới hạn còn lại của sản phẩm hoặc liên hệ với nhân viên để được tư vấn thêm, xin cám ơn!']);
        }

        $carts = new Cart();
        $carts->setQtyItem($id, $qty);

        $item = $carts->getCart();
        $itemPrice = $item[$id]['item']->price * $item[$id]['qty'];

        return \response()->json(['itemPrice' => $itemPrice, 'totalQty' => $carts->getTotalQty(), 'totalPrice' => $carts->getTotalPrice(), 'error_limit' => '']);
    }

    public function checkout($module, Request $request)
    {
        if (!\Session::has('cart')) return;
        $payWithPaypal = false;

        $this->validate($request, [
            'customer.name' => 'required',
            'customer.address' => 'required',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|numeric|digits_between:7,13',
            'payment_method' => 'required'
        ]);

        //get Cart
        $cart = new Cart();
        $carts = $cart->getCart();
        $requestData = $request->all();

        //create or update customer
        $customer = new Customer();
        $customer = $customer->where('phone', trim($requestData['customer']['phone']))->orWhere('email', trim($requestData['customer']['email']))->first();

        if ($customer) {
            if (empty($requestData['customer']['email'])) $requestData['customer']['email'] = $customer->email;
            if (empty($requestData['customer']['address'])) $requestData['customer']['address'] = $customer->address;
            $customer->update($requestData['customer']);
        } else {
            $customer = Customer::create($requestData['customer']);
        }

        if ($requestData['payment_method'] === config('booking.payment_method.paypal')) {
            $payWithPaypal = true;
            $listItem = [];
        }
        //create booking
        $bookings_id = [];
        $unit_price = Booking::$defaultParams['unit_price'];
        foreach ($carts as $key => $tour) {
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'adult_number' => $tour['qty'],
                'total_price' => $tour['qty'] * $tour['item']->price,
                'is_vat' => 1,
                'note' => $requestData['note'],
                'payment_method_id' => PaymentMethod::where('code', $requestData['payment_method'])->value('id'),
                'status_id' => Status::orderBy('index')->first()->id
            ]);
            $booking->services()->attach([$key => ['price' => $tour['item']->price]]);

            //Nếu có ngày xuất phát
            if (!empty($tour['item']->departure_date)) {
                $bookingPropertyId = BookingProperty::where('module', 'tour')->where('key', 'departure_date')->value('id');
                $booking->properties()->attach($bookingPropertyId, ['value' => $tour['item']->departure_date]);
            }

            //lưu booking id để thông báo
            $bookings_id[] = $booking->id;

            //Lấy id booking truyền vào item tương ứng paypal
            if ($payWithPaypal) {
                $listItem[$key] = new Item();
                $listItem[$key]->setName($tour['item']->name)
                    ->setCurrency('USD')
                    ->setSku($booking->id)
                    ->setQuantity($tour['qty'])
//                ->setPrice($tour['item']->price/23000);
                    ->setPrice(round(($tour['item']->price) / $unit_price, 2, PHP_ROUND_HALF_UP));
            }
        }

        event(new BookingEvent($bookings_id));

        \Session::put('paypal_success', $bookings_id);
        //Xóa giỏ hàng khi đã Tạo Booking
        Session::forget('cart');

        //Pay with paypal
        if ($payWithPaypal) {
            $this->initPaypal();
            //A resource representing a Payer that funds a payment For PayPal account payments
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            //(Optional) Lets you specify item wise information
            $values = Arr::flatten($listItem);
            $item_list = new ItemList();
            $item_list->setItems($values);

            $total_price = null;
            foreach ($item_list->getItems() as $item) {
                $item_price = (float)$item->getPrice() * (float)$item->getQuantity();
                $total_price += $item_price;
            }

            //Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($total_price);

            //A transaction defines the contract of a payment – what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');

            //Set the URLs that the buyer must be redirected to after payment approval/ cancellation
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(\URL::route('check-paypal'))/** Specify return URL **/
            ->setCancelUrl(\URL::route('check-paypal'));

            //A Payment Resource; create one using the above types and intent set to ‘sale’
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

            try {
                $payment->create($this->_api_context);
            } catch (PayPalConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    dd($ex);
                } else {
                    \Session::put('paypal_success_error', 'Có thế một số sự cố đã xảy ra dẫn đến việc không thể liên kết đến trang thanh toán Paypal, xin lỗi vì sự bất tiện này!');
                    return \redirect('thanh-toan');
                }
            }

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            \Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }

            \Session::put('paypal_success_error', 'Một số sự cố đã xảy ra dẫn đến việc không thể liên kết đến trang thanh toán Paypal, xin lỗi vì sự bất tiện này!');
            return \redirect('thanh-toan');
        }

        //Pay with cod
        \Session::put('paypal_success', $bookings_id);
        return redirect('thanh-toan');
    }

    public function checkPaypal()
    {
        $this->initPaypal();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            // chữ kí không hợp lệ
            $booking_ids = Session::get('paypal_success');
            \Session::forget('paypal_success');
            //Xoá booking đã lưu
            foreach ($booking_ids as $bid) {
                $book = Booking::find($bid);
                $book->forceDelete();
            }
            return redirect('/booking-tour');
        }

        $payment = Payment::get($payment_id, $this->_api_context);
//        dump($payment['transactions']);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        //Lấy các booking id từ paypal
        //Cập nhật trạng thái booking - đã thanh toán (Lấy status mặc định)
        $bookings_id = [];
        foreach ($result->getTransactions()[0]->getItemList()->getItems() as $tour) {
            $booking = Booking::find($tour->getSku());
            if ($tour->getTax() == Booking::$defaultParams['pay_success_id_status_50']) {
                $booking->status_id = Booking::$defaultParams['pay_success_id_status_50'];
            } else {
                $booking->status_id = Booking::$defaultParams['pay_success_id_status'];
            }
            $booking->save();

            $bookings_id[] = $tour->getSku();
        }

        event(new BookingEvent($bookings_id));

        \Session::forget('moduleBook');

        if ($result->getState() == 'approved') {
            return \redirect('thanh-toan');
        }

        return \redirect('thanh-toan');
    }

    public function checkVNPay(Request $request)
    {
        // lay ma booking da duoc luu
        $booking_ids = \Session::get('paypal_success');
        // kiem tra ket qua tra ve de hien thi thong bao ket qua
        // success chuyen ve trang ket qua thanh toan
        // fail thong bao va chuyen ve trang truoc do
//        $result_msg = "";

        // nếu tồn tại lỗi thì lưu biến lỗi của bên thanh toán vào session hoặc log, không làm gì.

        try {
            $inputData = array();
            $data = $_REQUEST;
            foreach ($data as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }

            $vnp_SecureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHashType']);
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . $key . "=" . $value;
                } else {
                    $hashData = $hashData . $key . "=" . $value;
                    $i = 1;
                }
            }
            $secureHash = hash('sha256', config('vnpay.vnp_HashSecret') . $hashData);
            //Check Orderid
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {
                switch ($inputData['vnp_ResponseCode']) {
                    case "00":
                        //Thanh toán thành công
                        //Xóa giỏ hàng
                        \Session::forget('book');
                        //Gửi email tới người dùng.
                        event(new BookingEvent($booking_ids));
                        \Session::forget('moduleBook');
                        //cập nhật trạng thái đã thanh toán cho đơn hàng
                        foreach ($booking_ids as $bid) {
                            $book = Booking::find($bid);
                            $book->status_id = Booking::$defaultParams['pay_success_id_status'];
                            $book->save();
                        }
                        // chuyển về trang thanh toán thành công
                        return \redirect('thanh-toan');
                        break;
                    case "01":
//                        $result_msg = "Giao dịch đã tồn tại";
                        break;
                    case "02":
//                        $result_msg = "Merchant không hợp lệ";
                        break;
                    case "03":
//                        $result_msg = "Dữ liệu gửi sang không đúng định dạng";
                        break;
                    case "04":
//                        $result_msg = "Khởi tạo GD không thành công do Website đang bị tạm khóa";
                        break;
                    case "05":
//                        $result_msg = "Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch";
                        break;
                    case "13":
//                        $result_msg = "Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.";
                        break;
                    case "07":
//                        $result_msg = "Giao dịch bị nghi ngờ là giao dịch gian lận";
                        break;
                    case "09":
//                        $result_msg = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.";
                        break;
                    case "10":
//                        $result_msg = "Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần";
                        break;
                    case "11":
//                        $result_msg = "Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.";
                        break;
                    case "12":
//                        $result_msg = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.";
                        break;
                    case "51":
//                        $result_msg = "Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.";
                        break;
                    case "65":
//                        $result_msg = "Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.";
                        break;
                    case "08":
//                        $result_msg = "Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.";
                        break;
                    case "99":
//                        $result_msg = "Giao dịch không thành công";
                        break;
                    default:
                        \Session::forget('paypal_success');
                        //Xoá booking đã lưu
                        foreach ($booking_ids as $bid) {
                            $book = Booking::find($bid);
                            $book->forceDelete();
                        }
                        return redirect('/booking-tour');
                        break;
                }
            } else {
                // chữ kí không hợp lệ
                \Session::forget('paypal_success');
                //Xoá booking đã lưu
                foreach ($booking_ids as $bid) {
                    $book = Booking::find($bid);
                    $book->forceDelete();
                }
                return redirect('/booking-tour');
            }
        } catch (Exception $e) {
            \Session::forget('paypal_success');
            //Xoá booking đã lưu
            foreach ($booking_ids as $bid) {
                $book = Booking::find($bid);
                $book->forceDelete();
            }
            return redirect('/booking-tour');
        }
    }

    public function loadCondition(Request $request)
    {
        $code = $request->code;
        $payment = \App\PaymentMethod::where('code', $code)->first();
        return \response()->json(['description' => $payment->description]);
    }

    public function rollbackBook(Request $request)
    {
        try {
            $book_id = Session::get('paypal_success');
            \Session::forget('paypal_success');
            // neu ton tai session thi xoa book
            if (isset($book_id) && !isEmpty($book_id[0])) {
                $book = Booking::find($book_id[0]);
                $book->forceDelete();
            }
            return \response()->json([
                'success' => true
            ]);
        } catch (Exception $ex) {
            return \response()->json([
                'success' => false
            ]);
        }

    }


}