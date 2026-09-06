<?php

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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique(); // e.g. NVU, APEX, UNILAG
            $table->string('subdomain', 100)->unique(); // e.g. novica (novica.netzertech.com)
            $table->string('custom_domain', 255)->nullable()->unique(); // e.g. portal.novica.edu.ng
            $table->string('logo_url', 500)->nullable();
            $table->string('primary_color', 15)->default('#2E5FA3');
            $table->string('secondary_color', 15)->default('#1A7A6E');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            
            // Financial & Payment Gateway Settings
            $table->string('paystack_subaccount_code', 100)->nullable();
            $table->string('remita_merchant_id', 100)->nullable();
            $table->decimal('platform_fee_amount', 10, 2)->default(1500.00);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
