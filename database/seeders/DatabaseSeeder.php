<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Domain\Auth\V1\Enums\FileType;
use Domain\Auth\V1\Models\User;
use Domain\FileSystem\V1\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $users = User::factory(5)->create();

         foreach ($users as $user) {
             $rootFolder = $this->createFolders(amount: 1, owner: $user)->first();

             $folders = $this->createFolders(20,  owner: $user, parent: $rootFolder);
             $this->createFiles(100,  owner: $user, folder: $rootFolder);

             foreach ($folders as $folder) {
                 $this->createFiles(100,  owner: $user, folder: $folder);
             }
         }
    }

    protected function createFolders(int $amount, User $owner, array|null $parent = null): Collection
    {
        $folders = collect();

        for ($i = 1; $i <= $amount; $i++) {
            $folders->push([
                'id' => Str::uuid(),
                'name' => fake()->word(),
                'metadata' => json_encode([]),
                'type' => FileType::Folder->value,
                'owner_id' => $owner->id,
                'parent_id' => Arr::get($parent, 'id'),
                'created_at' => now(),
            ]);
        }

        File::query()->insert($folders->toArray());

        return $folders;
    }

    protected function createFiles(int $amount, User $owner, array $folder): Collection
    {
        $files = collect();
        for ($i = 1; $i <= $amount; $i++) {
            $files->push([
                'id' => Str::uuid(),
                'name' => fake()->word().'.'.fake()->fileExtension(),
                'metadata' => json_encode([
                    'mimetype' => fake()->mimeType()
                ]),
                'type' => FileType::File->value,
                'owner_id' => $owner->id,
                'parent_id' => $folder['id'],
                'created_at' => now(),
            ]);
        }

        File::query()->insert($files->toArray());

        return $files;
    }
}
