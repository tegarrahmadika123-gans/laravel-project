<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->string('npm')->after('book_id');
        $table->string('email')->after('npm');
    });
}

public function down()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->dropColumn(['npm', 'email']);
    });
}
};
