<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class YoutubeVideo extends Model
{
    use  HasFactory;

    protected $fillable = [
        'videoid',
        'publishedAt',
        'channelId',
        'title',
        'description',
        'thumbnails',
        'channelTitle',
        'userid',
        'username',
    ];
    protected $dateFormat = 'U';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

     /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
