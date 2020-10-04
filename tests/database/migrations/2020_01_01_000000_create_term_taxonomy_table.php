<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermTaxonomyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_taxonomy', function (Blueprint $table) {
            $table->bigIncrements('term_taxonomy_id');
            $table->unsignedBigInteger('term_id')->default(0)->index();
            $table->string('taxonomy', 32)->default('')->index();
            $table->longText('description');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('term_taxonomy');
    }
}
