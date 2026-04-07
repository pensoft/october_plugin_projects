<?php namespace Pensoft\Projects\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateProjectsTable Migration
 */
class CreateProjectsTable extends Migration
{
    public function up(): void
    {
        Schema::create('pensoft_projects_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->text('content')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pensoft_projects_projects');
    }
}
