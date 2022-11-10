<?php

namespace Modules\Disbursement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disbursement extends Model
{
    use HasFactory;
    protected $table = 'disbursements';
	protected $fillable = [
		'disbursement_code',
		'title',
		'description',
		'date', 
		'nominal',
		'company_code'
	];
}
