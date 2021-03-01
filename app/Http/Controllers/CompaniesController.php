<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $user = User::select('id')->where('api_token', $request->api_token)->first()->toArray();
        $companies = Company::select('title', 'phone', 'description')
            ->where('user_id', $user['id'])->get();

        return response(['companies' => $companies]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:255',
            'phone' => 'required|min:5|max:255',
            'description' => 'required|min:1|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'add companies validation error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->except('api_token');
        $data['user_id'] = User::where('api_token', $request->api_token)->first()->id;
        Company::create($data);

        return response(['message' => "Company {$data['title']} is created successfully"], 201);
    }
}
