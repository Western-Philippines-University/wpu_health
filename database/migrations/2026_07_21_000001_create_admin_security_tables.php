<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admin_login_attempts')) {
            Schema::create('admin_login_attempts', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->index();
                $table->string('ip_address', 45)->index();
                $table->string('user_agent', 255)->nullable();
                $table->boolean('successful')->default(false);
                $table->timestamp('attempted_at')->useCurrent()->index();
            });
        }

        if (! Schema::hasTable('admin_password_history')) {
            Schema::create('admin_password_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->index();
                $table->string('password_hash', 255);
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('security_audit_log')) {
            Schema::create('security_audit_log', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->index();
                $table->string('ip_address', 45);
                $table->string('user_agent', 255)->nullable();
                $table->string('action', 100)->index();
                $table->string('details', 500)->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'failed_login_attempts')) {
                $table->unsignedInteger('failed_login_attempts')->default(0);
            }
            if (! Schema::hasColumn('admins', 'locked_until')) {
                $table->timestamp('locked_until')->nullable();
            }
            if (! Schema::hasColumn('admins', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable();
            }
            if (! Schema::hasColumn('admins', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false);
            }
            if (! Schema::hasColumn('admins', 'two_factor_secret')) {
                $table->string('two_factor_secret', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_audit_log');
        Schema::dropIfExists('admin_password_history');
        Schema::dropIfExists('admin_login_attempts');

        Schema::table('admins', function (Blueprint $table) {
            $cols = ['failed_login_attempts', 'locked_until', 'password_changed_at', 'two_factor_enabled', 'two_factor_secret'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('admins', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
