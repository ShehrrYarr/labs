<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;


class CompanyController extends Controller
{
     public function __construct()
     {
         $this->middleware('auth');
     }
    public function showCompanies()
    {
        $company = Company::all();
        return view('showCompanies', compact('company'));
    }

     public function storeCompany(Request $request)
{
    // dd($request);
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    if (Company::where('name', $validated['name'])->exists()) {
        return redirect()->back()->withInput()->withErrors([
            'name' => 'Company with this name already exists.',
        ]);
    }

    Company::create([
        'name' => $validated['name'],
    ]);

    return redirect()->back()->with('success', 'Company added successfully!');
}


public function editCompany($id)
    {
        $filterId = Company::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }


     public function updateCompany(Request $request)
{
    $data = Company::findOrFail($request->id);
    $data->name = $request->input('name');

    $data->save();

    return redirect()->back()->with('success', 'Company updated successfully.');
}


public function destroyCompany(Request $request){

   $company = Company::findOrFail($request->id);
    $company->delete();

    return redirect()->back()->with('success', 'Company deleted successfully!');

}
}
