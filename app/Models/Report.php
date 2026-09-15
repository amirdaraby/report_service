<?php

namespace App\Models;

use App\Enums\Frequency;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'name',
        'frequency',
        'status',
        'keywords',
    ];

    protected function casts(): array
    {
        return [
            'frequency' => Frequency::class,
            'status' => Status::class,
            'keywords' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
