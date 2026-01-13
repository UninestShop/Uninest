<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'photos')) {
                $table->json('photos')->nullable();
            }
            if (!Schema::hasColumn('products', 'condition')) {
                $table->string('condition');
            }
            if (!Schema::hasColumn('products', 'is_sold')) {
                $table->boolean('is_sold')->default(false);
            }
            if (!Schema::hasColumn('products', 'meeting_location')) {
                $table->json('meeting_location')->nullable();
            }
            if (!Schema::hasColumn('products', 'views_count')) {
                $table->integer('views_count')->default(0);
            }
            if (!Schema::hasColumn('products', 'last_viewed_at')) {
                $table->timestamp('last_viewed_at')->nullable();
            }
            if (!Schema::hasColumn('products', 'reports_count')) {
                $table->integer('reports_count')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'photos',
                'condition',
                'is_sold',
                'meeting_location',
                'views_count',
                'last_viewed_at',
                'reports_count'
            ]);
        });
    }
};
