<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check fields
        $fields = $request->validate([
            'firstname' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'email' => ['required', 'max:50'],
            'password' => ['required', 'min:8', 'max:50'],
            'confirmation_password' => ['required', 'max:50'],
            'role' => ['required', Rule::in(['student', 'teacher'])],
        ]);

        // check if password and confirmation password are equal
        // check if email and role are assigned correctly
        if (strcmp($request->get('password'), $request->get('confirmation_password')) == 0 &&
            (preg_match("/(.+)@onmicrosoft.upb.ro/", $request->get('email')) &&
            strcmp($request->get('role'), 'teacher') == 0 || 
            preg_match("/(.+)@stud.upb.ro/", $request->get('email')) &&
            strcmp($request->get('role'), 'student') == 0)) {
            // create user
            $user = User::create([
                'firstname' => $fields['firstname'],
                'lastname' => $fields['lastname'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'role' => $fields['role'],
            ]);
            return $user;
        }
        return response()->json(['errors' => ['input' => ['The input is invalid.']]], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // check if id exists
        if (User::select('id')->where('id',$id)->exists())
            return User::find($id);
        return response()->json(['errors' => ['id' => ['The id is invalid.']]], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // check if logged user updates its fields
        if (auth()->user()->id != $id)
            return response()->json(["message" => "Unauthorized."], 403);
        // check if password and confirmation_password are equal
        if ($request->has('password') xor $request->has('confirmation_password')) {
            return response()->json(['errors' => ['input' => ['The input is invalid.']]], 400);
        }
        
        // check if id exists
        if (User::select('id')->where('id',$id)->exists()) {
            // check if field exists and updates data
            if ($request->has('firstname'))
                User::where('id', $id)->where('id', $id)->update(array('firstname' => $request->get('firstname')));
            if ($request->has('lastname'))
                User::where('id', $id)->where('id', $id)->update(array('lastname' => $request->get('lastname')));
            if ($request->has('password'))
                User::where('id', $id)->where('id', $id)->update(array('password' => $request->get('password')));
            if ($request->has('email'))
                User::where('id', $id)->where('id', $id)->update(array('email' => $request->get('email')));
            
            return User::find($id);
        }

        return response()->json(['errors' => ['id' => ['The id is invalid.']]], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if logged user deletes himself
        if (auth()->user()->id != $id)
            return response()->json(["message" => "Unauthorized."], 403);
        // check if id exists
        if (User::select('id')->where('id',$id)->exists()) {
            return User::destroy($id);
        }
        return response()->json(['errors' => ['id' => ['The id is invalid.']]], 404);
    }

    public function login(Request $request) {

        // check if fields exist
        if (!($request->has('password')) || !($request->has('email'))) {
            return response()->json(['errors' => ['input' => ['The input is invalid.']]], 400);
        }
        // validate fields
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

    
        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Bad creds'], 401);
        }
        
        // create token for user
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

}
