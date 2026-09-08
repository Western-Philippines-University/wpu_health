<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('patient_record_edit_history')) {
            Schema::create('patient_record_edit_history', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('patient_record_id');
                $table->string('edited_by', 50)->nullable();
                $table->mediumText('snapshot');
                $table->timestamp('created_at')->useCurrent();
                $table->index(['patient_record_id', 'created_at'], 'idx_record_created');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_record_edit_history');
    }
};
