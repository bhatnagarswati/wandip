<?php

namespace App\Http\Controllers\Api\v1\Servicer\Attributes;

use App\Http\Controllers\Controller;
use App\v1\Attributes\Exceptions\AttributeNotFoundException;
use App\v1\Attributes\Exceptions\CreateAttributeErrorException;
use App\v1\Attributes\Exceptions\UpdateAttributeErrorException;
use App\v1\Attributes\Repositories\AttributeRepository;
use App\v1\Attributes\Repositories\AttributeRepositoryInterface;
use App\v1\Attributes\Requests\CreateAttributeRequest;
use App\v1\Attributes\Requests\UpdateAttributeRequest;

class AttributeController extends Controller
{
    private $attributeRepo;

    /**
     * AttributeController constructor.
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepo = $attributeRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $results = $this->attributeRepo->listAttributes();
        $attributes = $this->attributeRepo->paginateArrayResults($results->all());

        return view('servicer.attributes.list', compact('attributes'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('servicer.attributes.create');
    }

    /**
     * @param CreateAttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAttributeRequest $request)
    {
        $attribute = $this->attributeRepo->createAttribute($request->except('_token'));
        $request->session()->flash('message', 'Create attribute successful!');

        return redirect()->route('servicer.attributes.edit', $attribute->id);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $attribute = $this->attributeRepo->findAttributeById($id);
            $attributeRepo = new AttributeRepository($attribute);

            return view('servicer.attributes.show', [
                'attribute' => $attribute,
                'values' => $attributeRepo->listAttributeValues()
            ]);
        } catch (AttributeNotFoundException $e) {
            request()->session()->flash('error', 'The attribute you are looking for is not found.');

            return redirect()->route('servicer.attributes.index');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = $this->attributeRepo->findAttributeById($id);

        return view('servicer.attributes.edit', compact('attribute'));
    }

    /**
     * @param UpdateAttributeRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAttributeRequest $request, $id)
    {
        try {
            $attribute = $this->attributeRepo->findAttributeById($id);

            $attributeRepo = new AttributeRepository($attribute);
            $attributeRepo->updateAttribute($request->except('_token'));

            $request->session()->flash('message', 'Attribute update successful!');

            return redirect()->route('servicer.attributes.edit', $attribute->id);
        } catch (UpdateAttributeErrorException $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('servicer.attributes.edit', $id)->withInput();
        }
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        $this->attributeRepo->findAttributeById($id)->delete();

        request()->session()->flash('message', 'Attribute deleted successfully!');

        return redirect()->route('servicer.attributes.index');
    }
}
