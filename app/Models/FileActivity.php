<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileActivity extends Model
{
    /** @use HasFactory<\Database\Factories\FileActivityFactory> */
    use HasFactory;

    protected $table = 'file_activities';

    protected $fillable = [
        'file_id',
        'user_id',
        'activity_type',
        'timestamp',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Relasi: Aktivitas dilakukan oleh 1 User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
