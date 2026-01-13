<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('mrp', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('condition');
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('pending');
            $table->json('photos')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->json('meeting_location')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->integer('reports_count')->default(0);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
