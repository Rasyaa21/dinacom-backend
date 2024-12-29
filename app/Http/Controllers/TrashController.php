<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanImageRequest;
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
            $validated = $request->validated();

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

            $result = $this->trashRepository->scanImage($fullPath);

            @unlink($fullPath);

            return new ApiResponse(201, $result);

        } catch (Exception $e) {
            Log::error('Image Classification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $statusCode = $e instanceof InvalidArgumentException ? 400 : 500;

            return new ApiResponse($statusCode, [
                'error' => $e->getMessage(),
                'help' => 'Ensure you are using form-data with a valid image file in the trash_image field'
            ], 'Error processing image');
        }
    }
}
