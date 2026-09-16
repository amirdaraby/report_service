<?php

namespace App\Models;

use App\Enums\Frequency;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'name',
        'frequency',
        'status',
        'keywords',
        'next_run_at',
        'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'frequency' => Frequency::class,
            'status' => Status::class,
            'keywords' => 'array',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
