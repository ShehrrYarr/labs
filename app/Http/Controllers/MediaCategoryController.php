<?php

namespace App\Http\Controllers;

use App\Models\MediaCategory;
use Illuminate\Http\Request;

class MediaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaCategory = MediaCategory::all();
        return view('media/mediacategory', compact('mediaCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $createCategory = MediaCategory::create([
            'category_name' => $request->category_name,
            'price' => $request->category_price,
        ]);

        if ($createCategory) {

            return back()->with('success', 'Category Created Successfully');
        } else {
            return response()->json('There is some error saving the Category');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaCategory  $mediaCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MediaCategory $mediaCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaCategory  $mediaCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $filterId = MediaCategory::find($id);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);
    }


    public function update(Request $request)
    {

        $filterCategory = MediaCategory::find($request->category_id);
        // dd($request->category_id);



        $filterCategory->category_name = $request->category_name;
        $filterCategory->price = $request->price;


        $filterCategory->save();
        if ($filterCategory)

            return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaCategory  $mediaCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $filterId = MediaCategory::find($id);
        $filterId->delete();
        return back()->with('success', 'Category Deleted Successfully');

    }
}
