<?php
namespace App\Jobs;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ProcessCsvUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        $insertData = [];
        
        foreach ($this->rows as $rowAssoc) {
            if (!$rowAssoc || empty($rowAssoc['title']) || empty($rowAssoc['category_name'])) {
                continue;
            }

            if (Post::where('title', $rowAssoc['title'])->exists()) {
                continue;
            }

            $category = Category::firstOrCreate(['name' => $rowAssoc['category_name']]);

            $insertData[] = [
                'title' => $rowAssoc['title'],
                'description' => $rowAssoc['description'],
                'status' => (int) $rowAssoc['status'],
                'category_id' => $category->id,
                'create_user_id' => Auth::id(),
                'updated_user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            Post::insert($insertData);
        }
    }
}
