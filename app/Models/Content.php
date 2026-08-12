<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 05-08-2020
 * Time: 19:21
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Content
 * @package App
 */
class Content extends Model
{
    const TYPE_DEFAULT = 'default';
    const ORDER_RANDOM = 'random';
    const ORDER_DEFAULT = 'default';

    /* @var string */
    protected $table = 'content';

    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'content_id';

    /**
     * @param String $order
     * @param int $limit
     * @return mixed
     */
    static public function getArticles($order=self::ORDER_DEFAULT, $limit=2)
    {
        $conditions = [];

        // $conditions['locked'] = 'N';
        $conditions['deleted'] = 'N';
        $conditions['viewable'] = 'Y';

        $contentId = ContentCategory::getArticleId();
        $query = Content::where($conditions)
            ->where(function ($q) use ($contentId) {
                $q->whereNull('content_category_id')->orWhere('content_category_id', $contentId);
            });

        $date = date('Y-m-d');
        $query->where(function ($q) use ($date) {
            $q->whereNull('online_release_date')->orWhere('online_release_date', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('online_end_date')->orWhere('online_end_date', '>=', $date);
        });

        // $query = $query->inRandomOrder();
        $models = $query->orderBy('priority', 'asc')
            ->orderBy('date_published', 'desc')
            ->limit($limit)->get();

        $priority = null;
        foreach($models as $model) {
            if (!$priority) {
                $priority = $model->priority;
            } else {
                if ($priority != $model->priority) {
                    return $models;
                }
            }
        }

        return $order == self::ORDER_RANDOM ? $models->shuffle() : $models;
    }

    /**
     * for DEMO
     * @return mixed
     */
    static private function demoInterestBasedArticles()
    {
        // 436
        // - [A100] Aging Service/HealthCare
        // 439
        // - [A100] Aging Services/Health Care,Home Care and/or Assisted Living
        // - Population Served/Elderly
        // 437
        // - [D100] Children and Family Services/Adoption,Children and Youth Services
        // - Population Served/Children (3 - 11), Youth (12 - 25)
        // 460
        // - [D100] Children and Family Services

        $ciaIds = ContactInterestArea::getInterestAreaIds();

        $ids = [];
        if (in_array('A100', $ciaIds) && !in_array('D100', $ciaIds)){
            $ids = [436, 439];
            return Content::find($ids); // do not shuffle
        } else if (in_array('D100', $ciaIds) && !in_array('A100', $ciaIds)) {
            $ids = [437, 460];
        } else {
            $collection = [436, 437, 439, 460];
            $indices = array_rand($collection, 2);
            shuffle($indices);
            foreach($indices as $i) $ids[] = $collection[$i];
        }
        $models = Content::find($ids);
        return $models->shuffle();
    }

    /**
     * for DEMO
     * @return mixed
     */
    static private function demoArticles()
    {
        $collection = [436, 437, 439, 460];
        $indices = array_rand($collection, 3);
        shuffle($indices);
        $ids = [];
        foreach($indices as $i) $ids[] = $collection[$i];
        $models = Content::find($ids);
        return $models->shuffle();
    }

    /**
     * @param bool|false $interestBased
     * @return mixed
     */
    static public function getPromoArticles($interestBased=false)
    {
        if (ClientInfo::isGNA()) {
            if ($interestBased) {
                return self::demoInterestBasedArticles();
            } else {
                return self::demoArticles();
            }
        }
        return Content::getArticles(Content::ORDER_RANDOM, 2);
    }

    /**
     * for CCT Initiatives/programs
     * @return mixed
     */
    static public function getPrograms()
    {
        $conditions['deleted'] = 'N';
        $conditions['viewable'] = 'Y';
        $conditions['content_category_id'] = ContentCategory::getProgramId();

        $query = Content::where($conditions);

        $date = date('Y-m-d');
        $query->where(function ($q) use ($date) {
            $q->whereNull('online_release_date')->orWhere('online_release_date', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('online_end_date')->orWhere('online_end_date', '>=', $date);
        });

        $models = $query->orderBy('priority', 'asc')
            ->orderBy('date_published', 'desc')
            ->get();

        return $models;
    }

}
