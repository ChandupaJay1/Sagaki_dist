<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class Product extends Model
{
    use GeneratesCode;
    protected $fillable = [
        'name', 'code', 'sku', 'is_main_product', 'main_product_id',
        'category', 'sub_category', 'brand', 'model', 'description',
        'floor', 'rack', 'row', 'bin', 'location',
        'reorder_point', 'alert_quantity', 'qty_in_bulk',
        'is_purchase', 'is_sale', 'is_production', 'is_serialized',
        'is_stock_report', 'is_price_level', 'is_multi_price',
        'project', 'supplier_warranty', 'customer_warranty', 'vendor_id',
        'inventory_account', 'cost_account', 'sales_account',
        'cost', 'unit', 'max_sale_price', 'min_sale_price',
        'max_wholesale_price', 'min_wholesale_price', 'discount_percentage',
        'image_path'
    ];

    public function mainProduct()
    {
        return $this->belongsTo(Product::class, 'main_product_id');
    }

    public function subProducts()
    {
        return $this->hasMany(Product::class, 'main_product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
