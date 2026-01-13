<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddSlugToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });
        
        // Generate slugs for existing products
        $this->generateSlugsForExistingProducts();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
    
    /**
     * Generate slugs for existing products
     */
    private function generateSlugsForExistingProducts()
    {
        $products = DB::table('products')->get();
        
        foreach ($products as $product) {
            $slug = Str::slug($product->name);
            $originalSlug = $slug;
            $count = 1;
            
            while (DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }
}
