<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppendUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var string
     */
    private string $chunkIndex;

    /**
     * @var string
     */
    private string $chunkSize;

    /**
     * @var string
     */
    private string $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunkIndex, $chunkSize, $fileName)
    {
        $this->chunkSize = $chunkSize;
        $this->chunkIndex = $chunkIndex;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::select(...$this->getColumns())
            ->take($this->chunkSize)
            ->skip($this->chunkIndex * $this->chunkSize)
            ->get()
            ->toArray();

        $handle = fopen(storage_path('app/' . $this->fileName), 'a');

        foreach ($users as $user) {
            fputcsv($handle, $user);
        }

        fclose($handle);
    }

    private function getColumns(): array
    {
        return [
            'id',
            'name',
            'email'
        ];
    }
}
