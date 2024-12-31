<?php

namespace App\Http\Controllers;

use App\Filament\Resources\TrashLocationResource;
use App\Http\Resources\TrashLocationResResource;
use App\Http\Response\ApiResponse;
use App\Models\TrashLocation;
use App\Repositories\Contracts\TrashInterface;
use App\Repositories\Contracts\TrashLocationInterface;
use Exception;
use Illuminate\Http\Request;

class TrashLocationController extends Controller
{
    private TrashLocationInterface $trashLocationRepository;
    public function __construct(
        TrashLocationInterface $trashLocationRepository
    ) {
        $this->trashLocationRepository = $trashLocationRepository;
    }

    public function getAllLocation(){
        try{
            $locations = $this->trashLocationRepository->all();
            return new ApiResponse(200, TrashLocationResResource::collection($locations), 'lokasi berhasil didapatkan');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function getLocationByCategory($category_id){
        try{
            $locations = $this->trashLocationRepository->getLocationByCategory($category_id);
            return new ApiResponse(200, TrashLocationResResource::collection($locations), 'lokasi berhasil didapatkan');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }
}
