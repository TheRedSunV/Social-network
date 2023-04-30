<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    public const STATUS_SENT = 'SENT';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_DELETED = 'DELETED';

    public const STATUSES = [
        self::STATUS_SENT,
        self::STATUS_ACTIVE,
        self::STATUS_REJECTED,
        self::STATUS_DELETED,
    ];

    protected $fillable = [
        'source_id',
        'target_id',
        'status'
    ];

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_id', 'id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }

    public function users()
    {
        $sourceUser = $this->sourceUser;
        $targetUser = $this->targetUser;

        return $sourceUser->merge($targetUser);;
    }


}
