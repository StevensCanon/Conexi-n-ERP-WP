<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('erps', function (Blueprint $table) {
            $table->id();  // Crea la columna 'id' como clave primaria
            $table->enum('nombre_erp', ['Allegra', 'Odoo', 'SAP', 'Microsoft Dynamics', 'Zoho ERP']);  // Enum para los diferentes ERPs
            $table->string('token_b64');  // Token base64 cifrado
            $table->unsignedBigInteger('store_id');  // Definimos la columna store_id como 'unsignedBigInteger'

            // Definir la clave foránea
            $table->foreign('store_id')  // Relación con la tabla 'stores'
                  ->references('id')    // Referencia a la columna 'id' de la tabla 'stores'
                  ->on('stores')        // En la tabla 'stores'
                  ->onDelete('cascade'); // Si se elimina una tienda, se eliminan los ERPs asociados

            $table->timestamps();  // Tiempos de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('erps');  // Elimina la tabla 'erps'
    }
};
