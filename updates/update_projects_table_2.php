<?php namespace Pensoft\Projects\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * UpdateProjectsTable Migration
 */
class UpdateProjectsTable2 extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_projects_projects', function(Blueprint $table)
        {
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->string('slug')->nullable();
            $table->string('intro')->nullable();
            $table->text('partners')->nullable();
            $table->text('contact')->nullable();
            $table->text('publications')->nullable();
            $table->text('keywords')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_projects_projects', function(Blueprint $table)
        {
            $table->dropColumn('start');
            $table->dropColumn('end');
            $table->dropColumn('slug');
            $table->dropColumn('intro');
            $table->dropColumn('partners');
            $table->dropColumn('contact');
            $table->dropColumn('publications');
            $table->dropColumn('keywords');
        });
    }
}
