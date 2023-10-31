<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        if(!Schema::hasTable('map_datas')){

            Schema::create('map_datas', function (Blueprint $table) {
                $table->id();

                //テーブル項目の設定
                $table->double('latitude',18,14);
                $table->double('longitude',18,14);
                $table->string('place_name');
                $table->string('url')->nullable();;
                $table->string('information')->nullable();;
                $table->string('sheet_A')->nullable();
                $table->string('sheet_B')->nullable();
                $table->integer('marker');

                $table->timestamps();
            });

        }

    }

    public function down(): void
    {
        Schema::dropIfExists('map_datas');
    }
};
