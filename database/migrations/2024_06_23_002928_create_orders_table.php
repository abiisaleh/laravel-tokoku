<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['new', 'canceled', 'processing', 'shipped', 'delivered', 'pending'])->default('pending');
            $table->integer('subtotal')->nullable();
            $table->integer('ongkir')->nullable();
            $table->integer('total')->virtualAs('subtotal + ongkir');
            $table->string('tujuan')->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table->json('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
