<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        $url = 'https://medical-info.dghs.gov.bd/public/medical-cases/datatable/json?show_total_death=1&ministry_verified=1&length=1';
        $request = Http::get($url);

        if($request->successful()) {
            $DGHSData = $request->json();
            if(array_key_exists('data', $DGHSData)) {
                foreach($DGHSData['data'] as $item) {
                    $geo = Marker::where('name', $item['facility_name'])->first();

                    if($geo) {
                        if($item['permanent_address'] == $item['present_address']) {
                            $item['present_address'] = '';
                        }

                        $data[] = [
                            'facility_name' => $item['facility_name'],
                            'patient_name_en' => $item['patient_name_en'],
                            'patient_name_bn' => $item['patient_name_bn'],
                            'father_name' => $item['father_name'],
                            'contact_no' => $item['contact_no'],
                            'present_address' => $item['present_address'],
                            'permanent_address' => $item['permanent_address'],
                            'type_of_service' => $item['type_of_service'],
                            'lat' => $geo->lat,
                            'lng' => $geo->lng,
                        ];
                    }
                }
            }
        }

        return view('welcome', compact('data'));
    }
}
