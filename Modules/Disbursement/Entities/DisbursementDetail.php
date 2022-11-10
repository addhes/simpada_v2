<?php

namespace Modules\Disbursement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisbursementDetail extends Model
{
    use HasFactory;
    protected $table = 'disbursementdetails';
	protected $fillable = [
		'disbursement_code',
		'description',
		'nominal', 
		'company_code'
	];
}
