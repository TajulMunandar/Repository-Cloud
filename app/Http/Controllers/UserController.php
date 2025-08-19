<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.user');
    }

    public function getData(Request $request)
    {
        $query = User::query();

        // filter status
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        // filter role
        if ($request->has('is_admin') && $request->is_admin !== null) {
            $query->where('is_admin', $request->is_admin);
        }

        return DataTables::of($query)
            ->addColumn('full_name', function ($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
            ->addColumn('action', function ($user) {
                return '
                    <button class="btn btn-sm btn-warning" onclick="editUser(' . $user->id . ')">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')">
                        <i class="fa fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'nullable|max:100',
            'username'   => 'required|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'is_active'  => 'required|boolean',
            'is_admin'   => 'required|boolean',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'is_active'  => $request->is_active,
            'is_admin'   => $request->is_admin,
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan', 'data' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'nullable|max:100',
            'username'   => 'required|unique:users,username,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6',
            'is_active'  => 'required|boolean',
            'is_admin'   => 'required|boolean',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->username   = $request->username;
        $user->email      = $request->email;
        $user->is_active  = $request->is_active;
        $user->is_admin   = $request->is_admin;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'User berhasil diupdate', 'data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }
}
