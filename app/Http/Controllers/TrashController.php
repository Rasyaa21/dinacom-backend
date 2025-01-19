<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanImageRequest;
use App\Http\Resources\TrashResource;
use App\Http\Response\ApiResponse;
use App\Repositories\Contracts\TrashInterface;
use App\Repositories\TrashRepository;
use Exception;
use GeminiAPI\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class TrashController extends Controller
{
    private TrashInterface $trashRepository;

    public function __construct(TrashInterface $trashRepository) {
        $this->trashRepository = $trashRepository;
    }


    public function scanImage(ScanImageRequest $request)
        {
            try {
                $image = $request->file('trash_image');
                if (!$image || !$image->isValid()) {
                    return new ApiResponse(400, [
                        'error' => 'Invalid or missing image file',
                        'help' => 'Please provide a valid image file in the trash_image field'
                    ]);
                }
                $storagePath = 'trash_images';
                $imagePath = $image->store($storagePath, 'public');
                if (!$imagePath) {
                    throw new RuntimeException("Failed to store the image file");
                }
                $fullPath = public_path('storage/' . $imagePath);
                if (!file_exists($fullPath)) {
                    throw new RuntimeException("Image file not found at: " . $fullPath);
                }
                $scanResult = $this->trashRepository->scanImage($fullPath);
                return new ApiResponse(200, $scanResult, "Image processed successfully.");
            } catch (Exception $e) {
                Log::error('Error occurred:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return new ApiResponse(500, [
                    'error' => 'An error occurred while processing the image',
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function getGroupData(string $type){
        try{
            $data = $this->trashRepository->getGroupDataByUserId($type);
            return new ApiResponse(200, TrashResource::collection($data), "success");
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function getDataByUserAndCategory($category_id){
        try{
            $data = $this->trashRepository->getDataByUserAndCategory($category_id);
            return new ApiResponse(200, TrashResource::collection($data), "success");
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function getAllDataByUserId(){
        try{
            $data = $this->trashRepository->getAllDataByUserId();
            return new ApiResponse(200, TrashResource::collection($data), "success");
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function getTrashDetail($id){
        try{
            $data = $this->trashRepository->DetailTrash($id);
            return new ApiResponse(200, new TrashResource($data), "success");
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }
}
