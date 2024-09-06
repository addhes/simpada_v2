<?php

namespace Modules\Submission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'submissions';
	protected $fillable = [
		'user_id',
		'submission_code',
		'title',
		'description',
		'channel_id',
		'estimated_price',
		'destination_account',
		'account_number',
		'category_id',
		'bank_id',
		'finance_app',
		'finance_desc',
		'finance_app_at',
		'last_balance',
		'admin_app',
		'admin_desc',
		'admin_app_at',
		'user_attachment',
		'finance_attachment',
		'status',
		'status_date',
		'company_code'
	];
}
