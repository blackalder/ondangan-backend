<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('invitation_id')
                    ->constrained('invitations')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('title');
            $table->text('address');
            $table->string('landmark');
            $table->string('location');
            $table->date('date');
            $table->time('start_time');
            $table->string('end_time');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
