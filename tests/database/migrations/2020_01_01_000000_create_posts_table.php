<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->unsignedBigInteger('post_author')->default(0)->index();
            $table->datetime('post_date')->default('0000-00-00 00:00:00')->index();
            $table->datetime('post_date_gmt')->default('0000-00-00 00:00:00');
            $table->longText('post_content');
            $table->text('post_title');
            $table->text('post_excerpt');
            $table->string('post_status', 20)->default('publish')->index();
            $table->string('comment_status', 20)->default('open');
            $table->string('ping_status', 20)->default('open');
            $table->string('post_password', 255)->default('');
            $table->string('post_name', 200)->default('')->index();
            $table->text('to_ping');
            $table->text('pinged');
            $table->datetime('post_modified')->default('0000-00-00 00:00:00');
            $table->datetime('post_modified_gmt')->default('0000-00-00 00:00:00');
            $table->longText('post_content_filtered');
            $table->unsignedBigInteger('post_parent')->default(0)->index();
            $table->string('guid', 255)->default('');
            $table->integer('menu_order')->default(0);
            $table->string('post_type', 20)->default('post');
            $table->string('post_mime_type', 100)->default('');
            $table->bigInteger('comment_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
