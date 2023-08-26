<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $client = new Client();
            $client->name = 'Gen Client';
            $client->app_name = 'Gen Client';
            $client->address = 'So 1 Vo Van Ngan, Thu Duc, TP HCM';
            $client->website = 'https://hcmute.edu.vn/';
            $client->email = 'gen@email.com';
            $client->phone = '0123456789';

            $client->save();

            $user = new User();
            $user->name = 'Gen User';
            $user->email = $client->email;
            $user->type = User::ROLE_ADMIN;
            $user->password = bcrypt('123');
            $user->client_id = $client->id;
            $user->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            $client = Client::where('email', 'gen@email.com')->first();
            $client->delete();
        });
    }
};
