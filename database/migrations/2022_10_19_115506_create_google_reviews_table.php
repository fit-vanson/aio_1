<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->index();
            $table->string('reviewId')->index();
            $table->string('authorName')->nullable();
            $table->text('userComment')->nullable();
            $table->string('reviewerLanguage')->nullable();
            $table->integer('thumbsDownCount')->nullable();
            $table->integer('thumbsUpCount')->nullable();
            $table->integer('starRating')->nullable();
            $table->text('deviceMetadata')->nullable();
            $table->integer('lastModifiedUser')->nullable();
            $table->text('developerComment')->nullable();
            $table->integer('lastModifiedDeveloper')->nullable();



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
        Schema::dropIfExists('google_reviews');
    }
}
