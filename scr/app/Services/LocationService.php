<?php
namespace App\Services;

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{
    protected $apiBase = 'https://esgoo.net/api-tinhthanh';

    public function syncLocations()
    {
        try {
            $this->syncProvinces();
            $this->syncDistricts();
            $this->syncWards();
        } catch (\Exception $e) {
            Log::error('Location Sync Error: ' . $e->getMessage());
        }
    }

    protected function syncProvinces()
    {
        $response = Http::get("{$this->apiBase}/1/0.htm");
        
        if ($response->successful()) {
            $provinces = $response->json()['data'] ?? [];
            
            foreach ($provinces as $provinceData) {
                Province::updateOrCreate(
                    ['code' => $provinceData['id']],
                    [
                        'name' => $provinceData['name'],
                        'full_name' => $provinceData['full_name']
                    ]
                );
            }
        }
    }

    protected function syncDistricts()
    {
        $provinces = Province::all();
        
        foreach ($provinces as $province) {
            $response = Http::get("{$this->apiBase}/2/{$province->code}.htm");
            
            if ($response->successful()) {
                $districts = $response->json()['data'] ?? [];
                
                foreach ($districts as $districtData) {
                    District::updateOrCreate(
                        ['code' => $districtData['id']],
                        [
                            'name' => $districtData['name'],
                            'full_name' => $districtData['full_name'],
                            'province_id' => $province->id
                        ]
                    );
                }
            }
        }
    }

    protected function syncWards()
    {
        $districts = District::all();
        
        foreach ($districts as $district) {
            $response = Http::get("{$this->apiBase}/3/{$district->code}.htm");
            
            if ($response->successful()) {
                $wards = $response->json()['data'] ?? [];
                
                foreach ($wards as $wardData) {
                    Ward::updateOrCreate(
                        ['code' => $wardData['id']],
                        [
                            'name' => $wardData['name'],
                            'full_name' => $wardData['full_name'],
                            'district_id' => $district->id
                        ]
                    );
                }
            }
        }
    }
}