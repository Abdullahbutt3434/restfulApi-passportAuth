<?php

namespace App\Http\Controllers;

use App\Trait\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {

    }
//
//    protected function allowedAdminAction()
//    {
//        if (Gate::denies('admin-action')) {
//            throw new AuthorizationException('This action is unauthorized');
//        }
    }


