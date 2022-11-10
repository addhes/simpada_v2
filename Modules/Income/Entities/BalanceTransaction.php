<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalanceTransaction extends Model
{
    use HasFactory;
    protected $table = 'balance_transactions';
	protected $fillable = [
		'post_from',
		'trans_code',
		'trans_type', 
		'nominal',
		'last_balance',
		'company_code'
	];
}
