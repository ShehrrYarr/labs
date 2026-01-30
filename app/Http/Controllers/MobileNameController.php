<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileName;


class MobileNameController extends Controller
{
   public function __construct()
     {
         $this->middleware('auth');
     }
     public function showMobileNames()
    {
        $mobileNames = MobileName::all();
        return view('showMobileNames', compact('mobileNames'));
    }

    public function storeMobileName(Request $request)
{
    // dd($request);
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    if (MobileName::where('name', $validated['name'])->exists()) {
        return redirect()->back()->withInput()->withErrors([
            'name' => 'Mobile with this name already exists.',
        ]);
    }

    MobileName::create([
        'name' => $validated['name'],
    ]);

    return redirect()->back()->with('success', 'Mobile Name added successfully!');
}

public function editMobileName($id)
    {
        $filterId = MobileName::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }

    public function updateMobileName(Request $request)
{
    $data = MobileName::findOrFail($request->id);
    $data->name = $request->input('name');

    $data->save();

    return redirect()->back()->with('success', 'Mobile Name updated successfully.');
}

}
