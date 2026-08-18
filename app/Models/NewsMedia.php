<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMedia extends Model {

    protected $table = 'news_media';
    protected $fillable = [
        'news_id', 'type', 'path', 'video_url', 'video_source', 'sort_order',
    ];

    //////////////////////////////////////////////
    public function news() {
        return $this->belongsTo(News::class, 'news_id');
    }

    //////////////////////////////////////////////
    // رابط جاهز للعرض بالواجهة: مسار صورة مرفوعة، أو رابط الفيديو الخارجي كما هو.
    public function displayUrl(): ?string {
        if ($this->type === 'image' && $this->path) {
            return url($this->path);
        }
        return $this->video_url;
    }

}
