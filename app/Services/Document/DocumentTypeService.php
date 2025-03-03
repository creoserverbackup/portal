<?php

namespace App\Services\Document;

use App\Models\OrderTypes;

class DocumentTypeService
{

    public function getTypeNameById($typeId)
    {
        $orderTypes = OrderTypes::find($typeId, 'typeName');
        return ($orderTypes != null) ? $orderTypes->typeName : '';
    }

}