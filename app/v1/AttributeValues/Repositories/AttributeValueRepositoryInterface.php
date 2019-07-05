<?php

namespace App\v1\AttributeValues\Repositories;

use App\v1\Attributes\Attribute;
use App\v1\AttributeValues\AttributeValue;
use Jsdecena\Baserepo\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface AttributeValueRepositoryInterface extends BaseRepositoryInterface
{
    public function createAttributeValue(Attribute $attribute, array $data) : AttributeValue;

    public function associateToAttribute(Attribute $attribute) : AttributeValue;

    public function dissociateFromAttribute() : bool;

    public function findProductAttributes() : Collection;
}
