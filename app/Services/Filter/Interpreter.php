<?php

namespace App\Services\Filter;

class Interpreter
{
    public function queryToParams(array $query)
    {

        $attributes = [];
        $configurators = [];
        $price = [];
        foreach ($query as $key => $value) {
            // attributes
            if (stripos($key, 'a_') === 0) {
                /*
                    $parts[0]=>'a_' - name param
                    $parts[1]=>'7' - id param
                    $parts[2] => ids,id,from,to,is - type value
                 */
                $parts = explode('_', $key);


                /*
                    if  $value type is ids need create array values
                 */
                if ($parts[2] === 'ids') {
                    $value = is_string($value) ? [(int)$value] : $value;
                }


                $attributes[$parts[1]]['attribute_id'] = (int)$parts[1];
                $attributes[$parts[1]]['value'][$parts[2]] = $value;
                unset($query[$key]);
            } elseif (stripos($key, 'c_') === 0) {
                $parts = explode('_', $key);

                if ($parts[2] === 'ids') {
                    $value = is_string($value) ? [(int)$value] : $value;
                }

                $configurators[$parts[1]]['configurator_id'] = (int)$parts[1];
                $configurators[$parts[1]]['value'][$parts[2]] = $value;
                unset($query[$key]);
            } elseif (stripos($key, 'price_') === 0) {
                $parts = explode('_', $key);
                $price[$parts[1]] = $value;
                unset($query[$key]);
            }
        }

        /*    result
          $attributes[$parts[1]] = [
                            'attribute_id' => (int)$parts[1],
                            'value' => [$parts[2] => $value]
                         ];
     */
        if ($attributes) {
            $query['attribute'] = array_values($attributes);
        }

        if ($configurators) {
            $query['configurator'] = array_values($configurators);
        }

        if ($price) {
            $query['price'] = $price;
        }

        return $query;
    }
}
