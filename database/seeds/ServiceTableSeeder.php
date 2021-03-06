<?php

use Illuminate\Database\Seeder;
use App\Service;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = config('add_services.services_db');
        foreach ($services as $service) {
            $new_service = new Service();
            $new_service->fill($service);
            $new_service->save();
        }
    }
}
