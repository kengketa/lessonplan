<?php

namespace Tests\Feature\Report;

use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

//    public function test_product_index_page_viewing_required_authenication()
//    {
//        $response = $this->get(route('dashboard.products.index'));
//        $response->assertSessionHasNoErrors();
//        //$response->assertStatus(302);
//    }
//
//    public function test_product_index_page_can_be_viewed_after_login()
//    {
//        $this->signInAdmin();
//        $response = $this->get(route('dashboard.products.index'));
//        $response->assertSessionHasNoErrors();
//        $response->assertStatus(200);
//        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
//        $this->assertEquals('/dashboard/products', $pageUrl);
//    }
//
//    public function test_product_create_page_can_be_viewed_after_login()
//    {
//        $this->signInAdmin();
//        Category::factory()->create();
//        SubCategory::factory()->create();
//        $response = $this->get(route('dashboard.products.create'));
//        $response->assertSessionHasNoErrors();
//        $response->assertStatus(200);
//        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
//        $this->assertEquals('/dashboard/products/create', $pageUrl);
//    }
//
//    public function test_product_show_page_can_be_viewed_after_login()
//    {
//        $this->signInAdmin();
//        //create mock data
//        $product = Product::factory()->create();
//
//        // call show page
//        $response = $this->get(route('dashboard.products.show', $product->hashid));
//        $response->assertSessionHasNoErrors();
//        $response->assertStatus(200);
//        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
//        $this->assertEquals('/dashboard/products/'.$product->hashid, $pageUrl);
//    }
//
//    public function test_product_edit_page_can_be_viewed_after_login()
//    {
//        $this->signInAdmin();
//        $product = Product::factory()->create();
//        // call edit page
//        $response = $this->get(route('dashboard.products.edit', $product->hashid));
//        $response->assertSessionHasNoErrors();
//        $response->assertStatus(200);
//        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
//        $this->assertEquals('/dashboard/products/'.$product->hashid.'/edit', $pageUrl);
//    }
//
//    public function test_product_can_be_created()
//    {
//        $this->signInAdmin();
//        // Create a temporary category
//        Category::factory()->create();
//        $subcategory = SubCategory::factory()->create();
//
//        $this->get(route('dashboard.products.create'));
//        $product = Product::latest()->first();
//
//        $mockProduct = Product::factory()->make();
//
//        //upload photos
//        $photos = [];
//        Storage::fake('local');
//        for ($i = 1; $i <= 3; $i++) {
//            $fileName = 'product-hoto-'.$i.'.jpeg';
//            $photoResponse = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//                'photo' => UploadedFile::fake()->image($fileName),
//            ])->getContent();
//            $this->assertEquals(true, json_decode($photoResponse)->success);
//            $photos[] = json_decode($photoResponse);
//        }
//        //Save Product
//        $response = $this->post(route('dashboard.products.store'), [
//            'id' => $product->hashid,
//            'subcategory_id' => $subcategory->id,
//            'name_th' => $mockProduct->name_th,
//            'name_en' => $mockProduct->name_en,
//            'description_th' => $mockProduct->description_th,
//            'description_en' => $mockProduct->description_en,
//            'slug' => $mockProduct->slug,
//            'photos' => $photos,
//        ]);
//
//        $response->assertSessionHasNoErrors();
//        $this->assertEquals(1, Product::count());
//        $this->assertDatabaseHas('products', [
//            'id' => $product->id,
//            'name_th' => $mockProduct->name_th,
//            'name_en' => $mockProduct->name_en,
//            'description_th' => $mockProduct->description_th,
//            'description_en' => $mockProduct->description_en,
//            'slug' => $mockProduct->slug,
//            'recommended' => false,
//        ]);
//    }
//
//    public function test_product_can_be_updated()
//    {
//        $this->signInAdmin();
//        $product = Product::factory()->create();
//        $oldPhotoCount = count(fractal(
//            Product::find(1),
//            new ProductTransformer()
//        )->includePhotos()->toArray()['photos']['data']);
//
//        //Add a new photo
//        $newPhoto = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//            'photo' => UploadedFile::fake()->image('update-image.png'),
//        ])->getContent();
//        $newPhotoCount = count(fractal(
//            Product::find(1),
//            new ProductTransformer()
//        )->includePhotos()->toArray()['photos']['data']);
//        $this->assertEquals($oldPhotoCount + 1, $newPhotoCount);
//
//        $photos = fractal(
//            $product->getMedia(Product::MEDIA_COLLECTION_PHOTO),
//            (new ProductPhotoTransformer())->withDeleteUrl($product)
//        )->toArray()['data'];
//
//        // update a Product
//        $response = $this->put(route('dashboard.products.update', $product->hashid), [
//            'id' => $product->id,
//            'subcategory_id' => $product->subcategory_id,
//            'name_th' => 'update name',
//            'name_en' => 'update name en',
//            'description_th' => 'update description',
//            'description_en' => 'update description en',
//            'slug' => 'update-slug',
//            'photos' => $photos,
//            'variants' => [],
//        ]);
//        $response->assertSessionHasNoErrors();
//        $response->assertStatus(302);
//        $this->assertDatabaseHas('products', [
//            'id' => $product->id,
//            'name_th' => 'update name',
//            'name_en' => 'update name en',
//            'description_th' => 'update description',
//            'description_en' => 'update description en',
//            'slug' => 'update-slug',
//        ]);
//    }
//
//    public function test_product_can_be_deleted()
//    {
//        $this->signInAdmin();
//        $product = Product::factory()->create();
//        //Delete product
//        $response = $this->delete(route('dashboard.products.destroy', $product->hashid));
//        $response->assertSessionHasNoErrors();
//        $this->assertEquals(0, Product::count());
//    }
//
//    public function test_product_relationship_with_variants_is_not_broken()
//    {
//        $product = Product::factory()->create();
//        ProductVariant::factory()->count(2)->create(['product_id' => $product->id]);
//        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->variants);
//    }
//
//    public function test_product_photo_upload_is_working()
//    {
//        $this->signInAdmin();
//        $product = Product::factory()->create();
//        Storage::fake('local');
//        // check jpg
//        $response = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//            'photo' => UploadedFile::fake()->image('test.jpg'),
//        ])->getContent();
//        $this->assertEquals(true, json_decode($response)->success);
//
//        // check jpeg
//        $response = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//            'photo' => UploadedFile::fake()->image('test.jpeg'),
//        ])->getContent();
//        $this->assertEquals(true, json_decode($response)->success);
//
//        // check png
//        $response = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//            'photo' => UploadedFile::fake()->image('test.png'),
//        ])->getContent();
//        $this->assertEquals(true, json_decode($response)->success);
//
//        // check pdf must return error
//        $response = $this->json('POST', route('dashboard.products.photo.store', $product->hashid), [
//            'photo' => UploadedFile::fake()->image('test.pdf'),
//        ])->getContent();
//        $this->assertEquals('The given data was invalid.', json_decode($response)->message);
//
//        // check multiple photos
//        $transformedProduct = fractal(
//            $product,
//            new ProductTransformer()
//        )->includePhotos()->toArray();
//        $this->assertEquals(3, count($transformedProduct['photos']['data']));
//    }
}
