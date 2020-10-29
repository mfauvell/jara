<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Http\Resources\UserResource;
use Throwable;
use Illuminate\Support\Facades\Log;

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
        if (!$this->police->can_do('user', 'view',auth()->user(),$user)) {
            Log::warning('No authorized attempt of view a user', ['user' => auth()->user()->id, 'user_acces' => $user->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        return response(['data' => UserResource::make($user)], 200);
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
            unset($request['password']);
            Log::warning('No authorized attempt of create a user', ['user' => auth()->user()->id, 'request' => $request]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();
            $user = new User();
            $user->id = 0;
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->role_id = $params['role_id'];
            if ($params['password'] != '') $user->password = bcrypt($params['password']);
            $user->save();
            return response(['data' => UserResource::make($user)],201);
        } catch (Throwable $e) {
            unset($request['password']);
            Log::error('An error has ocurred when create a user', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
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
            unset($request['password']);
            Log::warning('No authorized attempt of update a user', ['user' => auth()->user()->id, 'request' => $request, 'user_access' => $user->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->role_id = $params['role_id'];
            if ($params['password'] != '') $user->password = bcrypt($params['password']);
            $user->save();
            return response(['data' => UserResource::make($user)],200);
        } catch (Throwable $e) {
            unset($request['password']);
            Log::error('An error has ocurred when update a user', ['exception' => $e, 'request' => $request, 'user' => $user->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
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
            Log::warning('No authorized attempt of delete a user', ['user' => auth()->user()->id, 'user_access' => $user->id]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $user->delete();
            return response(['data' => $user->id],200);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when delete a user', ['exception' => $e, 'user' => $user->id]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }

    /**
     * Obtain a collection of User instance
     *
     * @param Request $request
     * @return collection /App/Model/User
     */
    public function search(Request $request){
        if (!$this->police->can_do('user','view',auth()->user())) {
            Log::warning('No authorized attempt of search users', ['user' => auth()->user()->id, 'request' => $request]);
            return response()->json(['error' => 'Not authorized.'],403);
        }
        try {
            $params = $request->all();

            $users = User::search($params);
            return response(['data' => UserResource::collection($users)],200);
        } catch (Throwable $e) {
            Log::error('An error has ocurred when delete a user', ['exception' => $e, 'request' => $request]);
            return response()->json(['error' => 'Unexpected server error.'],500);
        }
    }
}
