<?php

namespace App\Services\Companies;

use Illuminate\Support\Facades\Auth;
use App\Company;

class DashboardService
{
    public function getHomeByCompany()
    {
        $company = Company::findOrFail(Auth::id());
        $homes = $company->homes()->orderBy('id', 'DESC')->get();
        return $homes;
    }

}
