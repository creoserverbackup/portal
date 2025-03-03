<?php

namespace App\Http\Resources\Catalog;


use App\Models\CatalogProduct;
use App\Services\Product\ProductCategoryService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CatalogProduct
 */
class PageCatalogProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $productCategoryService = new ProductCategoryService();
        $option = request()->get('option');

        if (empty($option) || $option == 'false') {
            return [
                    "id" => $this->productId,
                    "productId" => $this->productId,
                    'markName' => $this->catalogMark ? $this->catalogMark->markName : '',
                    "catalog_mark" => '',
                    "article" => $this->article,
                    "masterId" => $this->masterId,
                    "formFactor" => $this->formFactor,
                    "visible" => $this->visible,
                    "version_type" => $this->version_type,
                    "meta_title" => $this->meta_title,
                    "meta_description" => $this->meta_description,
                    "state" => (int)$this->state,
                    "type" => $this->type,
                    "name" => $this->name,
                    "rating" => $this->rating,
                    "quantity" => '',
                    "sold" => $this->sold,
                    "sku" => $this->sku,
                    "catalog_product_template" => '',
                    "multiBatch" => $this->multiBatch,
                    "price" => '',
                    "priceOld" => '',
                    "priceBase" => '',
                    "priceSale" => '',
                    "warranty" => '',
                    "longDescription" => '',
                    "orderAvailable" => false,
                    "defImages" => [],
                    "images" => [],
                    "gallery" => [],
                    "review_rating" => '',
                    "options" => [],
                    "hard_disk_slot" => [],
                    'attributes' => [],
                    'configurator_attributes' => $this->configurator_attributes,
                    'associated' => [],
                    'products_configurator_out_stock' => '',
                    'master_slug' => '',
                    'sale' => $this->sale,
                    'categoryMain' => '',
            ];
        } else {
            $dataConfiguratorQuantity = $this->slave_configurator_quantity;

            $orderAvailable = $this->order_available;

            if ($dataConfiguratorQuantity['quantity'] <= 0) {
                $orderAvailable = CatalogProduct::STATUS_ORDER_AVAILABLE['no'];
            }

            return [
                    "id" => $this->productId,
                    "productId" => $this->productId,
                    'markName' => $this->catalogMark ? $this->catalogMark->markName : '',
                    "catalog_mark" => new PageCatalogProductCatalogMarkResource($this->whenLoaded('catalogMark')),
                    "article" => $this->article,
                    "masterId" => $this->masterId,
                    "visible" => $this->visible,
                    "formFactor" => $this->formFactor,
                    "version_type" => $this->version_type,
                    "meta_title" => $this->meta_title,
                    "meta_description" => $this->meta_description,
                    "state" => (int)$this->state,
                    "type" => $this->type,
                    "name" => $this->name,
                    "rating" => $this->rating,
                    "quantity" => $dataConfiguratorQuantity['quantity'],
                    "sold" => $this->sold,
                    "sku" => $this->sku,
                    "catalog_product_template" => new PageCatalogProductTemplateResource($this->catalogProductTemplate),
                    "multiBatch" => $this->multiBatch,
                    "price" => $this->configurator_price,
                    "priceOld" => $this->configurator_price_old,
                    "priceBase" => $this->price_base,
                    "priceSale" => $this->price_sale,
                    "warranty" => $this->catalogProductDesc->warranty,
                    "longDescription" => empty($this->catalogProductDesc->description) ? $this->text : $this->catalogProductDesc->description,
                    "orderAvailable" => $orderAvailable,
                    "defImages" => $this->is_default_images,
                    "images" => $this->images,
                    "gallery" => $this->gallery,
                    "review_rating" => $this->review_rating,
                    "options" => $this->configurator_options,
                    "hard_disk_slot" => $this->configurator_hard_disk_slot,
                    'attributes' => $this->product_attributes,
                    'configurator_attributes' => $this->configurator_attributes,
                    'associated' => CatalogProductAssociatedResource::collection($this->catalogProductAssociated),
                    'products_configurator_out_stock' => $dataConfiguratorQuantity['productsConfiguratorOutStock'],
                    'products_configurator_default_change' => $dataConfiguratorQuantity['productsConfiguratorDefaultChange'],
                    'master_slug' => $dataConfiguratorQuantity['masterSlug'],
                    'sale' => $this->sale,
                    'categoryMain' => $productCategoryService->getCategoryMain($this->category),
            ];
        }
    }
}
