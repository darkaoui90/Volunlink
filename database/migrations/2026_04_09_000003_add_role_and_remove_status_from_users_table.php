<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('volunteer')->after('password');
            });
        }

        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::table('users')
            ->where(function (Builder $query) {
                $query->whereNull('role')->orWhere('role', '');
            })
            ->update(['role' => 'volunteer']);

        DB::table('users')
            ->whereNotIn('role', ['admin', 'coordinator', 'supervisor', 'volunteer'])
            ->update(['role' => 'volunteer']);

        if (! DB::table('users')->where('role', 'admin')->exists()) {
            $firstUserId = DB::table('users')->orderBy('id')->value('id');

            if ($firstUserId !== null) {
                DB::table('users')->where('id', $firstUserId)->update(['role' => 'admin']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
