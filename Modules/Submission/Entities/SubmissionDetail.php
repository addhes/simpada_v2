<?php

namespace Modules\Submission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionDetail extends Model
{
    use HasFactory;
    protected $table = 'submissiondetails';
	protected $fillable = [
		'submission_code',
		'description',
		'nominal',
		'company_code'
	];
}
