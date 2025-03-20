<?php

namespace App\Http\Controllers\APIs;

use App\Models\Category;
use Nette\Utils\Validators;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        try {

            $results  = Category::get();
            $response = [
                'status'  => 'success',
                'message' => "Data has been fetched !",
                'results' => $results
            ];

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $response = [
                'errors' => [
                    'code'    => 422,
                    'message' => $e->errors()
                ]
            ];
            return response()->json($response, 422);
        } catch (\Exception $e) {
            $statusCode = ($e->getCode() > 100 && $e->getCode() < 500) ? $e->getCode() : 500;
            $response = [
                'errors' => [
                    'code'    => $statusCode,
                    'message' => ($e->getCode() > 100 && $e->getCode() < 500) ? $e->getMessage() : 'Maaf, terjadi kesalahan'
                ]
            ];
            return response()->json($response, $statusCode);
        }
    }

    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required']
            ]);

            if ($validator->fails()) {
                $response = [
                    'errors' => [
                        'code'    => 422,
                        'message' => $validator->errors()
                    ],
                ];
                return response()->json($response, 422);
            }
            $params = $validator->validate();
            Category::create($params);

            $response = [
                'status'  => 'success',
                'message' => "Data has been created !",
                'results' => $params
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $statusCode = ($e->getCode() > 100 && $e->getCode() < 500) ? $e->getCode() : 500;
            $response = [
                'errors' => [
                    'code'    => $statusCode,
                    'message' => ($e->getCode() > 100 && $e->getCode() < 500) ? $e->getMessage() : 'Maaf, terjadi kesalahan'
                ]
            ];
            return response()->json($response, $statusCode);
        }
    }
}
