<?php

namespace App\Factories;

class MeiliSearchOptionFactory
{

    private $sorts = [
        'sortByPriceLowToHigh' => ['price:asc'],
        'sortByPriceHighToLow' => ['price:desc'],
        'sortBySoldHighToLow' => ['sold:desc'],
        'sortByRatingHighToLow' => ['rating:desc'],
        'sortByCounterHighToLow' => ['quantity:desc'],
    ];

    public function createFromParams($params)
    {
        $options['sort'] = ['master:asc', 'type:desc'];

        if (!empty($params['sort']) && isset($this->sorts[$params['sort']])) {
            $options['sort'] = $this->sorts[$params['sort']];
        }

        return $options;
    }
}
