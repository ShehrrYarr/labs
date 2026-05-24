<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSetting extends Model
{
    protected $fillable = [
        'lab_name', 'lab_address', 'lab_email', 'lab_phone',
        'logo', 'footer_note', 'doctors',
    ];

    protected $casts = [
        'doctors' => 'array',
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
}
