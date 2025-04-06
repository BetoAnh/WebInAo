<?php
namespace Betod\Webinao\Controllers;

use Betod\Webinao\Models\OrderDetail;
use Betod\Webinao\Models\Products as Product;
use Betod\Webinao\Models\Variants as Variant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Betod\Webinao\Models\Orders;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use System\Models\File;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected function saveBase64Image($base64String)
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $tempFileName = Str::random(10) . '.png';
        $tempPath = temp_path($tempFileName);
        file_put_contents($tempPath, $fileData);
        $file = new File();
        $file->fromFile($tempPath);
        $file->save();
        return $file;
    }

    public function createOrder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'province' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'subdistrict' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'diffname' => 'nullable|string|max:255',
                'diffphone' => 'nullable|string|max:20',
                'diffprovince' => 'nullable|string|max:255',
                'diffdistrict' => 'nullable|string|max:255',
                'diffsubdistrict' => 'nullable|string|max:255',
                'diffaddress' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000',
                'terms' => 'required|boolean',
                'paymenttype' => 'required|integer',
                'differentaddresschecked' => 'required|boolean',
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.variant' => 'nullable|array',
                'items.*.frontImage' => 'nullable|string',
                'items.*.backImage' => 'nullable|string',
            ]);

            $totalPrice = array_reduce($validatedData['items'], function ($sum, $item) {
                return $sum + $item['price'] * $item['quantity'];
            }, 0);

            // Tạo mã đơn hàng
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $propertyData = Arr::except($validatedData, ['items', 'differentaddresschecked', 'terms', 'user_id']);

            // Chuyển dữ liệu thành JSON trước khi lưu vào cột text
            $order = Orders::create([
                'user_id' => $validatedData['user_id'],
                'order_code' => $orderCode,
                'price' => $totalPrice,
                'property' => $propertyData,
            ]);

            foreach ($validatedData['items'] as $item) {
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'variant' => $item['variant'],
                    'price' => ($item['price'] * $item['quantity']),
                ]);

                if (!empty($item['frontImage'])) {
                    $orderDetail->frontImage = $this->saveBase64Image($item['frontImage']);
                }
                // Gán ảnh backImage (base64)
                if (!empty($item['backImage'])) {
                    $orderDetail->backImage = $this->saveBase64Image($item['backImage']);
                }

                $orderDetail->save();

                $product = Product::find($item['product_id']);
                $variant = Variant::where('product_id', $item['product_id'])->first();
                if ($product) {
                    $variant->stock -= $item['quantity'];
                    $product->sold_out += $item['quantity'];
                    $product->save();
                }
            }

            return response()->json([
                'message' => 'Order created successfully!',
                'order_code' => $order->order_code
            ], 201);
        } catch (\Exception $e) {
            // Log exception details
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while creating the order.'
            ], 500);
        }
    }
}