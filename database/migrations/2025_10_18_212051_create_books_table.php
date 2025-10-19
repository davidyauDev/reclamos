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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // Consumer information
            $table->enum('person_type', ['individual', 'legal_entity']);
            $table->string('document_type', 20);
            $table->string('document_number', 20);
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_minor')->default(false);

            // Contact details
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country', 3)->default('PE');

            // Product or service details
            $table->enum('item_type', ['product', 'service']);
            $table->text('item_description');
            $table->boolean('has_payment_proof')->default(false);

            // Attached files (array of URLs)
            $table->json('attached_files')->nullable();

            // Economic claim
            $table->boolean('claims_amount')->default(false);
            $table->decimal('claimed_amount', 10, 2)->nullable();

            // Complaint details
            $table->enum('claim_type', ['complaint', 'grievance']);
            $table->text('claim_description');
            $table->text('request');

            // Additional info
            $table->date('claim_date')->nullable();
            $table->string('preferred_contact_method', 50)->default('email');
            $table->boolean('data_processing_consent')->default(false);
            $table->string('signature')->nullable();
            $table->string('form_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
