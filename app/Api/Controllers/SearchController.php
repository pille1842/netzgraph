<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function search(Request $request)
    {
        if (!$request->has('q')) {
            return $this->response->errorBadRequest();
        }

        $q = $request->input('q');
    }
}
