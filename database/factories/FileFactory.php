<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = $this->faker->word . '.' . $this->faker->fileExtension;
        $filePath = 'files/' . $fileName;
        Storage::disk('public')->put($filePath, Str::random(1000));
        return [
            'name' => $fileName,
            'path' => $filePath,
            'size' => Storage::disk('public')->size($filePath), // Size in bytes
            'file' => $filePath
        ];
    }
}
