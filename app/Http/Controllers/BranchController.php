<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/branch",
     * operationId="Index Branch",
     * tags={"Branch"},
     * summary="User get Branch",
     * description="User get Branch here",
     *      @OA\Response(
     *          response=201,
     *          description="Insert branch Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Insert branch Successfully",
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
    public function index(Request $request)
    {
        if ($request->user()->role == 'dokter' || $request->user()->role == 'resepsionis') {
            return response()->json([
                'message' => 'The user role was invalid.',
                'errors' => ['Akses User tidak diizinkan!'],
            ], 403);
        }

        $branches = Branch::all();

        return response()->json($branches, 200);
    }

    /**
     * @OA\Post(
     * path="/api/branch",
     * operationId="Create Branch",
     * tags={"Branch"},
     * summary="User input Branch",
     * description="User input Branch here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"branch_code","branch_name","phone_number", "address", "social_media"},
     *               @OA\Property(property="branch_code", type="text"),
     *               @OA\Property(property="branch_name", type="text"),
     *               @OA\Property(property="phone_number", type="text"),
     *               @OA\Property(property="address", type="text"),
     *               @OA\Property(property="social_media", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Insert branch Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Insert branch Successfully",
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
        $data = $request->only('branch_code', 'branch_name', 'phone_number', 'address', 'social_media');

        $validator = Validator::make($data, [
            'branch_code' => 'required|string',
            'branch_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'social_media' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        Branch::create([
            'branch_code' => $request->branch_code,
            'branch_name' => $request->branch_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'social_media' => $request->social_media,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Simpan data berhasil!',
        ], 200);
    }

    /**
     * @OA\Put(
     * path="/api/branch",
     * operationId="updateBranch",
     * tags={"Branch"},
     * summary="User update Branch",
     * description="User update Branch here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     * @OA\Examples(
     *        summary="Update Branch",
     *        example = "Update Branch",
     *       value = {
     *           "id":1,
     *           "branch_code":"BDG",
     *           "branch_name":"bandung",
     *           "phone_number":"081238473847",
     *           "address":"dipati ukur",
     *           "social_media":"ig"
     *         },)),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id","branch_name","phone_number", "address", "social_media"},
     *               @OA\Property(property="id", type="integer",example="2"),
     *               @OA\Property(property="branch_name", type="text",example="pamulang"),
     *               @OA\Property(property="phone_number", type="text",example="082134574837"),
     *               @OA\Property(property="address", type="text",example="jl muchtar"),
     *               @OA\Property(property="social_media", type="text",example="instagram")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Update branch Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Update branch Successfully",
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
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'branch_name' => 'required|string',
            'branch_code' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'social_media' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator,
            ], 422);
        }

        $branch = Branch::find($request->id);

        if (is_null($branch)) {
            return response()->json([
                'message' => 'The data was invalid.',
                'errors' => ['Data tidak ditemukan!'],
            ], 404);
        }

        $branch->branch_code = $request->branch_code;
        $branch->branch_name = $request->branch_name;
        $branch->phone_number = $request->phone_number;
        $branch->address = $request->address;
        $branch->social_media = $request->social_media;
        $branch->updated_at = Carbon::now();
        $branch->updated_by = $request->user()->id;
        $branch->save();

        return response()->json([
            'success' => true,
            'message' => 'Ubah data berhasil!',
        ], 200);
    }

    /**
     * @OA\Delete(
     * path="/api/branch",
     * operationId="Delete Branch",
     * tags={"Branch"},
     * summary="User delete Branch",
     * description="User delete Branch here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(* @OA\Examples(
     *        summary="Delete Branch",
     *        example = "Delete Branch",
     *       value = {
     *           "id":3,
     *         },)),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Delete branch Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Delete branch Successfully",
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
    public function delete(Request $request)
    {
        $branch = Branch::find($request->id);
        
        if ($branch) {
            $branch->delete();
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
