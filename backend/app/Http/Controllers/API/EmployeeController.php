<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\UserResource;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->orderBy('name')
            ->get();

        return UserResource::collection($users);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'role' => $request->validated('role'),
            'password' => null,
        ]);

        return response()->json((new UserResource($user))->resolve(), 201);
    }

    public function show(string $employee)
    {
        $user = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->findOrFail($employee);

        return response()->json((new UserResource($user))->resolve());
    }

    public function update(UpdateEmployeeRequest $request, string $employee)
    {
        $user = User::query()->findOrFail($employee);

        $user->fill($request->validated());
        $user->save();

        return response()->json((new UserResource($user))->resolve());
    }

    public function destroy(Request $request, string $employee)
    {
        $user = User::query()->findOrFail($employee);

        if ((string) $user->id === (string) $request->user()->id) {
            return response()->json(['message' => 'You cannot delete your own account'], 422);
        }

        Attendance::query()->where('user_id', (string) $user->id)->delete();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Employee removed']);
    }
}
