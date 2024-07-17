<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RoleController extends Controller
{
    public function index()
    {
        return Role::all();
    }

    public function show(Role $role)
    {
        return $role;
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response()->json($role, 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }

    public function assignPermission(Request $request, Role $role){
        $role->givePermissionTo($request->permissions);
        return response()->json(['message' => 'Permission assigned successfully'], 200);
    }
}
