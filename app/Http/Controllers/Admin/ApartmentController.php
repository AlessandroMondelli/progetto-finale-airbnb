<?php

namespace App\Http\Controllers\Admin;

use App\Apartment;
use App\Http\Controllers\Controller;
use App\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartments = Apartment::all();
        return view('admin.apartments.index', ['apartments' => $apartments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.create', [
            'services' => $services
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'sommary_title' => 'required|max:255',
            'description' => 'required',
            'room_number' => 'required|numeric|min:1|max:10',
            'guest_number' => 'required|numeric|min:1|max:10',
            'wc_number' => 'required|numeric|min:1|max:3',
            'square_meters' => 'required|numeric|min:30|max:250',
//            'latitude' => 'required|numeric',
//            'longitude' => 'required|numeric',
            'cover_image' => 'image'
        ]);

        $data = $request->all();
        $apartment = new Apartment();


        if (!empty($data['cover_image'])) {
            $cover_image = $data['cover_image'];
            $cover_image_path = Storage::put('uploads', $cover_image);
            $apartment->cover_image = $cover_image_path;
        }

        $apartment->fill($data);
        $apartment->slug = Str::slug($data['sommary_title']);
        $apartment->user_id = Auth::user()->id;
        $apartment->save();

        if (!empty($data['service_id'])) {
            $apartment->services()->sync($data['service_id']);
        }

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        return view('admin.apartments.show', ['apartment' => $apartment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        $services = Service::all();
        return view('admin.apartments.edit', [
            'apartment' => $apartment,
            'services' => $services

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        $request->validate([
            'sommary_title' => 'required|max:255',
            'description' => 'required',
            'room_number' => 'required|numeric|min:1|max:10',
            'guest_number' => 'required|numeric|min:1|max:10',
            'wc_number' => 'required|numeric|min:1|max:3',
            'square_meters' => 'required|numeric|min:30|max:250',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'cover_image' => 'image',
        ]);

        $data = $request->all();



        if (!empty($data['cover_image'])) {
            $cover_image = $data['cover_image'];

            $cover_image_path = Storage::put('uploads', $cover_image);
            $apartment->cover_image = $cover_image_path;

        }

        $apartment->update($data);

        if (!empty($data['service_id'])) {
            $apartment->services()->sync($data['service_id']);
        }

        return redirect()->route('admin.apartments.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {

        $apartment_image = $apartment->cover_image;
        if (!empty($apartment_image)) {
            Storage::delete($apartment_image);
        }

        if ($apartment->services->isNotEmpty()) {
            $apartment->services()->sync([]);
        }

        $apartment->delete();
        return redirect()->route('admin.apartments.index');
    }
}
