<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        return response()->json([
            'members' => Member::orderByDesc('created_at')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'email' => 'required|email|unique:members,email',
            'phoneNumber' => 'required',
            'address' => 'required|string',
            'maritalStatus' => 'required|string',
            'baptismStatus' => 'required|string',
            'membershipNumber' => 'required|integer|unique:members,membershipNumber',
            'joinDate' => 'required|date',
            'activeStatus' => 'required|string',
        ]);

        $validatedData['phoneNumber'] = (string) $validatedData['phoneNumber'];

        $member = Member::create($validatedData);

        return response()->json(['message' => 'Member added successfully!', 'member' => $member], 201);
    }

    public function update(Request $request, Member $member)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phoneNumber' => 'required',
            'address' => 'required|string',
            'maritalStatus' => 'required|string',
            'baptismStatus' => 'required|string',
            'membershipNumber' => 'required|integer|unique:members,membershipNumber,' . $member->id,
            'joinDate' => 'required|date',
            'activeStatus' => 'required|string',
        ]);

        $validatedData['phoneNumber'] = (string) $validatedData['phoneNumber'];
        $member->update($validatedData);

        return response()->json(['message' => 'Member updated successfully!', 'member' => $member]);
    }

    public function destroy(Request $request, Member $member)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $member->delete();

        return response()->json(['message' => 'Member deleted successfully!']);
    }
}
