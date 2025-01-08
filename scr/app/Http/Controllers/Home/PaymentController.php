<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function payment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = (int)$request->input('total_amount');

        // Validate amount
        if ($amount < 10000 || $amount > 50000000) {
            return redirect()->back()->with('error', 'Số tiền giao dịch phải từ 10,000 VND đến 50,000,000 VND.');
        }

        Log::info('Payment amount: ' . $amount);

        $orderId = time() . "";
        $redirectUrl = route('payment.momo.callback'); // Thay đổi ở đây
        $ipnUrl = route('payment.momo.ipn');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (!empty($jsonResult) && isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        } else {
            Log::error('Momo payment error: ' . json_encode($jsonResult));
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi kết nối với MoMo. Vui lòng thử lại sau.');
        }
    }

    public function momoCallback(Request $request)
    {
        // Xử lý callback từ MoMo
        $resultCode = $request->input('resultCode');
        $orderId = $request->input('orderId');

        if ($resultCode == '0') {
            // Thanh toán thành công
            // Tìm đơn hàng dựa trên orderId và cập nhật trạng thái
            $order = Order::where('id', $orderId)->first();
            if ($order) {
                $order->order_status = 'processing';
                $order->payment_status = 'paid';
                $order->save();

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->final_amount,
                    'payment_method' => 'Momo',
                    'status' => 'completed',
                    'transaction_id' => $request->input('transId'),
                ]);

                return redirect()->route('orders.success', ['orderId' => $order->order_id]);
            }
        }

        // Thanh toán thất bại hoặc không tìm thấy đơn hàng
        return redirect()->route('checkout')->with('error', 'Thanh toán thất bại hoặc đơn hàng không tồn tại.');
    }

    public function ipn(Request $request)
    {
        // Handle MoMo IPN (Instant Payment Notification)
        Log::info('MoMo IPN received: ' . json_encode($request->all()));

        // Verify the signature and process the payment
        // Update the order status based on the IPN data

        return response('OK', 200);
    }
}
