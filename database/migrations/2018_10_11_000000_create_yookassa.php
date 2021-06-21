<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYookassa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(env('YOOKASSA_DATABASE_TABLE_NAME', 'yookassa'), function (Blueprint $table) {
            $table->id();
            # UserID from Users table
            $table->unsignedBigInteger('user_id')->nullable();

            # PaymentID from YooKassa
            $table->string('payment_id');
            # Status Payment
            $table->enum('status', ['pending', 'waiting_for_capture', 'succeeded', 'canceled']);
            # Is Paid?
            $table->boolean('paid')->default(false);
            # Amount Invoice
            $table->float('sum', 2);
            # Currency Invoice
            $table->string('currency')->default('RUB');
            # Payment Link
            $table->string('payment_link');
            # Description Invoice
            $table->string('description')->nullable();
            # Paid At
            $table->dateTime('paid_at')->nullable();
            # Uniq ID
            $table->string('uniq_id')->nullable();

            # Foreign Key
            $table->foreign(
                env('YOOKASSA_DATABASE_FIELD_FOREIGN', 'user_id')
            )->references(
                env('YOOKASSA_DATABASE_FIELD_ON', 'users')
            )->on(
                env('YOOKASSA_DATABASE_FIELD_REFERENCES', 'id')
            )->onDelete(
                env('YOOKASSA_DATABASE_FIELD_ON_DELETE', 'cascade')
            );
            # Fields: created_at And updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(env('YOOKASSA_DATABASE_TABLE_NAME', 'yookassa'));
    }
}
