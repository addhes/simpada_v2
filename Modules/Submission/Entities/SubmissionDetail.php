<?php

namespace Modules\Submission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmissionDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'submissiondetails';
	protected $fillable = [
		'submission_code',
		'description',
		'nominal',
		'company_code'
	];
}
