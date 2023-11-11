<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentMethod;
use App\Enums\PaymentMethodType;
use App\Models\Client;
use App\Classes\CommonConstant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {

            $client = Client::first();
            $paymentMethod = new PaymentMethod();
            $paymentMethod->name = 'UTEPay';
            $paymentMethod->type = PaymentMethodType::UTEPAY;
            $paymentMethod->config = json_encode([
                "UTE_PAY_DETAILS" => ""
            ]);
            $paymentMethod->code = 'UTE_PAY';
            $paymentMethod->client_id = $client->id;
            $paymentMethod->active = CommonConstant::DATABASE_YES;
            $paymentMethod->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            //
        });
    }
};
