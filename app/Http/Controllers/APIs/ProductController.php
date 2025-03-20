<?php

namespace App\Http\Controllers\APIs;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'per_page' => ['nullable'],
                'start'    => ['nullable', 'integer'],
                'length'   => ['nullable', 'integer'],
                'order.*'  => ['array', 'nullable']
            ]);

            $params  = $validator->validate();


            $page             = ($params['start'] /  $params['length']) + 1;
            $perPage          = $params['length'] ?? 10;
            $skip             = ($page -  1) * $perPage;
            $sortBy           = $params['order'][0]['column'] ?? '0';
            $orderBy          = $params['order'][0]['dir'] ?? 'desc';
            $sortKey          = ['products.name', 'products.price', 'products.category_id', 'status'];
            $search           = isset($request['search']['value']) ? NULL : $request['search'];

            $query = Product::select([
                'products.id',
                'products.name',
                'price',
                'cat.name as category',
                'products.category_id',
                'status'
            ])
            ->leftJoin('categories as cat', 'cat.id','products.category_id')
                ->orderBy($sortKey[$sortBy], $orderBy)
                ->when(
                    !is_null($search),
                    fn($subQuery) => $subQuery->where($sortKey[$sortBy], 'LIKE', '%' . $search . '%')
                );

            $recordsFiltered = $recordsTotal = $query->count();
            $products        = $query->skip($skip)->take($perPage)->get();

            $response = [
                'message'         => "Data has been fetched !",
                "draw"            => intval($request->draw),
                "recordsTotal"    => intval($recordsTotal),
                "recordsFiltered" => intval($recordsFiltered),
                'results' => [
                    'data' => $products
                ]
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'        => ['required'],
                'price'       => ['required'],
                'category_id' => ['required'],
                'status'      => ['required']
            ]);

            $params  = $validator->validate();

            $results = Product::create($params);

            $response = [
                'status'  => 'success',
                'message' => "Data has been created !",
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

    public function show(Request $request, $productId)
    {
        try {

            $results = Product::find($productId);

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

    public function update(Request $request, $productId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'        => ['required'],
                'price'       => ['required'],
                'category_id' => ['required'],
                'status'      => ['required']
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
            
            $params  = $validator->validate();

            $results = Product::where('id', $productId)->update($params);

            $response = [
                'status'  => 'success',
                'message' => "Data has been updated !",
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

    public function delete(Request $request, $productId)
    {
        try {

            $results = Product::where('id', $productId)->delete();

            $response = [
                'status'  => 'success',
                'message' => "Data has been deleted !",
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
}
