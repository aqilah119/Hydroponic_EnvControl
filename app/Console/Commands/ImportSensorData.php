<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportSensorData extends Command
{
    protected $signature = 'import:sensor';
    protected $description = 'Import sensor data from CSV';

    public function handle()
    {
        $file = storage_path('app/hydroponic.csv');

        if (!file_exists($file)) {
            $this->error('File not found!');
            return;
        }

        $csv = fopen($file, 'r');

        fgetcsv($csv); // skip header

        while (($row = fgetcsv($csv)) !== false) {

            $timestamp = Carbon::createFromFormat('d/m/Y H:i', $row[1])
                                ->format('Y-m-d H:i:s');

            DB::table('sensor_logs')->insert([
                'timestamp'        => $timestamp,
                'ph'               => $row[2],
                'tds'              => $row[3],
                'water_level'      => $row[4],
                'dht_temp'         => $row[5],

                'ph_reducer'     => strtolower(trim($row[6])) == 'on',
                'add_water'      => strtolower(trim($row[7])) == 'on',
                'nutrient_adder' => strtolower(trim($row[8])) == 'on',
                'ex_fan'         => strtolower(trim($row[10])) == 'on',

                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        fclose($csv);

        $this->info('✅ Import success with actuator data!');
    }
}