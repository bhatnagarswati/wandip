<?php

namespace App\Http\Controllers\Api\v1\Servicer\Attributes;

use App\Http\Controllers\Controller;
use App\v1\Attributes\Repositories\AttributeRepositoryInterface;
use App\v1\AttributeValues\AttributeValue;
use App\v1\AttributeValues\Repositories\AttributeValueRepository;
use App\v1\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\v1\AttributeValues\Requests\CreateAttributeValueRequest;

class AttributeValueController extends Controller
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepo;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attributeValueRepo;

    /**
     * AttributeValueController constructor.
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository
    ) {
        $this->attributeRepo = $attributeRepository;
        $this->attributeValueRepo = $attributeValueRepository;
    }

    public function create($id)
    {
        return view('servicer.attribute-values.create', [
            'attribute' => $this->attributeRepo->findAttributeById($id)
        ]);
    }

    /**
     * @param CreateAttributeValueRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAttributeValueRequest $request, $id)
    {
        $attribute = $this->attributeRepo->findAttributeById($id);

        $attributeValue = new AttributeValue($request->except('_token'));
        $attributeValueRepo = new AttributeValueRepository($attributeValue);

        $attributeValueRepo->associateToAttribute($attribute);

        $request->session()->flash('message', 'Attribute value created');

        return redirect()->route('servicer.attributes.show', $attribute->id);
    }

    /**
     * @param $attributeId
     * @param $attributeValueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($attributeId, $attributeValueId)
    {
        $attributeValue = $this->attributeValueRepo->findOneOrFail($attributeValueId);

        $attributeValueRepo = new AttributeValueRepository($attributeValue);
        $attributeValueRepo->dissociateFromAttribute();

        request()->session()->flash('message', 'Attribute value removed!');
        return redirect()->route('servicer.attributes.show', $attributeId);
    }
}
