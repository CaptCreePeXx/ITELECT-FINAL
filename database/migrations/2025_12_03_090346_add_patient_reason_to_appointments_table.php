<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_patient_reason_to_appointments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add a new column to store the patient's original note
            $table->text('patient_reason')->nullable()->after('note'); 
            
            // Optional: If you want to rename the existing 'note' column 
            // to 'receptionist_message' for clarity, you would do it here 
            // but this requires additional steps. Let's stick to adding the new column.
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('patient_reason');
        });
    }
};