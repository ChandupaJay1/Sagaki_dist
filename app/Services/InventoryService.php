<?php

namespace App\Services;

use App\Models\InventoryLog;
use App\Models\InventorySummary;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Update inventory and log transaction.
     */
    public static function updateStock($productId, $locationNameOrId, $qty, $type, $referenceType, $referenceId, $description = null)
    {
        return DB::transaction(function () use ($productId, $locationNameOrId, $qty, $type, $referenceType, $referenceId, $description) {
            $product = Product::findOrFail($productId);
            
            // Resolve location_id
            $locationId = null;
            if (is_numeric($locationNameOrId)) {
                $locationId = $locationNameOrId;
            } else {
                $location = Location::where('name', $locationNameOrId)->first();
                if ($location) {
                    $locationId = $location->id;
                }
            }

            // Update Inventory Summary (Product total stock)
            $product->stock += $qty;
            $product->save();

            // Update Location Summary (Per site stock)
            if ($locationId) {
                $summary = InventorySummary::firstOrNew([
                    'product_id' => $productId,
                    'location_id' => $locationId
                ]);
                $summary->qty += $qty;
                $summary->save();
            }

            // Inventory Details (Log)
            InventoryLog::create([
                'product_id' => $productId,
                'location_id' => $locationId,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'change_qty' => $qty,
                'after_qty' => $product->stock,
                'type' => $type,
                'description' => $description
            ]);

            return true;
        });
    }

    /**
     * Reverse inventory update.
     */
    public static function reverseStock($productId, $locationNameOrId, $qty, $type, $referenceType, $referenceId, $description = null)
    {
        return self::updateStock($productId, $locationNameOrId, -$qty, $type, $referenceType, $referenceId, $description);
    }
}
