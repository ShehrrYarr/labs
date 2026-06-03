<?php

namespace App\Http\Controllers;

use App\Models\LabSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'lab_name'              => ['required', 'string', 'max:255'],
            'lab_address'           => ['nullable', 'string', 'max:1000'],
            'lab_email'             => ['nullable', 'email', 'max:255'],
            'lab_phone'             => ['nullable', 'string', 'max:50'],
            'footer_note'           => ['nullable', 'string', 'max:500'],
            'bottom_notice'         => ['nullable', 'string', 'max:300'],
            'bottom_notice_color'   => ['nullable', 'string', 'max:20'],
            'bottom_notice_size'    => ['nullable', 'integer', 'min:6', 'max:72'],
            'bottom_notice_align'   => ['nullable', 'in:left,center,right'],
            'bottom_notice_italic'  => ['nullable'],
            'bottom_notice_bold'    => ['nullable'],
            'logo'                  => ['nullable', 'image', 'max:2048'],
            'doctor_names.*'        => ['nullable', 'string', 'max:255'],
            'doctor_descs.*'        => ['nullable', 'string', 'max:500'],
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

        $bottomNoticeStyle = [
            'color'  => $data['bottom_notice_color']  ?? '#1d4ed8',
            'size'   => (int)($data['bottom_notice_size']   ?? 10),
            'align'  => $data['bottom_notice_align']  ?? 'right',
            'italic' => $request->has('bottom_notice_italic'),
            'bold'   => $request->has('bottom_notice_bold'),
        ];

        $setting->update([
            'lab_name'             => $data['lab_name'],
            'lab_address'          => $data['lab_address'] ?? null,
            'lab_email'            => $data['lab_email'] ?? null,
            'lab_phone'            => $data['lab_phone'] ?? null,
            'footer_note'          => $data['footer_note'] ?? 'Electronically generated report — No need of signature',
            'bottom_notice'        => $data['bottom_notice'] ?? null,
            'bottom_notice_style'  => $bottomNoticeStyle,
            'doctors'              => $doctors ?: null,
        ]);

        return redirect()->route('lab-settings.edit')->with('success', 'Lab settings saved.');
    }

    /** AJAX: upload one image to the gallery */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => ['required', 'image', 'max:4096']]);

        $file     = $request->file('image');
        $filename = 'header_img_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('letterheads'), $filename);

        $setting           = LabSetting::instance();
        $images            = $setting->header_images ?? [];
        $images[]          = $filename;
        $setting->update(['header_images' => $images]);

        return response()->json([
            'ok'       => true,
            'filename' => $filename,
            'url'      => asset('letterheads/' . $filename),
        ]);
    }

    /** AJAX: delete one image from the gallery */
    public function deleteImage(Request $request)
    {
        $request->validate(['filename' => ['required', 'string', 'max:255']]);

        $filename = basename($request->input('filename'));
        $setting  = LabSetting::instance();
        $images   = collect($setting->header_images ?? [])
                        ->reject(fn($f) => $f === $filename)
                        ->values()
                        ->toArray();

        $setting->update(['header_images' => $images ?: null]);

        // Only delete files we created (prevents path traversal)
        if (str_starts_with($filename, 'header_img_')) {
            $path = public_path('letterheads/' . $filename);
            if (file_exists($path)) @unlink($path);
        }

        return response()->json(['ok' => true]);
    }

    /** AJAX: save canvas JSON + composite PNG */
    public function saveCanvas(Request $request)
    {
        $request->validate([
            'canvas_json'    => ['required', 'string'],
            'composite_data' => ['required', 'string'],
        ]);

        $setting = LabSetting::instance();
        $setting->update(['header_canvas_json' => $request->input('canvas_json')]);

        $dataUrl = $request->input('composite_data');
        if (preg_match('/^data:image\/(png|jpeg|jpg);base64,(.+)$/s', $dataUrl, $m)) {
            $imgData = base64_decode($m[2]);
            if ($imgData !== false) {
                if (!is_dir(public_path('letterheads'))) {
                    mkdir(public_path('letterheads'), 0755, true);
                }
                file_put_contents(public_path('letterheads/header_composite.png'), $imgData);
            }
        }

        return response()->json(['ok' => true]);
    }

    /** AJAX: save watermark canvas JSON + composite PNG (transparent background) */
    public function saveWatermarkCanvas(Request $request)
    {
        $request->validate([
            'canvas_json'    => ['required', 'string'],
            'composite_data' => ['required', 'string'],
        ]);

        $setting = LabSetting::instance();
        $setting->update(['watermark_canvas_json' => $request->input('canvas_json')]);

        $dataUrl = $request->input('composite_data');
        if (preg_match('/^data:image\/(png|jpeg|jpg);base64,(.+)$/s', $dataUrl, $m)) {
            $imgData = base64_decode($m[2]);
            if ($imgData !== false) {
                if (!is_dir(public_path('letterheads'))) {
                    mkdir(public_path('letterheads'), 0755, true);
                }
                file_put_contents(public_path('letterheads/watermark_composite.png'), $imgData);
            }
        }

        return response()->json(['ok' => true]);
    }
}
