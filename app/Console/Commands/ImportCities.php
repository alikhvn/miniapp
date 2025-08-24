<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use League\Csv\Reader;

class ImportCities extends Command
{
    protected $signature = 'import:cities {file}';
    protected $description = 'Import cities from Simplemaps CSV';

    public function handle()
    {
        $file = $this->argument('file');

        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            City::updateOrCreate(
                [
                    'name_en' => $record['city'],
                    'country' => $record['country'],
                ],
                [
                    'lat'        => $record['lat'],
                    'lng'        => $record['lng'],
                    'iso2'       => $record['iso2'] ?? null,
                    'region'     => $record['admin_name'] ?? null,
                    'capital'    => $record['capital'] ?? null,
                    'population' => !empty($record['population']) ? (int)$record['population'] : null,
                ]
            );
        }


        $this->info('Cities imported successfully!');
    }
}
