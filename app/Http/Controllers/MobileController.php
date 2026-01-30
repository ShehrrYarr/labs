<?php

namespace App\Http\Controllers;

use App\Models\Mobile;
use App\Models\Group;
use App\Models\Company;
use App\Models\MobileName;
use App\Models\TransferRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MobilesExport;
use App\Exports\SoldMobilesExport;
use App\Models\Restore;
use Illuminate\Support\Facades\DB;



class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware('auth');
     }



    public function manageInventory(Request $request) {
    $groups = Group::all();
    $companies = Company::all();
    $mobileNames = MobileName::all();
    $users = User::all();

    $query = Mobile::where('user_id', auth()->user()->id)
        ->where('availability', 'Available')
        ->where('is_transfer', false)
        ->with('company', 'group', 'mobileName');

    // Apply filters if present
    if ($request->filled('mobile_name_id')) {
        $query->where('mobile_name_id', $request->mobile_name_id);
    }
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }
    if ($request->filled('group_id')) {
        $query->where('group_id', $request->group_id);
    }

    $mobile = $query->get();

    // Sum after filtering
    $totalCostPrice = $mobile->sum('cost_price');

    return view('mobileinventory', compact('mobile', 'users', 'totalCostPrice', 'groups', 'companies', 'mobileNames'));
}




    public function allShopMobile(Request $request) {
    $groups = Group::all();
    $companies = Company::all();
    $mobileNames = MobileName::all();
    $users = User::all();

    $query = Mobile::with('company', 'group', 'mobileName','original_owner','user') ->where('availability', 'Available');

    // Apply filters if present
    if ($request->filled('mobile_name_id')) {
        $query->where('mobile_name_id', $request->mobile_name_id);
    }
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }
    if ($request->filled('group_id')) {
        $query->where('group_id', $request->group_id);
    }

    $mobile = $query->get();


    return view('allmobileinventory', compact('mobile', 'users','groups', 'companies', 'mobileNames'));
}





    public function exportMobiles(Request $request)
     {
         $start_date = $request->input('start_date');
         $end_date = $request->input('end_date');

         // Retrieve mobiles based on the date range and original_owner_id
        //  $mobiles = Mobile::whereBetween('created_at', [$start_date, $end_date])
        //      ->where('original_owner_id', Auth::id())
        //      ->where('availability', 'Available')
        //      ->where('is_transfer', false)
        //      ->get();

         // Generate and return the Excel sheet
         return Excel::download(new MobilesExport($start_date, $end_date), 'mobiles.xlsx');
     }
     
     
      public function exportSoldMobiles(Request $request)
     {
         $start_date = $request->input('start_date');
         $end_date = $request->input('end_date');

         // Generate and return the Excel sheet
         return Excel::download(new SoldMobilesExport($start_date, $end_date), 'sold_mobiles.xlsx');
     }
     
     
     
   public function storeMobile(Request $request)
{
    // dd($request->all());
    // Validate the request data
    $validatedData = $request->validate([
        'imei_number'    => 'required',
        'sim_lock'       => 'required|in:J.V,PTA,Non-PTA',
        'color'          => 'required',
        'storage'        => 'required',
        'cost_price'     => 'required|numeric',
        'selling_price'  => 'required|numeric',
    ]);

    // Create a new mobile record
    $mobile = new Mobile($validatedData);
    $mobile->user()->associate(auth()->user());
    $mobile->original_owner()->associate(auth()->user());
    $mobile->is_approve = 'Not_Approved';
    $mobile->battery_health = $request->battery_health;
    $mobile->company_id = $request->company_id;
    $mobile->group_id = $request->group_id;
    $mobile->mobile_name_id = $request->mobile_name_id;
    $mobile->availability = 'Available';

    $mobile->save();

    // Return a response indicating successful mobile creation
    return redirect()->back()->with('success', 'Mobile created successfully.');
}






    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mobile  $mobile
     * @return \Illuminate\Http\Response
     */
    public function show(Mobile $mobile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mobile  $mobile
     * @return \Illuminate\Http\Response
     */
 public function editMobile($id)
{
    $mobile = \App\Models\Mobile::with('mobileName')->find($id);
    if (!$mobile) {
        return response()->json(['message' => 'Id not found'], 404);
    }

    $result = $mobile->toArray();

    // Determine correct mobile name (relationship OR field)
    if ($mobile->mobile_name_id && $mobile->mobileName) {
        $result['mobile_name_display'] = $mobile->mobileName->name;
    } else {
        $result['mobile_name_display'] = $mobile->mobile_name;
    }

    return response()->json(['result' => $result]);
}





    public function sellMobile(Request $request)
{
    $data = Mobile::findOrFail($request->id);

    
    
   
   
    $data->selling_price = $request->input('selling_price');
    $data->availability = $request->input('availability');
    $data->customer_name = $request->input('customer_name');
    $data->sold_at = Carbon::now();
    
    $data->is_approve = $request->input('is_approve');



    $data->save();

    return redirect()->back()->with('success', 'Mobile Sold successfully.');
}
public function updateMobile(Request $request)
{
    $data = Mobile::findOrFail($request->id);

    // Check if the authenticated user ID is 2
    if (auth()->user()->id === 6) {
        // Update publication data
        $data->mobile_name = $request->input('mobile_name');
        $data->imei_number = $request->input('imei_number');
        $data->sim_lock = $request->input('sim_lock');
        $data->color = $request->input('color');
        $data->storage = $request->input('storage');
        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->customer_name = $request->input('customer_name');
        $data->sold_at = now();
        $data->battery_health = $request->input('battery_health');
        $data->is_approve = $request->input('is_approve');

        $data->save();

        return redirect()->back()->with('success', 'Mobile updated successfully.');
    } else {
        return redirect()->back()->with('danger', "You can't edit the product.");
    }
}


public function restoreMobile(Request $request)
{
    // dd($request->all());
    $data = Mobile::findOrFail($request->id);

    $restoreMobile = new Restore();
   if ($data->mobile_name_id && $data->mobileName) {
    $restoreMobile->mobile_name = $data->mobileName->name;
} else {
    $restoreMobile->mobile_name = $data->mobile_name;
}
    $restoreMobile->imei_number = $data->imei_number;
    $restoreMobile->customer_name = $data->customer_name;
    $restoreMobile->old_cost_price = $data->cost_price;
    $restoreMobile->old_selling_price = $data->selling_price;
    $restoreMobile->new_cost_price = $request->input('cost_price');
    $restoreMobile->new_selling_price = $request->input('selling_price');
    $restoreMobile->restore_by = auth()->user()->name;
    $restoreMobile->save();

        // dd($request);
        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->customer_name = $request->input('customer_name');
        $data->battery_health = $request->input('battery_health');
        $data->is_approve = 'Not_Approved';
        $data->save();

        return redirect()->back()->with('success', 'Mobile Restored successfully.');

    }
    
     public function pendingRestore(Request $request)
{
    $data = Mobile::findOrFail($request->id);
    // dd($request->id);

    if($request->availability == 'Pending'){
    return redirect()->back()->with('danger', 'Please Select a Different Option');
    }

    $data->cost_price = $request->input('cost_price');
    $data->selling_price = $request->input('selling_price');
    $data->availability = $request->input('availability');
    $data->battery_health = $request->input('battery_health');
    $data->save();
    return redirect()->back()->with('success', 'Mobile Restored successfully.');
}

public function receivedPendingRestore(Request $request)
{
    $data = Mobile::findOrFail($request->id);
    // dd($request->id);

    if($request->availability == 'Pending'){
    return redirect()->back()->with('danger', 'Please Select a Different Option');
    }

    $data->cost_price = $request->input('cost_price');
    $data->selling_price = $request->input('selling_price');
    $data->availability = $request->input('availability');
    $data->battery_health = $request->input('battery_health');
    $data->save();
    return redirect()->back()->with('success', 'Mobile Restored successfully.');
}





// public function updateMobile(Request $request)
// {
//     $data = Mobile::findOrFail($request->id);

//     // Update mobile data
//     $data->mobile_name = $request->input('mobile_name');
//     $data->imei_number = $request->input('imei_number');
//     $data->sim_lock = $request->input('sim_lock');
//     $data->color = $request->input('color');
//     $data->storage = $request->input('storage');
//     $data->cost_price = $request->input('cost_price');
//     $data->selling_price = $request->input('selling_price');
//     $data->availability = $request->input('availability');
//     $data->sold_at = now();

//     // Update the is_approve field
//     $data->is_approve = $request->input('is_approve');

//     $data->save();

//     return redirect()->back()->with('success', 'Mobile updated successfully.');
// }


    public function findMobile($id)
    {
        $mobile = \App\Models\Mobile::with('mobileName')->find($id);
    if (!$mobile) {
        return response()->json(['message' => 'Id not found'], 404);
    }

    $result = $mobile->toArray();

    // Determine correct mobile name (relationship OR field)
    if ($mobile->mobile_name_id && $mobile->mobileName) {
        $result['mobile_name_display'] = $mobile->mobileName->name;
    } else {
        $result['mobile_name_display'] = $mobile->mobile_name;
    }

    return response()->json(['result' => $result]);

    }


    public function findApMobile($id)
    {
       $mobile = \App\Models\Mobile::with('mobileName')->find($id);
    if (!$mobile) {
        return response()->json(['message' => 'Id not found'], 404);
    }

    $result = $mobile->toArray();

    // Determine correct mobile name (relationship OR field)
    if ($mobile->mobile_name_id && $mobile->mobileName) {
        $result['mobile_name_display'] = $mobile->mobileName->name;
    } else {
        $result['mobile_name_display'] = $mobile->mobile_name;
    }

    return response()->json(['result' => $result]);

    }

//     public function transferMobile(Request $request)
// {

//     // Validate the request data
//     $request->validate([
//         'to_user_id' => 'required',
//         'mobile_id' => 'required',
//         // Add other validation rules if needed
//     ]);

//     // Find the authenticated user
//     $fromUser = auth()->user();

//     // Find the user to transfer the mobile to
//     $toUser = User::findOrFail($request->to_user_id);

//     // Find the mobile device to be transferred
//     $mobile = Mobile::findOrFail($request->mobile_id);



//     // Update the mobile device's ownership
//     $mobile->user_id = $toUser->id;
//     $mobile->is_transfer = true;
//     $mobile->save();

//     // Create the transfer record
//     $transferRecord = new TransferRecord();
//     $transferRecord->from_user_id = $fromUser->id;
//     $transferRecord->to_user_id = $toUser->id;
//     $transferRecord->mobile_id = $mobile->id;
//     $transferRecord->transfer_time = Carbon::now(); // Set the current timestamp
//     // Set other transfer record data if needed
//     $transferRecord->save();

//     return redirect()->back()->with('success', 'Mobile Trnsfered successfully.');
// }

public function transferMobile(Request $request)
{
    // Validate the request data
    $request->validate([
        'to_user_id' => 'required',
        'mobile_id' => 'required',
        // Add other validation rules if needed
    ]);

    // Find the authenticated user
    $fromUser = auth()->user();

    // Find the user to transfer the mobile to
    $toUser = User::findOrFail($request->to_user_id);

    // Check if the transfer is being made to the same user
    if ($toUser->id === $fromUser->id) {
        return redirect()->back()->with('danger', 'Please select another user to transfer the mobile.');
    }

    // Find the mobile device to be transferred
    $mobile = Mobile::find($request->mobile_id);

    // Update the mobile device's ownership
    $mobile->user_id = $toUser->id;
    $mobile->is_transfer = true;
    $mobile->save();

    // Create the transfer record
    $transferRecord = new TransferRecord();
    $transferRecord->from_user_id = $fromUser->id;
    $transferRecord->to_user_id = $toUser->id;
    $transferRecord->mobile_id = $mobile->id;
    $transferRecord->transfer_time = Carbon::now(); // Set the current timestamp
    // $transferRecord->t_check = true;
    // Set other transfer record data if needed
    $transferRecord->save();




    return redirect()->back()->with('success', 'Mobile transferred successfully.');
}


public function moveToInventory(Request $request)
{
    // dd($request);
    // Find the authenticated user
    $userId = auth()->user()->id;

    // Retrieve the mobile ID from the request
    $mobileId = $request->input('mobile_id');

    // Find the mobile
    $mobile = Mobile::find($mobileId);

    // Check if the mobile belongs to the authenticated user
    if ($mobile->original_owner_id == $userId) {
        $mobile->is_transfer = false;
        $mobile->save();

        return redirect()->back()->with('success', 'Mobile has been moved to main inventory.');
    } else {
        return redirect()->back()->with('danger', "Mobile can't be moved.");
    }
}





// public function findTransferId($id)
// {
//     $filterId = Mobile::find($id);
//     // dd($filterId);
//     if (!$filterId) {

//         return response()->json(['message' => 'Id not found'], 404);
//     }

//     return response()->json(['result' => $filterId]);

// }


public function approve(Request $request)
{
    $mobile = Mobile::findOrFail($request->id);

    // Check if the authenticated user ID matches the original owner ID
    if (auth()->user()->id === $mobile->original_owner_id) {
        $mobile->is_approve = $request->input('is_approve');
        $mobile->save();

        return redirect()->back()->with('success', 'Mobile has been approved successfully.');
    } else {
        return redirect()->back()->with('danger', 'You cannot approve this mobile.');
    }
}

public function approveMobile(Request $request)
{
    $data = Mobile::findOrFail($request->id);

    // Check if the authenticated user has ID 3
    if (auth()->user()->id === 6) {
        $data->is_approve = $request->input('is_approve');
        $data->save();
        return redirect()->back()->with('success', 'Mobile Approved successfully.');
    } else {
        return redirect()->back()->with('danger', 'You cannot approve this mobile.');
    }
}

public function moveToOwner(Request $request)
{
    $mobileId = $request->input('id');

    $mobile = Mobile::findOrFail($mobileId);
    $mobile->user_id = $mobile->original_owner_id;
    $mobile->is_transfer = false;
    $mobile->save();

    // Perform any additional actions or redirect as needed

    return redirect()->back()->with('success', 'Mobile transferred to the original owner successfully.');
}

public function otherInventory($id)
{
    $mobileNames= MobileName::all();

    $mobileData = Mobile::where('user_id', $id)
    ->where('is_transfer', false)
    ->where('availability', 'Available')->with('mobileName')
    ->get();

    return view('otherinventory', ['mobileData' => $mobileData,
 'mobileNames' => $mobileNames]);
}

public function otherTotalInventory($id)
{
    $mobileNames= MobileName::all();
    $mobileData = Mobile::where('user_id', $id)
    ->where('availability', 'Available')->with('mobileName')
    ->get();

    return view('othertotalinventory', ['mobileData' => $mobileData,
 'mobileNames' => $mobileNames]);
}

public function otherSoldInventory($id)
{
    $mobileNames= MobileName::all();

    $mobileData = Mobile::where('user_id', $id)
    ->where('is_transfer', false)
    ->where('availability', 'Sold')
    ->get();

    return view('othersoldinventory', ['mobileData' => $mobileData]);
}

public function otherPendingInventory($id)
{
    $mobileData = Mobile::where('user_id', $id)
    ->where('is_transfer', false)
    ->where('availability', 'Pending')->with('mobileName')
    ->get();

    return view('otherpendinginventory', ['mobileData' => $mobileData]);
}

public function otherDeleteinventory($id)
{
    $mobileData = Mobile::where('user_id', $id)
    ->where('is_transfer', false)
    ->where('availability', 'Deleted')->with('mobileName')
    ->get();

    return view('otherdeleteinventory', ['mobileData' => $mobileData]);
}

public function otherTransferInventory($id)
{
    $mobileData = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) use ($id) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', $id)
        ->whereHas('mobile', function ($query) use ($id) {
            $query->where('user_id', $id)
                ->where('availability', 'Available');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    return view('othertransferinventory', ['mobileData' => $mobileData]);
}
public function otherTransferSoldInventory($id)
{
    $mobileData = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', $id)
        ->whereHas('mobile', function ($query) use ($id) {
            $query->where('user_id', $id)
                ->where('availability', 'Sold');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    return view('othertransfersoldinventory', ['mobileData' => $mobileData]);
}




public function destroy(Request $request)
{
    $filterMobile = Mobile::find($request->id);

    // Check if the authenticated user ID is 2
    if (auth()->user()->id === 6) {
       $filterMobile->availability = 'Deleted';
       $filterMobile->save();
        return redirect()->back()->with('success', 'Mobile Deleted Successfully');
    } else {
        return redirect()->back()->with('danger', "You can't delete the product.");
    }
}

public function bulkEntryForm()
{
    $mobileNames = MobileName::all();
    $companies = Company::all();
    $groups = Group::all();
    return view('bulk_entry', compact('mobileNames', 'companies', 'groups'));
}

public function checkImei(Request $request)
{
    $imei = $request->query('imei');
    $exists = \App\Models\Mobile::where('imei_number', $imei)->exists();
    return response()->json(['exists' => $exists]);
}

public function bulkStore(Request $request)
{
    $mobiles = $request->input('mobiles', []);

    // Validate each row
    foreach ($mobiles as $index => $mobile) {
        $validator = \Validator::make($mobile, [
            'mobile_name_id' => 'required|exists:mobile_names,id',
            'company_id'     => 'required|exists:companies,id',
            'group_id'       => 'required|exists:groups,id',
            'imei_number'    => 'required|unique:mobiles,imei_number',
            'sim_lock'       => 'required|in:J.V,PTA,Non-PTA',
            'color'          => 'required',
            'storage'        => 'required',
            'battery_health' => 'nullable',
            'cost_price'     => 'required|numeric',
            'selling_price'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // If any row fails validation, redirect back with an error, and old input
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Error in row ' . ($index+1) . ': ' . $validator->errors()->first());
        }
    }

    // All data valid, proceed to save
    foreach ($mobiles as $mobile) {
        $newMobile = new \App\Models\Mobile();
        $newMobile->mobile_name_id = $mobile['mobile_name_id'];
        $newMobile->company_id = $mobile['company_id'];
        $newMobile->group_id = $mobile['group_id'];
        $newMobile->imei_number = $mobile['imei_number'];
        $newMobile->sim_lock = $mobile['sim_lock'];
        $newMobile->color = $mobile['color'];
        $newMobile->storage = $mobile['storage'];
        $newMobile->battery_health = $mobile['battery_health'] ?? null;
        $newMobile->cost_price = $mobile['cost_price'];
        $newMobile->selling_price = $mobile['selling_price'];
        $newMobile->user_id = auth()->id();
        $newMobile->original_owner_id = auth()->id();
        $newMobile->is_approve = 'Not_Approved';
        $newMobile->availability = 'Available';
        $newMobile->save();
    }

    return redirect()->back()->with('success', 'All mobiles saved successfully!');
}

public function approveBulk(Request $request)
{
    // Only allow if logged in user is admin (id = 6)
    if (auth()->id() != 6) {
        // For AJAX request, return JSON error
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => "You can't approve this mobile"
            ], 403);
        }
        // For normal requests, redirect back with error
        return redirect()->back()->with('danger', "You can't approve this mobile");
    }

    $ids = $request->input('mobile_ids', []);
    if (!empty($ids)) {
        Mobile::whereIn('id', $ids)->update(['is_approve' => 'Approved']);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false, 'message' => 'No mobiles selected'], 400);
}




}
