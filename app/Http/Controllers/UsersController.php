<?php

namespace App\Http\Controllers;

use App\Jobs\AppendUsers;
use App\Jobs\CreateExportFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index()
    {
        return view('users', ['users' => User::paginate()]);
    }

    public function export()
    {
        $chunkSize = 1000;
        $fileName = time() . '_users_export.csv';
        $numberOfChunks = ceil(User::count() / $chunkSize);

        $batches = [
            new CreateExportFile($fileName)
        ];

        for ($i = 0; $i < $numberOfChunks; $i++) {
            $batches[] = new AppendUsers($i, $chunkSize, $fileName);
        }

        Bus::batch($batches)
            ->name('export users')
            ->then(fn () => Log::info('Done'))
            ->catch(fn () => Log::error('Error'))
            ->dispatch();

        return back()->with('success', 'You Will Be Notified When File Is Finished');
    }
}
