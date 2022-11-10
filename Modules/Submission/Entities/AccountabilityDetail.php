<?php

namespace Modules\Submission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountabilityDetail extends Model
{
    use HasFactory;
    protected $table = 'accountabilitydetails';
    protected $fillable = [
		'accountability_code',
		'date', 
		'description',
		'nominal',
		'company_code'
	];
}
