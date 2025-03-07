<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProcessCsvUpload
 * Handles the CSV upload processing and stores posts in the database.
 */
class ProcessCsvUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The rows from the CSV to be processed.
     *
     * @var array
     */
    protected $rows;

    /**
     * Create a new job instance.
     *
     * @param array $rows
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * Processes the CSV rows and stores the valid posts in the database.
     *
     * @return void
     */
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
                'created_user_id' => Auth::id(),
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
