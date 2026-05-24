<?php

namespace App\Http\Controllers;

use App\Models\LabSetting;
use Illuminate\Http\Request;

class LabSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->category !== 'admin') abort(403);
            return $next($request);
        });
    }

    public function edit()
    {
        $setting = LabSetting::instance();
        return view('admin.lab_setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'lab_name'    => ['required', 'string', 'max:255'],
            'lab_address' => ['nullable', 'string', 'max:1000'],
            'lab_email'   => ['nullable', 'email', 'max:255'],
            'lab_phone'   => ['nullable', 'string', 'max:50'],
            'footer_note' => ['nullable', 'string', 'max:500'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'doctor_names.*'   => ['nullable', 'string', 'max:255'],
            'doctor_descs.*'   => ['nullable', 'string', 'max:500'],
        ]);

        $setting = LabSetting::instance();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'lab_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('letterheads'), $filename);
            $setting->logo = $filename;
        }

        // Build doctors array from parallel name/desc arrays
        $doctors = [];
        $names = $request->input('doctor_names', []);
        $descs = $request->input('doctor_descs', []);
        foreach ($names as $i => $name) {
            $name = trim($name ?? '');
            if ($name !== '') {
                $doctors[] = [
                    'name'        => $name,
                    'description' => trim($descs[$i] ?? ''),
                ];
            }
        }

        $setting->update([
            'lab_name'    => $data['lab_name'],
            'lab_address' => $data['lab_address'] ?? null,
            'lab_email'   => $data['lab_email'] ?? null,
            'lab_phone'   => $data['lab_phone'] ?? null,
            'footer_note' => $data['footer_note'] ?? 'Electronically generated report — No need of signature',
            'doctors'     => $doctors ?: null,
        ]);

        return redirect()->route('lab-settings.edit')->with('success', 'Lab settings saved.');
    }
}
