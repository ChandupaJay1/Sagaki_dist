<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_create_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('Product Master - Create');
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $vendor = Vendor::create(['name' => 'Test Vendor', 'email' => 'vendor@test.com', 'phone' => '1234567890']);
        
        Storage::fake('public');
        // Use create() with size instead of image() to avoid GD dependency
        $file = UploadedFile::fake()->create('product.jpg', 100);

        $productData = [
            'name' => 'Live Test Product',
            'code' => 'TEST-001',
            'sku' => '12345678',
            'category' => 'Electronics',
            'vendor_id' => $vendor->id,
            'floor' => '1st',
            'rack' => 'A1',
            'cost' => '1000',
            'max_sale_price' => '1500',
            'inventory_account' => '1300 - Inventory Asset',
            'cost_account' => '8000 - Cost of Goods Sold', // This field must match select option value purely text based in controller currently or just ignored if not validated strictly as "exists"
            'sales_account' => '7000 - Sales Income',
            'image_path' => $file,
            'is_purchase' => '1',
            'is_sale' => '1',
        ];

        $response = $this->actingAs($user)->post(route('products.store'), $productData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Live Test Product',
            'code' => 'TEST-001',
            'category' => 'Electronics',
        ]);

        // Verify image storage
        $product = Product::where('code', 'TEST-001')->first();
        Storage::disk('public')->assertExists($product->image_path);
    }
    
    public function test_validation_errors()
    {
        $user = User::factory()->create();
        
        // Missing required fields
        $response = $this->actingAs($user)->post(route('products.store'), []);
        
        $response->assertSessionHasErrors(['name', 'code']);
    }
    public function test_authenticated_user_can_update_product()
    {
        $user = User::factory()->create();
        $vendor = Vendor::create(['name' => 'Test Vendor', 'email' => 'vendor@test.com', 'phone' => '1234567890']);
        $product = Product::create([
            'name' => 'Original Product',
            'code' => 'ORG-001',
            'cost' => 1000,
            'vendor_id' => $vendor->id
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'code' => 'ORG-001', // Keep same code
            'cost' => 1200,
            'vendor_id' => $vendor->id,
            'image_path' => null // Ensure this is handled
        ];

        $response = $this->actingAs($user)->put(route('products.update', $product->id), $updateData);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'cost' => '1200.00'
        ]);
    }

    public function test_authenticated_user_can_delete_product()
    {
        $user = User::factory()->create();
        $vendor = Vendor::create(['name' => 'Test Vendor', 'email' => 'vendor@test.com', 'phone' => '1234567890']);
        $product = Product::create([
            'name' => 'To Delete Product',
            'code' => 'DEL-001',
            'cost' => 1000,
            'vendor_id' => $vendor->id
        ]);

        $response = $this->actingAs($user)->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }
}
