<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 05-08-2020
 * Time: 19:21
 */

namespace App\Models;

/*
 * contact_type_id
 * contact_type
 */

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactType - Contact Types
 * @package App
 */
class ContentCategory extends Model
{
    const TYPE_ARTICLE = 'Article';
    const TYPE_PROGRAM = 'Program';

    /* @var string */
    protected $table = 'content_category';

    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'content_category_id';

    static public function getProgramId()
    {
        return ContentCategory::where('category', self::TYPE_PROGRAM)->pluck('content_category_id')->first();
    }

    static public function getArticleId()
    {
        return ContentCategory::where('category', self::TYPE_ARTICLE)->pluck('content_category_id')->first();
    }

}
