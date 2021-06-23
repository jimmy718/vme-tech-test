<?php

namespace App\Queries\Brand;

use App\Models\Brand;

class FirstOrCreateBrandByNameQuery
{
    public function run(?string $name)
    {
        if (is_null($name) || strlen(trim($name)) === 0) {
            return null;
        }

        return Brand::firstOrCreate([
            'name' => trim($name)
        ]);
    }
}
