<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\Create;
use App\Http\Requests\User\Update;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
        $this->authorizeApiResource("users");
    }

    public function index()
    {
        return $this->userRepository->all();
    }

    public function show($id)
    {
        $user =  $this->userRepository->get($id);
        return success($user,'Get User Successfully');
    }

    public function store(Create $request)
    {
        $user =  $this->userRepository->create($request->all());
        return success($user,'User Created Successfully', 201);
    }

    public function update(User $user, Update $request)
    {
        $user = $this->userRepository->update($user, $request->all());
        return success($user,'User Updated Successfully');
    }

    public function destroy(User $user)
    {
        $this->userRepository->delete($user);
        return success(null, 'User Deleted Successfully',204);
    }

}
