<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWoocommerceAttributeTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woocommerce_attribute_taxonomies', function (Blueprint $table) {
            $table->bigIncrements('attribute_id');
            $table->string('attribute_name', 200)->index();
            $table->string('attribute_label', 200)->nullable();
            $table->string('attribute_type', 20);
            $table->string('attribute_orderby', 20);
            $table->tinyInteger('attribute_public')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woocommerce_attribute_taxonomies');
    }
}
