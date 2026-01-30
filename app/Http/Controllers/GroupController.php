<?php

namespace App\Http\Controllers;
use App\Models\Group;

use Illuminate\Http\Request;

class GroupController extends Controller
{
   public function __construct()
     {
         $this->middleware('auth');
     }
     public function showGroups()
    {
        $group = Group::all();
        return view('showGroups', compact('group'));
    }

     public function storeGroup(Request $request)
{
    // dd($request);
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    if (Group::where('name', $validated['name'])->exists()) {
        return redirect()->back()->withInput()->withErrors([
            'name' => 'Gropu with this name already exists.',
        ]);
    }

    Group::create([
        'name' => $validated['name'],
    ]);

    return redirect()->back()->with('success', 'group added successfully!');
}


public function editGroup($id)
    {
        $filterId = Group::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }


     public function updateGroup(Request $request)
{
    $data = Group::findOrFail($request->id);
    $data->name = $request->input('name');

    $data->save();

    return redirect()->back()->with('success', 'Group updated successfully.');
}


public function destroyGroup(Request $request){

   $group = Group::findOrFail($request->id);
    $group->delete();

    return redirect()->back()->with('success', 'Group deleted successfully!');

}
}
