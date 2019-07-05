<?php

namespace App\Http\Controllers\Api\v1\Servicer\Countries;

use App\v1\Countries\Repositories\CountryRepository;
use App\v1\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\v1\Countries\Requests\UpdateCountryRequest;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    private $countryRepo;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepo = $countryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->countryRepo->listCountries('created_at', 'desc');

        return view('servicer.countries.list', [
            'countries' => $this->countryRepo->paginateArrayResults($list->all(), 10)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $country = $this->countryRepo->findCountryById($id);
        $countryRepo = new CountryRepository($country);
        $provinces = $countryRepo->findProvinces();

        return view('servicer.countries.show', [
            'country' => $country,
            'provinces' => $this->countryRepo->paginateArrayResults($provinces->toArray())
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('servicer.countries.edit', ['country' => $this->countryRepo->findCountryById($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCountryRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $country = $this->countryRepo->findCountryById($id);

        $update = new CountryRepository($country);
        $update->updateCountry($request->except('_method', '_token'));

        $request->session()->flash('message', 'Update successful');
        return redirect()->route('servicer.countries.edit', $id);
    }
}
