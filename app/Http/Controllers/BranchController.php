<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();

        return $branches;
    }

    public function create(Request $request)
    {
        $data = $request->only('branch_name', 'phone_number', 'address', 'social_media');

        $validator = Validator::make($data, [
            'branch_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'social_media' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Branch::create([
            'branch_name' => $request->branch_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'social_media' => $request->social_media,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Simpan data berhasil!',
        ], Response::HTTP_OK);
    }
}
