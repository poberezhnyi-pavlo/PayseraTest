<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CalculateFeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_calculate(): void
    {
        Storage::fake();
        $this
            ->artisan('calculate:fee ./tests/Storage/input.csv')
            ->assertExitCode(1);

        $this->assertFileExists(Storage::disk()->path('res.csv'));

        $this->assertFileEquals(base_path() . './tests/Storage/res.csv', Storage::disk()->path('res.csv'));

        $files = Storage::allFiles();
        Storage::delete($files);
    }
}
