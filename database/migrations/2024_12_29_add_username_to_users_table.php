<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('id')->unique();
            // Tambahkan kolom lain yang mungkin belum ada
            $table->string('firstname')->nullable()->after('username');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('address')->nullable()->after('password');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('postal')->nullable()->after('country');
            $table->text('about')->nullable()->after('postal');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'firstname',
                'lastname',
                'address',
                'city',
                'country',
                'postal',
                'about'
            ]);
        });
    }
}; 