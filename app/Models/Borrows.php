<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrows extends Model
{
    use HasFactory;

    protected $table="borrows";
    protected $fillable=[
        'id_book', 
        'id_member', 
        'book_quantity', 
        'borrowed_date', 
        'returned_date'
    ];

    protected $casts = [
        'id_borrow' => 'array',
    ];

    protected $with = [
        'books',
        'members'
    ];

    public function books()
    {
        return $this->belongsTo(Books::class, 'id_book', 'id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class, 'id_member', 'id');
    }
}
