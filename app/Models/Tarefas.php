<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefas extends Model
{
    use HasFactory;

    protected $table = 'tarefas';

    protected $fillable = ['titulo', 'descricao', 'user_id', 'status'];


    public function user() {
        return $this->belongsTo(User::class);
    }

}
