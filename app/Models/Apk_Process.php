<?php

namespace App\Models;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Apk_Process extends Model
{
    use Searchable;
    use HasFactory;
    public $timestamps = null;
    protected $table = 'apk_process';
    protected $fillable = [
        'appid ',
        'package',
        'category',
        'download',
        'title ',
        'icon',
        'screenshot',
        'description',
        'uptime',
        'vercode',
        'verStr',
        'pss_console',
        'pss_ads',
        'pss_rebuild',
        'pss_aab',
        'pss_chplay',
        'pss_huawei',
        'pss_sdk',
    ];


    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'appid' => $this->appid,
            'package' => $this->package,
            'title' => $this->title,
            'description' => $this->description,
            'pss_ads' => $this->pss_ads,
            'pss_console' => $this->pss_console,
        ];
    }

//
//    protected $mappingProperties = array(
//        'title' => array(
//            'type' => 'string',
//            'analyzer' => 'standard'
//        ),
//        'package' => array(
//            'type' => 'string',
//            'analyzer' => 'standard'
//        )
//    );

//    function getIndexName()
//    {
//        return 'apk_process';
//    }
}
