<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PivotPlCpl extends Model
{
    protected $table = 'pivot_pl_cpls';
    protected $fillable = ['kurikulum_id', 'pl_id', 'cpl_id'];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function pl()
    {
        return $this->belongsTo(ProfileLulusan::class, 'pl_id');
    }

    public function cpl()
    {
        return $this->belongsTo(CapaianPembelajaranLulusan::class, 'cpl_id');
    }


}
