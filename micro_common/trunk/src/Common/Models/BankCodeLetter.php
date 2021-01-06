<?php

namespace Micro\Common\Common\Models;


use Illuminate\Database\Eloquent\Model;

class BankCodeLetter extends Model
{
    protected $table = "bank_code_letter";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}