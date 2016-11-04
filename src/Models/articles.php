
<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class pictures extends Eloquent {

    protected $fillable = array('id','photo','title','theme');
   //public $timestamps = false;
    //protected $dateFormat = 'U';
    
}


