<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomeDetail extends Model
{
    use HasFactory;
    protected $table = 'incomedetails';
	protected $fillable = [
		'income_code',
		'description',
		'nominal', 
		'company_code'
	];
}
