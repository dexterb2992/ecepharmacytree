<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class StockReturnCode extends Model
{
    protected $guarded = ['id'];
    protected $table = "stock_return_codes";
}
