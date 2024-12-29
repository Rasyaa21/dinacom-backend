<?php

namespace App\Repositories;

use App\Repositories\Contracts\TrashInterface;
use Gemini\Data\Blob;
use LucianoTonet\GroqLaravel\Facades\Groq;
use Gemini\Enums\MimeType as EnumsMimeType;
use GeminiAPI\Laravel\Facades\Gemini;
use Exception;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Storage\StorageClient;
use InvalidArgumentException;
use RuntimeException;

class TrashRepository implements TrashInterface
{
    private const FALLBACK_MODEL = 'gemini-1.5-flash';
    // Implement the methods from the interface
    public function all()
    {
        // Logic for getting all items
    }

    public function find($id)
    {
        // Logic for finding a single item
    }

    public function create(array $data)
    {
        // Logic for creating a new item
    }

    public function update($id, array $data)
    {
        // Logic for updating an item
    }

    public function delete($id)
    {
        // Logic for deleting an item
    }

    public function scanImage(string $imagePath)
    {
        if (!file_exists($imagePath) || !is_file($imagePath) || !is_readable($imagePath)) {
            throw new InvalidArgumentException("Image file not found or not readable: $imagePath");
        }

        var_dump(gettype($imagePath));
        try {
            $prompt = 'Klasifikasikan saya sampah apa yang ada di gambar: organik atau anorganik. ' .
                    'Berikan saya nama sampahnya dan rekomendasi cara mengelola sampah tersebut.';
            $response = Groq::vision()->analyze($imagePath, $prompt);
            $imageAnalysis = $response['choices'][0]['message']['content'];

            return $response;

        } catch (Exception $e) {
            Log::error('Groq API Error', [
                'error' => $e->getMessage(),
                'file' => $imagePath,
                'trace' => $e->getTraceAsString()
            ]);

            throw new RuntimeException("Failed to process image with Groq API: " . $e->getMessage());
        }
    }
}


