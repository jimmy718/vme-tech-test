<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandController extends Controller
{
    public function index(): JsonResource
    {
        return BrandResource::collection(Brand::all());
    }
}
