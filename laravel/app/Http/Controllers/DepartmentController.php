<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('members')->orderBy('name')->get();
        return response()->json(['departments' => $departments]);
    }

    public function show(Department $department)
    {
        $department->load('members');
        return response()->json(['department' => $department]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:500',
        ]);

        $department = Department::create($data);

        return response()->json(['message' => 'Department created successfully!', 'department' => $department], 201);
    }

    public function update(Request $request, Department $department)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string|max:500',
        ]);

        $department->update($data);

        return response()->json(['message' => 'Department updated successfully!', 'department' => $department]);
    }

    public function destroy(Request $request, Department $department)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully!']);
    }

    public function addMember(Request $request, Department $department)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate(['member_id' => 'required|exists:members,id']);

        if ($department->members()->where('member_id', $data['member_id'])->exists()) {
            return response()->json(['message' => 'Member already in this department'], 422);
        }

        $department->members()->attach($data['member_id']);
        $department->load('members');

        return response()->json(['message' => 'Member added to department!', 'department' => $department]);
    }

    public function removeMember(Request $request, Department $department, $memberId)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $department->members()->detach($memberId);
        $department->load('members');

        return response()->json(['message' => 'Member removed from department!', 'department' => $department]);
    }
}
