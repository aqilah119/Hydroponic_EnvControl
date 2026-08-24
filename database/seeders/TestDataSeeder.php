<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorData;
use App\Models\ReferenceRange;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 SENSOR DATA (pH)
        SensorData::create([
            'sensor_type' => 'pH',
            'value' => 6.0,
            'timestamp' => now()
        ]);

        // 🔥 SENSOR DATA (Temperature)
        SensorData::create([
            'sensor_type' => 'temperature',
            'value' => 25,
            'timestamp' => now()
        ]);

        // 🔥 SENSOR DATA (Water Level)
        SensorData::create([
            'sensor_type' => 'water_level',
            'value' => 75,
            'timestamp' => now()
        ]);

        // 🔥 GENERAL RANGE (pH)
        ReferenceRange::create([
            'parameter' => 'pH',
            'min_value' => 5.5,
            'max_value' => 6.5,
            'type' => 'general',
            'plant_name' => null
        ]);

        // 🔥 GENERAL RANGE (Temperature)
        ReferenceRange::create([
            'parameter' => 'temperature',
            'min_value' => 20,
            'max_value' => 30,
            'type' => 'general',
            'plant_name' => null
        ]);

        // 🔥 OPTIONAL: Plant-specific (lettuce)
        ReferenceRange::create([
            'parameter' => 'pH',
            'min_value' => 5.8,
            'max_value' => 6.2,
            'type' => 'plant',
            'plant_name' => 'lettuce'
        ]);
    }
}