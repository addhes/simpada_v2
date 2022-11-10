<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;
    protected $table = 'incomes';
	protected $fillable = [
		'income_code',
		'title',
		'description',
		'date', 
		'nominal',
		'company_code'
	];
}
