<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        User::create($data);

        return redirect()->back()->with('success', 'created successfully');
    }
}
