<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('password');
            $table->string('address')->nullable();
            $table->tinyinteger('country');
            $table->tinyinteger('state');
            $table->tinyinteger('city');
            $table->enum('verified',['yes','no']);
            $table->integer('wallet_amount')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL on update CURRENT_TIMESTAMP'));
            $table->tinyinteger('created_by');
            $table->tinyinteger('updated_by');
            $table->enum('is_active',['yes','no']);
            $table->enum('is_deleted',['yes','no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_models');
    }
}
