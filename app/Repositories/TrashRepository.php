<?php

namespace App\Repositories;

use App\Repositories\Contracts\TrashInterface;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use GeminiAPI\Laravel\Facades\Gemini;
use Exception;
use App\Models\Trash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrashRepository implements TrashInterface
{
    public function getDataByUserAndCategory($category_id){
        $userId = Auth::user()->id;
        return Trash::where('user_id', $userId)->where('trash_category_id', $category_id)->get();
    }

    public function getAllDataByUserId(){
        $userId = Auth::user()->id;
        return Trash::where('user_id', $userId)->get();
    }

    public function getGroupDataByUserId(string $type)
    {
        $user_id = Auth::user()->id;
        if (!in_array($type, ['month', 'week', 'day'])) {
            throw new InvalidArgumentException("Wrong Input Type");
        }
        $query = Trash::query()->where('user_id', $user_id);
        if ($type == 'month') {
            $groupData = $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period, COUNT(*) as count')
                ->groupBy('period')
                ->get();
            $data = Trash::where('user_id', $user_id)->where(function ($subQuery) use ($groupData){
                foreach($groupData as $group){
                    if ($group->count > 1){
                        $subQuery->orWhere(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $group->period);
                    }
                }
            })->get();
        } elseif ($type == 'week') {
            $groupData = $query->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as count')
                ->groupBy('year', 'week')
                ->get();
            $data = Trash::where('user_id', $user_id)->where(function ($subQuery) use ($groupData){
                foreach($groupData as $group){
                    if ($group->count > 1){
                        $subQuery->orWhere(function ($q) use ($group){
                            $q->whereYear('created_at', $group->year)
                            ->where(DB::raw('WEEK(created_at)'), $group->week);
                        });
                    }
                }
            })->get();
        } else {
            $groupData = $query->selectRaw('DATE(created_at) as period, COUNT(*) as count')
                ->groupBy('period')
                ->get();
            $data = Trash::where('user_id', $user_id)->where(function ($subQuery) use ($groupData){
                foreach($groupData as $group){
                    if($group->count > 1){
                        $subQuery->orWhere(DB::raw('DATE(created_at)'), $group->period);
                    }
                }
            })->get();
        }
        return $data;
    }
    public function scanImage(string $imagePath)
    {
        if (!file_exists($imagePath) || !is_file($imagePath) || !is_readable($imagePath)) {
            throw new InvalidArgumentException("Image file not found or not readable: $imagePath");
        }

        try {
            $prompt = "Anda adalah seorang AI yang sangat cerdas dan professional dalam pemilahan sampah. Tujuan utama anda mengidentifikasi, mengkategorikan, dan memberikan panduan terperinci untuk pembuangan dan daur ulang sampah. Berdasarkan gambar, identifikasi:
                        - Nama jenis sampah.
                        - Kategori sampah (Organik, Anorganik, atau Limbah).
                        - Jika termasuk Limbah, klasifikasikan lebih lanjut (contoh: B3, medis) dan jelaskan metode pengelolaan yang sesuai.
                        - Perkirakan jumlah sampah dalam gambar.

                        Jawab dalam format berikut:

                        **Nama Sampah:** [nama]
                        **Deskripsi:** [deskripsi] (deskripsikan sampahnya saja) jika bukan sampah jelaskan itu bukan sampah
                        **Kategori:** [Organik/Anorganik/Limbah] jika sampahnya tidak ada kategorikan sebagai Undefined
                        **Pengelolaan:** [penjelasan] jika itu bukan sampah jelaskan itu bukan sampah
                        **Jumlah Sampah:** [jumlah] (dalam bentuk satuan angka saja tanpa deskripsi) jika itu bukan sampah maka buatlah menjadi 0";

            $result = Gemini::geminiFlash()
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: MimeType::IMAGE_JPEG,
                        data: base64_encode(file_get_contents($imagePath))
                    )
                ]);

            $output = $result->text();

            preg_match('/\*\*Nama Sampah:\*\*\s*(.+?)(?=\n\*\*|$)/s', $output, $namaSampah);
            preg_match('/\*\*Deskripsi:\*\*\s*(.+?)(?=\n\*\*|$)/s', $output, $deskripsi);
            preg_match('/\*\*Kategori:\*\*\s*(.+?)(?=\n\*\*|$)/s', $output, $kategori);
            preg_match('/\*\*Pengelolaan:\*\*\s*(.+?)(?=\n\*\*|$)/s', $output, $pengelolaan);
            preg_match('/\*\*Jumlah Sampah:\*\*\s*(\d+)/', $output, $jumlahSampah);

            $parsedResult = [
                'trash_image' => $imagePath,
                'trash_name' => isset($namaSampah[1]) ? trim($namaSampah[1]) : 'Data tidak tersedia',
                'description' => isset($deskripsi[1]) ? trim($deskripsi[1]) : 'Data tidak tersedia',
                'category' => isset($kategori[1]) ? trim($kategori[1]) : 'Data tidak tersedia',
                'pengelolaan' => isset($pengelolaan[1]) ? trim($pengelolaan[1]) : 'Data tidak tersedia',
                'trash_quantity' => isset($jumlahSampah[1]) ? (int) $jumlahSampah[1] : 0
            ];

            Log::info('Parsed Trash Data:', $parsedResult);

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




    public function storeData(array $data){

        $user = Auth::user();
        $trash_category_id = null;
        $resErr = "itu bukan sampahh";
        if($data['category'] == 'Anorganik'){
            $trash_category_id = 2;
        } else if ($data['category'] == 'Organik'){
            $trash_category_id = 1;
        } else {
            $trash_category_id = 3;
        }
        if($data['category'] == 'Undefined'){
            return $resErr;
        }
        $trashData = Trash::create([
            'trash_image' => $data['trash_image'],
            'trash_name' => $data['trash_name'],
            'description' => $data['description'],
            'user_id' => $user->id,
            'trash_category_id' => $trash_category_id
        ]);

        $quantity = $data['trash_quantity'];
        $points = 5 * $quantity;
        $exp = 25 * $quantity;
        $user = User::find($user->id);
        $rank = $user->rank;

        $rankMultiplier = [
            'Bronze' => 1,
            'Silver' => 1.2,
            'Gold' => 1.4,
            'Platinum' => 1.5,
            'Diamond' => 1.7
        ];

        $multiplier = isset($rankMultiplier[$rank]) ? $rankMultiplier[$rank] : 1;
        $user->exp += $exp * $multiplier;
        $user->points += $points * $multiplier;
        $user->save();

        return $trashData;
    }

    public function delete($id){
        return Trash::where('id', $id)->delete();
    }

    public function DetailTrash($id){
        $user = Auth::user();
        return Trash::where('id', $id)->where('user_id', $user->id)->first();
    }
}
