<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\InventoryChange;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $successCount = 0;

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Kiểm tra sản phẩm tồn tại
            if(Product::where('product_name', $row['product_name'])->exists()) {
                throw new \Exception("Sản phẩm '{$row['product_name']}' đã tồn tại trong hệ thống.");
            }

            // Tạo sản phẩm mới
            $product = Product::create([
                'product_name' => $row['product_name'],
                'description' => $row['description'] ?? null,
                'short_description' => $row['short_description'] ?? null,
                'price' => $row['price'],
                'category_id' => $row['category_id'],
                'weight' => $row['weight'] ?? null,
                'dimensions' => $row['dimensions'] ?? null,
                'material' => $row['material'] ?? null,
                'origin' => $row['origin'] ?? null,
                'warranty_period' => $row['warranty_period'] ?? null,
                'stock' => isset($row['stock']) ? $row['stock'] : 0
            ]);

            // Ghi nhận thay đổi kho hàng
            if (isset($row['stock']) && $row['stock'] > 0) {
                InventoryChange::create([
                    'product_id' => $product->product_id,
                    'quantity_change' => $row['stock'],
                    'reason' => 'Tạo mới sản phẩm',
                    'created_at' => now()
                ]);
            }

            // Xử lý nhiều ảnh
            if (isset($row['images']) && !empty($row['images'])) {
                $imageUrls = explode(',', str_replace(';', ',', $row['images']));
                foreach ($imageUrls as $index => $imageUrl) {
                    $imageUrl = trim($imageUrl);
                    if (empty($imageUrl)) continue;

                    try {
                        if ($this->isValidImageUrl($imageUrl)) {
                            $imageContent = file_get_contents($imageUrl);
                            $filename = 'products/' . Str::random(40) . '.jpg';
                            Storage::disk('public')->put($filename, $imageContent);

                            ProductImage::create([
                                'product_id' => $product->product_id,
                                'image_url' => $filename,
                                'is_primary' => ($index === 0)
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Log lỗi ảnh nhưng không dừng quá trình import
                        Log::error("Lỗi khi xử lý ảnh: " . $e->getMessage());
                    }
                }
            }

            DB::commit();
            $this->successCount++;
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:100', 'unique:products,product_name'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:50'],
            'material' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:255'],
            'warranty_period' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'string']
        ];
    }

    public function customValidationMessages()
    {
        return [
            'product_name.required' => 'Tên sản phẩm là bắt buộc',
            'product_name.unique' => 'Tên sản phẩm đã tồn tại',
            'product_name.string' => 'Tên sản phẩm phải là chuỗi ký tự',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 100 ký tự',
            'description.string' => 'Mô tả chi tiết phải là chuỗi ký tự',
            'short_description.string' => 'Mô tả ngắn phải là chuỗi ký tự',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 255 ký tự',
            'price.required' => 'Giá sản phẩm là bắt buộc',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên',
            'stock.min' => 'Số lượng tồn kho không được âm',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại',
            'weight.numeric' => 'Khối lượng phải là số',
            'weight.min' => 'Khối lượng không được âm',
            'dimensions.string' => 'Kích thước phải là chuỗi ký tự',
            'dimensions.max' => 'Kích thước không được vượt quá 50 ký tự',
            'material.string' => 'Chất liệu phải là chuỗi ký tự',
            'material.max' => 'Chất liệu không được vượt quá 100 ký tự',
            'origin.string' => 'Xuất xứ phải là chuỗi ký tự',
            'origin.max' => 'Xuất xứ không được vượt quá 255 ký tự',
            'warranty_period.integer' => 'Thời gian bảo hành phải là số nguyên',
            'warranty_period.min' => 'Thời gian bảo hành không được âm'
        ];
    }

    private function isValidImageUrl($url): bool
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }
}