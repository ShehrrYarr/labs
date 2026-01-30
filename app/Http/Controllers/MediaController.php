<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaCategory;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaDetails = Media::with('category')->get();
        $mediaCategory = MediaCategory::get();
        return view('media/createmedia', compact('mediaCategory','mediaDetails'));
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $createMedia = Media::create([
            'media_agency_name' => $request->media_agency_name,
            'media_category_id' => $request->media_category_id,
        ]);

        if ($createMedia) {

            return back()->with('success', 'Media Created Successfully');
        } else {
            return response()->json('There is some error saving the Category');
        }
    }


    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $filterId = Media::with('category')->find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $filterData = Media::find($request->id);
        // dd($request->id);



        $filterData->media_agency_name = $request->media_agency_name;
        $filterData->media_category_id = $request->media_category_id;


        $filterData->save();
        if ($filterData)

            return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $filterId = Media::find($id);
        $filterId->delete();
        return back()->with('success', 'Category Deleted Successfully');
    }
}
