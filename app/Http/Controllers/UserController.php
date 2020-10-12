<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Models\Role;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Police to control permissions
     *
     * @var \App\Models\Police
     */
    protected $police;

    public function __construct(Police $police)
    {
        $this->police = $police;
    }

    public function show(User $user)
    {
        return response(['data' => new UserResource($user)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->police->can_do('user', 'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $user = new User();
        $user->id = 0;
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->role_id = $params['role_id'];
        if ($params['password'] != '') $user->password = bcrypt($params['password']);
        $user->save();
        return response(['data' => new UserResource($user)],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!$this->police->can_do('user', 'edit', auth()->user(), $user)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->role_id = $params['role_id'];
        if ($params['password'] != '') $user->password = bcrypt($params['password']);
        $user->save();
        return response(['data' => new UserResource($user)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete(User $user)
    {
        if (!$this->police->can_do('user', 'delete', auth()->user(), $user)) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $user->delete();
        return response(['data' => $user->id],200);
    }

    /**
     * Obtain a collection of User instance
     *
     * @param Request $request
     * @return collection /App/Model/User
     */
    public function search(Request $request){
        if (!$this->police->can_do('user','view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();

        $users = User::search($params);
        return response(['data' => UserResource::collection($users)],200);
    }
}
