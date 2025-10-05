<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'upload_date',
        'expired_date',
        'upload_bw',
        'upload_duration',
        'total_views',
        'total_downloads',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: File punya banyak aktivitas
     */
    public function activities()
    {
        return $this->hasMany(FileActivity::class);
    }
}
