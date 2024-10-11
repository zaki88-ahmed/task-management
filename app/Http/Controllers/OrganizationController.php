<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Models\Manager;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    //
    use ApiDesignTrait;
    private $organization;
    private $manager;

    public function __construct(Organization $organization, Manager $manager) {

        $this->organization = $organization;
        $this->manager = $manager;
    }


    public function getAllOrganization(){

        $organizations = $this->organization->with('manager')->get();
//        dd($organizations);
        return $this->ApiResponse(200, 'Get All Organizations', null, $organizations);
    }
}
