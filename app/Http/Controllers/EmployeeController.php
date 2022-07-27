<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::all();

        return response()->json($employee, 200);
    }

    /**
     * @OA\Post(
     * path="/api/employee",
     * operationId="Create Employee",
     * tags={"Employee"},
     * summary="User input Employee",
     * description="User input Employee here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"branch_id","full_name","nick_name","phone_number"},
     *               @OA\Property(property="branch_id", type="text"),
     *               @OA\Property(property="full_name", type="text"),
     *               @OA\Property(property="nick_name", type="text"),
     *               @OA\Property(property="phone_number", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Insert employee Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Insert employee Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function create(Request $request)
    {
        $data = $request->only('branch_id', 'full_name', 'nick_name', 'phone_number');

        $validator = Validator::make($data, [
            'branch_id' => 'required|integer',
            'full_name' => 'required|string',
            'nick_name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Employee::create([
            'branch_id' => $request->branch_id,
            'full_name' => $request->full_name,
            'nick_name' => $request->nick_name,
            'phone_number' => $request->phone_number,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Simpan data berhasil!',
        ], 200);
    }

    public function update(Request $request)
    {
        $data = $request->only('id', 'branch_id', 'full_name', 'nick_name', 'phone_number');

        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'branch_id' => 'required|integer',
            'full_name' => 'required|string',
            'nick_name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator,
            ], 422);
        }

        $emp = Employee::find($request->id);

        if (is_null($emp)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $emp->branch_id = $request->branch_id;
        $emp->full_name = $request->full_name;
        $emp->nick_name = $request->nick_name;
        $emp->phone_number = $request->phone_number;
        $emp->updated_at = Carbon::now();
        $emp->updated_by = $request->user()->id;
        $emp->save();

        return response()->json([
            'success' => true,
            'message' => 'Ubah data berhasil!',
        ], 200);
    }

    public function delete(Request $request)
    {
        $emp = Employee::find($request->id);
        
        if ($emp) {
            $emp->delete();
        } else {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        return response()->json([
            'message' => 'Hapus data berhasil!',
        ], Response::HTTP_OK);
    }
}
