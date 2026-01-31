<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added for DB facade usage

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size', 50);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // Migrate Data
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            // Handle 'sizes' column if it exists (it should, based on previous steps)
            // Note: DB::table returns object, properties correspond to columns
            $sizesStr = $product->sizes ?? $product->size ?? null; // Handle potential naming confusion if any, though current schema is 'sizes'
            
            if (!empty($sizesStr)) {
                $sizes = array_map('trim', explode(',', $sizesStr));
                $first = true;
                foreach ($sizes as $size) {
                    if (empty($size)) continue;
                    DB::table('product_variants')->insert([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => $first ? $product->stock : 0, // Assign total stock to first variant
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $first = false;
                }
            } else {
                 // Create default variant if no sizes
                 DB::table('product_variants')->insert([
                    'product_id' => $product->id,
                    'size' => 'All Size',
                    'stock' => $product->stock,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sizes', 'stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sizes')->nullable();
            $table->integer('stock')->default(0);
        });

        // Restore data (Best Effort)
        $variants = DB::table('product_variants')->get()->groupBy('product_id');
        
        foreach ($variants as $productId => $productVariants) {
            $totalStock = $productVariants->sum('stock');
            $sizesList = $productVariants->pluck('size')->join(', ');
            
            DB::table('products')
                ->where('id', $productId)
                ->update([
                    'stock' => $totalStock,
                    'sizes' => $sizesList
                ]);
        }

        Schema::dropIfExists('product_variants');
    }
};
