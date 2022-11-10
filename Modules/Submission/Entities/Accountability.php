<?php

namespace Modules\Submission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accountability extends Model
{
    use HasFactory;
    protected $table = 'accountabilities';
	protected $fillable = [
		'accountability_code',
		'submission_code',
		'user_id', 
		'accountability_attachment', 
		'description',
		'date',
		'company_code'
	];
}
