<?php
// app/Jobs/StoreTrashData.php
namespace App\Jobs;

use App\Models\Trash;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class StoreTrashData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $userId;

    public function __construct(array $data, $userId)
    {
        $this->data = $data;
        $this->userId = $userId;
    }

    public function handle()
    {
        $user = User::find($this->userId);
        $trash_category_id = null;

        if ($this->data['category'] == 'Anorganik') {
            $trash_category_id = 2;
        } else if ($this->data['category'] == 'Organik') {
            $trash_category_id = 1;
        } else {
            $trash_category_id = 3;
        }

        $imagePath = 'trash_images/' . basename($this->data['trash_image']);
        UserAchievement::where('user_id', $user->id)->increment('progress', 1);

        $trashData = Trash::create([
            'trash_image' => $imagePath,
            'trash_name' => $this->data['trash_name'],
            'description' => $this->data['description'],
            'user_id' => $user->id,
            'trash_category_id' => $trash_category_id
        ]);

        $quantity = $this->data['trash_quantity'];
        $points = 5 * $quantity;
        $exp = 25 * $quantity;
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

        Artisan::call('update:claimable');
    }
}
