<?php

namespace Domain\FileSystem\V1\Models;

use Domain\Auth\V1\Enums\FileType;
use Domain\Auth\V1\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    use HasUuids;

    protected $table = 'files';
    protected $guarded = [];
    protected $casts = [
        'type' => FileType::class,
        'metadata' => 'array'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(File::class, 'parent_id', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id', 'id');
    }
}
