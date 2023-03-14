<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('title');
            $table->string('domain')->unique();
            $table->string('theme')->unique();
            $table->string('quotes')->nullable();
            $table->string('info')->nullable();
            $table->string('closing')->nullable();
            $table->string('male_name');
            $table->string('male_nickname');
            $table->string('male_foto')->nullable();
            $table->string('male_ig_id')->nullable();
            $table->string('male_fb_id')->nullable();
            $table->string('male_tw_id')->nullable();
            $table->string('male_tt_id')->nullable();
            $table->string('male_mother_name');
            $table->string('male_father_name');
            $table->string('male_family_order')->nullable();
            $table->string('female_name');
            $table->string('female_nick_name');
            $table->string('female_foto')->nullable();
            $table->string('female_mother_name');
            $table->string('female_father_name');
            $table->string('female_ig_id')->nullable();
            $table->string('female_fb_id')->nullable();
            $table->string('female_tw_id')->nullable();
            $table->string('female_tt_id')->nullable();
            $table->string('female_family_order')->nullable();
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
        Schema::dropIfExists('invitations');
    }
}
