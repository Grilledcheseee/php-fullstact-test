<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('my_client', function (Blueprint $table) {
            $table->id();
            $table->char('name', 250);
            $table->char('slug', 100)->unique();
            $table->string('is_project', 30)->default('0');
            $table->char('self_capture', 1)->default('1');
            $table->char('client_prefix', 4);
            $table->char('client_logo', 255)->default('no-image.jpg');
            $table->text('address')->nullable();
            $table->char('phone_number', 50)->nullable();
            $table->char('city', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
