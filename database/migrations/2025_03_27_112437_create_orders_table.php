<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('redProviderPortalId');
            $table->string('name');
            $table->enum('type', ['connector', 'vpn_connection']);
            $table->enum('status', ['ordered', 'processing', 'completed'])->default('ordered');
            $table->timestamps();
            $table->index('created_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
