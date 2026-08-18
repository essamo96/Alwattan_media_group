<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {

    use SoftDeletes;

    protected $table = 'news';
    protected $fillable = [
        'title', 'description', 'details', 'type', 'thumb', 'image', 'video', 'video_source', 'category_id', 'tags', 'special', 'sidebar', 'language', 'user_id',
    ];
    protected $hidden = [
        '',
    ];

    //////////////////////////////////////////////
    public function category() {
        return $this->belongsTo('App\Models\Categories', 'category_id');
    }

    //////////////////////////////////////////////
    public function comment() {
        return $this->hasMany('App\Models\Comments', 'news_id', 'id');
    }

    //////////////////////////////////////////////
    // معرض الصور/الفيديوهات الإضافية التابعة لنفس الخبر (الريبيتر)
    public function media() {
        return $this->hasMany(NewsMedia::class, 'news_id')->orderBy('sort_order');
    }

    /////
    public function addNews($title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $sidebar, $language, $user_id, $type = 'image', $video = null) {
        if ($publish == 1) {
            $pub_date = date('Y-m-d H:i:s');
        }
        $this->title = $title;
        $this->slug = $slug;
        $this->sub = $sub;
        $this->descs = $descs;
        $this->image = $image;
        $this->type = $type;
        $this->video = $video;
        $this->category_id = $category_id;
        $this->tags = $tags;
        $this->pub_date = $pub_date;
        $this->publish = $publish;
        $this->main = $sidebar;
        $this->language = $language;
        $this->user_id = $user_id;

        $this->save();
        return $this;
    }

    //////////////////////////////////////////////
    public function updateNews($obj, $title, $slug, $sub, $descs, $image, $category_id, $tags, $pub_date, $publish, $language, $sidebar, $type = 'image', $video = null) {
        $old_resort = $obj->resort;
        if ($obj->publish == 0 && $publish == 1) {
            $pub_date = date('Y-m-d H:i:s');
        }
        $obj->title = $title;
        $obj->slug = $slug;
        $obj->sub = $sub;
        $obj->descs = $descs;
        $obj->image = $image;
        $obj->type = $type;
        $obj->video = $video;
        $obj->category_id = $category_id;
        $obj->tags = $tags;
        $obj->pub_date = $pub_date;
        $obj->publish = $publish;
        $obj->language = $language;
        $obj->main = $sidebar;

        $obj->save();

        return $obj;
    }

    //////////////////////////////////////////////
    public function updatePublish($id, $publish) {
        return $this
                        ->where('id', '=', $id)
                        ->update([
                            'publish' => $publish,
                            'pub_date' => date('Y-m-d H:i:s'),
        ]);
    }

    //////////////////////////////////////////////
    public function deleteNews($obj) {
        // لا يوجد قيد FK على مستوى القاعدة يربط news_media.news_id بـ news.id (انظر
        // ملاحظة migration جدول news_media)، فنحذف صفوف المعرض يدوياً هون.
        NewsMedia::where('news_id', $obj->id)->delete();
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getNews($id) {
        $item = $this->with('category')
                ->where('publish', '=', 1)
                ->where('id', '=', $id)
                ->first();
        if ($item) {
            $item->views = $item->views + 1;
            $item->save();

            return $item;
        } else {
            return false;
        }
    }

    public function getNew($id) {
        return $this->find($id);
    }

    public function getNewBySlug($slug) {
        return $this->where('slug', $slug)->first();
    }
    // public function getNewBySlugId($slug,$id) {
    //     return $this->where('slug', $slug)->first();
    // }

    //////////////////////////////////
    public function getAllNews() {
        return $this->get();
    }

    //////////////////////////////////
    public function getSpecialNews($limit) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('main', '=', 1)
                        ->orderBy('id', 'desc')
                        ->take($limit)
                        ->get();
    }

    //////////////////////////////////
    //////////////////////////////////
    public function getSidebarNews() {
        return $this->with('category')
                        ->where('publish', 1)
                        ->where('main', '=', 1)
                        ->orderBy('id', 1)
                        ->orderBy('id', 'desc')
                        ->take(4)
                        ->get();
    }

    //////////////////////////////////
    public function getNewsPrev($postsId, $category_id) {
        $id = $this->where('publish', 1)
                ->where('id', '<', $postsId)
                ->where('category_id', $category_id)
                ->max('id');

        return $this->getNew($id);
    }

    //////////////////////////////////
    public function getNewsNext($postsId, $category_id) {
        $id = $this->where('publish', 1)
                ->where('id', '>', $postsId)
                ->where('category_id', $category_id)
                ->min('id');

        return $this->getNew($id);
    }

    //////////////////////////////////
    public function getNewsByTags($tags, $postsId) {
        if (strpos($tags, ',')) {
            $tags = \explode(',', $tags);
        } else {
            $tags = \str_replace('-', '–', $tags);
            $tags = \explode('–', $tags);
        }
        $like = '';
        $like_last = '';
        $ind = 1;
        foreach ($tags as $tag) {
            if ($ind++ == sizeof($tags)) {
                $like_last = " tags LIKE '%" . $tag . "%' ";
            } else {
                $like .= " or tags LIKE '%" . $tag . "%' ";
            }
        }

        return $this->whereRaw('(' . $like_last . $like . ')')
                        ->where('publish', 1)
                        ->where('id', '!=', $postsId)
                        ->orderBy('id', 'desc')
                        ->take(4)
                        ->get();
    }

    public function getBannerNews($tags) {
        return $this->where('tags', 'LIKE', '%' . $tags . '%')
                        ->where('publish', 1)
                        ->orderBy('id', 'asc')
                        ->paginate(30);
    }

    public function getSearchNews($searchTerms) {
        return $this->where('publish', 1)
                        ->where('title', 'LIKE', '%' . $searchTerms . '%')
                        ->orwhere('descs', 'LIKE', '%' . $searchTerms . '%')
                        ->orderBy('id', 'desc')
                        ->paginate(30)
                        ->appends(['q' => $searchTerms, 'section' => 2]);
    }

    public function getTagsNews($tag) {
        return $this->where('publish', 1)
                        ->where('tags', 'LIKE', '%' . $tag . '%')
                        ->orderBy('id', 'desc')
                        ->orderBy('resort', 'asc')
                        ->paginate(30);
    }

    //////////////////////////////////
    public function getNewsByCategory($category_id, $start, $limit, $locale) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('category_id', $category_id)
                        ->where('language', $locale)
                        ->orderBy('id', 'desc')
                        ->skip($start)
                        ->take($limit)
                        ->paginate($limit);
    }

    public function getOneNewsByCategory($category_id) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('category_id', $category_id)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    public function getCategoryNews($locale, $limit) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('category_id', '=', $locale == 'ar' ? 1 : 2)
                        ->orderBy('id', 'desc')
                        ->paginate($limit);
    }

    //////////////////////////////////
    public function getNewsByMostView($limit) {
        $today = new \DateTime();

        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('created_at', '>', $today->modify('-7 days'))
                        ->orderBy('views', 'desc')
                        ->take($limit)
                        ->get();
    }

    function getLastNews($limit, $locale) {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where('language', '=', $locale)
                        ->orderBy('id', 'desc')
                        ->take($limit)
                        ->get();
    }

//    public function getLastNews($limit, $locale) {
//
//        return $this->with('category')
//                        ->where('publish', '=', 1)
//                        ->where('category_id', '=', $locale == 'ar' ? 1 : 2)
//                        ->orderBy('id', 'desc')
//                        ->take($limit)
//                        ->get();
//    }

    public function getRssNews() {
        return $this->with('category')
                        ->where('publish', '=', 1)
                        ->where("DATE_FORMAT(created_at,'%Y-%m-%d')", '=', date('Y-m-d'))
                        ->orderBy('pub_date', 'desc')
                        ->get();
    }

    //////////////////////////////////////////////
    public function searchNews($News = null, $publish = -1, $category = -1, $start = 0, $length = 25) {
        return $this->with('category')->where(function ($query) use ($News, $publish, $category) {
                    if ($News != '') {
                        $query->where('title', 'LIKE', '%' . $News . '%');
                    }
                    if ($publish != -1) {
                        $query->where('publish', '=', $publish);
                    }
                    if ($category != -1) {
                        $query->where('category_id', '=', $category);
                    }
                })->skip($start)->orderBy('id', 'desc')->take($length);
    }

    //////////////////////////////////////////////
    public function searchNewsCount($News = null, $publish = -1, $category = -1) {
        return $this->where(function ($query) use ($News, $publish, $category) {
                    if ($News != '') {
                        $query->where('title', 'LIKE', '%' . $News . '%');
                    }
                    if ($publish != -1) {
                        $query->where('publish', '=', $publish);
                    }
                    if ($category != -1) {
                        $query->where('category_id', '=', $category);
                    }
                })->count('id');
    }

}
