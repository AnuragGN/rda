<?php

namespace App\Models;


class Json {

    public function process($what) {

        $catalog = [];
        $catalog['info'] = [];
        $catalog['story'] = [];

        $catalog['info'] = $this->getUpdateInfoJson();
        $catalog['story'] = $this->getUpdateStory();

        return $catalog;
    }

    public function getUpdateInfoJson()
    {
        // read info from DB
        $info = [];
        $info['a'] = 1;
        $info['b'] = 2;
        $info['c'] = 3;
        return $info;

        // update catalog
        $catalog['info'] = $info;
        return $catalog;
    }

    public function getUpdateStory($catalog)
    {
        // read info from DB
        $story = [];
        $story['x'] = 1;
        $story['y'] = 2;
        $story['z'] = 3;
        return $story;

        // update story
        $catalog['story'] = $story;
        return $catalog;
    }

}
