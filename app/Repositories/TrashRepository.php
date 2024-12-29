<?php

namespace App\Repositories;

use App\Repositories\Contracts\TrashInterface;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use GeminiAPI\Laravel\Facades\Gemini;
use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class TrashRepository implements TrashInterface
{
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
        global $parsedResult;
        if (!file_exists($imagePath) || !is_file($imagePath) || !is_readable($imagePath)) {
            throw new InvalidArgumentException("Image file not found or not readable: $imagePath");
        }

        try {
            $prompt = '"Anda adalah seorang pemilah sampah profesional. Berdasarkan gambar, identifikasi:

                        Nama jenis sampah.
                        Kategori sampah (Organik, Anorganik, atau Limbah).
                        Jika termasuk Limbah, klasifikasikan lebih lanjut (contoh: B3, medis) dan jelaskan metode pengelolaan yang sesuai.
                        Harap jawab dalam format berikut:

                        Nama Sampah: [nama]
                        Kategori: [Organik/Anorganik/Limbah]
                        Pengelolaan (jika Limbah): [penjelasan]

                        "';
            $result = Gemini::geminiFlash()
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: MimeType::IMAGE_JPEG,
                        data: base64_encode(file_get_contents($imagePath))
                    )
                ]);

                $output = $result->text();

                preg_match('/Nama Sampah: (.+)/', $output, $namaSampah);
                preg_match('/Kategori: (.+)/', $output, $kategori);
                preg_match('/Pengelolaan \(jika Limbah\): (.+)/s', $output, $pengelolaan);

                $parsedResult = [
                    'nama_sampah' => $namaSampah[1] ?? null,
                    'kategori' => $kategori[1] ?? null,
                    'pengelolaan' => $pengelolaan[1] ?? null,
                ];
                return $parsedResult;

        } catch (Exception $e) {
            Log::error('Gemini API Error', [
                'error' => $e->getMessage(),
                'file' => $imagePath,
                'trace' => $e->getTraceAsString()
            ]);
            throw new RuntimeException("Failed to process image with Gemini API: " . $e->getMessage());
        }
    }

    public function storeData($data){

    }


}
