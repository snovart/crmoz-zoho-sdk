<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parent_module_id', 32)->index();
            $table->string('zoho_id', 32)->nullable()->index();
            $table->date('activity_date')->nullable();
            $table->string('activity_type', 100)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            // $table->softDeletes(); // если хочешь soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};
