<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSetting extends Model
{
    protected $fillable = [
        'lab_name', 'lab_address', 'lab_email', 'lab_phone',
        'logo', 'footer_note', 'bottom_notice', 'bottom_notice_style', 'doctors',
        'header_images', 'header_canvas_json', 'watermark_canvas_json',
        'report_background_color',
    ];

    protected $casts = [
        'doctors'              => 'array',
        'header_images'        => 'array',
        'bottom_notice_style'  => 'array',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'lab_name'    => 'Al Ghani Lab',
            'footer_note' => 'Electronically generated report — No need of signature',
        ]);
    }

    public function logoPath(): ?string
    {
        return $this->logo ? public_path('letterheads/' . $this->logo) : null;
    }

    public function logoBase64(): ?string
    {
        $path = $this->logoPath();
        if (!$path || !file_exists($path)) return null;
        $ext  = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $ext . ';base64,' . base64_encode($data);
    }

    /** Returns base64 data-URI of the canvas-designed composite header, or null. */
    public function headerCompositeBase64(): ?string
    {
        $path = public_path('letterheads/header_composite.png');
        if (!file_exists($path)) return null;
        return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
    }

    /** Returns base64 data-URI of the saved watermark composite PNG, or null. */
    public function watermarkCompositeBase64(): ?string
    {
        $path = public_path('letterheads/watermark_composite.png');
        if (!file_exists($path)) return null;
        return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
    }
}
