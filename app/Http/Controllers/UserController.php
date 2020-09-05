<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Models\Role;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->police->can_do(User::class,'view',auth()->user())) {
            $roles = Role::all();
            return view('users/list')->with([
                'roles' => $roles
            ]);
        } else {
            return response()->json(['error' => 'Not authorized.'],403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->police->can_do(User::class,'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $user = new User();
        $user->id = 0;
        $roles = Role::all();
        return view('users/form')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->police->can_do(User::class,'create',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $user = new User();
        $user->id = 0;
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->role_id = $params['role_id'];
        if ($params['password'] != '') $user->password = bcrypt($params['password']);
        return $user->save();
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $user_id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(int $user_id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $user_id)
    {
        if (!$this->police->can_do(User::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $user = User::find($user_id);
        $roles = Role::all();
        return view('users/form')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $user_id)
    {
        if (!$this->police->can_do(User::class,'edit',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();
        $user = User::find($user_id);
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->role_id = $params['role_id'];
        if ($params['password'] != '') $user->password = bcrypt($params['password']);
        return $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $user_id)
    {
        if (!$this->police->can_do(User::class,'delete',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $user = User::find($user_id);
        return $user->delete();
    }

    /**
     * Obtain a collection of User instance
     *
     * @param Request $request
     * @return collection /App/Model/User
     */
    public function search(Request $request){
        if (!$this->police->can_do(User::class,'view',auth()->user())) {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $params = $request->all();

        return User::search($params);
    }
}
