<?php

namespace App\Actions;

use App\Services\Convertor\Convertor;
use Illuminate\Database\Eloquent\Collection;


class CatalogProductIndexAttributeAction
{

    public function __construct(private CatalogProductSimpleAttributeAction $simpleAttributeAction,
                                private ReplaceCommaToDotInDecimalAction    $decimalAction)
    {
    }

    /**
     * @param Collection $productAttributeValues - of CatalogProductAttributeAttributeAttributeValue
     * @return array
     */
    public function handle(Collection $productAttributeValues): string
    {
        $result = $this->simpleAttributeAction->handle($productAttributeValues);

        $string = '';
        foreach ($result as $item) {

            $values = [];
            foreach ($item->values as $itemValue) {
                if ($itemValue->type === 'bool' && $itemValue === false) {
                    break;
                }

                $type = match ($itemValue->type) {
                    'string', 'bool' => '',
                    default => $itemValue->type
                };

                $value = match ($itemValue->type) {
                    'bool' => '',
                    default => $itemValue->value
                };

                $valueText = trim("{$value} {$type}");

                if ($valueText) {
                    $values[] = $this->decimalAction->handle($valueText);
                }
            }

            $string .= $item->name . (($values) ? ': ' : '');

            $string .= implode(', ', $values) . PHP_EOL;
        }

        return $string;
    }
}
