<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('reported_at')->nullable();
            $table->string('report_reason')->nullable();
            $table->boolean('has_unread_seller_messages')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
