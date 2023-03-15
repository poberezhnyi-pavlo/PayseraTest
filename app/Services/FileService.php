<?php

declare(strict_types=1);

namespace App\Services;

use App\Transformes\TransformToTransactionDTO;
use Illuminate\Support\Collection;

final class FileService
{
    private TransformToTransactionDTO $transformToTransactionDTO;

    public function __construct(TransformToTransactionDTO $transformToTransactionDTO)
    {
        $this->transformToTransactionDTO = $transformToTransactionDTO;
    }

    public function read(string $path): Collection
    {
        $data = collect();

        $file = fopen($path, 'r');

        while (($line = fgetcsv($file, 1000, ',')) !== false) {
            $data->push($this->transformToTransactionDTO->transform($line));
        }

        fclose($file);

        return $data;
    }

    public function write(string $path, array $data): void
    {
        $file = fopen($path, 'w');

        foreach ($data as $fee) {
            fputcsv($file, [$fee]);
        }

        fclose($file);
    }
}
