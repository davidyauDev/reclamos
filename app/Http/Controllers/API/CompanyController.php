<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class CompanyController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $companies = Company::query()->paginate($perPage);
        Log::info($companies);
        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }


    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = Company::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
        ], 201);
    }


    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }


    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $company->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }


    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted',
        ]);
    }
}
