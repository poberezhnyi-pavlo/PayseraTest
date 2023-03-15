<?php

namespace App\Console\Commands;

use App\Services\FeeService;
use App\Services\FileService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class CalculateFee extends Command
{
    protected $signature = 'calculate:fee {pathInput}';

    public function handle(FileService $fileService, FeeService $feeService): int
    {
        $pathInput = $this->argument('pathInput');

        $fileData = $fileService->read($pathInput);

        $result = $feeService->processFee($fileData);

        $pathResult = Storage::disk()->path('res.csv');

        $fileService->write($pathResult, $result);

        return 1;
    }
}
