<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamping extends Model
{
    use HasFactory;

    //リレーションを定義
    //リレーションとは・・・テーブル間の関連性のこと（一対一や一対多）
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
