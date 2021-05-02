<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string("text")->nullable();
            $table->integer("sender_id");
            $table->integer("receiver_id")->nullable();
            $table->integer("group_id")->nullable();
            $table->timestamps();

            $table->foreign("sender_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("receiver_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("group_id")->references("id")->on("groups")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
