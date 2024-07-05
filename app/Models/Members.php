<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    use HasFactory;

    protected $table="members";
    protected $fillable=['code', 'name', 'penalized_until', 'id_borrow'];

    public function borrows()
    {
        return $this->hasMany(Borrows::class, 'id_member');
    }

    public function addBorrowId($borrowId)
    {
        $idBorrow = $this->id_borrow ?? [];
        $idBorrow[] = $borrowId;
        $this->id_borrow = $idBorrow;
        $this->save();
    }

    public function removeBorrowId($borrowId)
    {
        $idBorrow = $this->id_borrow ?? [];
        $idBorrow = array_diff($idBorrow, [$borrowId]);
        $this->id_borrow = $idBorrow;
        $this->save();
    }

    public function countCurrentBorrows()
    {
        return count($this->id_borrow ?? []);
    }

}
