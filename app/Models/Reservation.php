<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'table_id',
        'date',
        'time',
        'pax',
        'notes',
        'status',
    ];

    /**
     * Relasi ke model User (Pelanggan yang membuat reservasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Table (Meja yang dipesan)
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relasi ke model Order (Jika ada pesanan/pre-order yang terhubung)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
