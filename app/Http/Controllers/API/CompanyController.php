<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *     title="Company API",
 *     version="1.0.0",
 *     description="API documentation for managing companies."
 * )
 *
 * @OA\Tag(
 *     name="Companies",
 *     description="API Endpoints for Companies"
 * )
 *
 * @OA\Schema(
 *     schema="Company",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Example Company"),
 *     @OA\Property(property="email", type="string", format="email", example="example@company.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-18T12:34:56Z")
 * )
 *
 * @OA\Schema(
 *     schema="StoreCompanyRequest",
 *     type="object",
 *     required={"ruc", "razon_social", "departamento", "provincia", "distrito", "direccion"},
 *     @OA\Property(property="ruc", type="string", example="12345678901", description="RUC de la empresa (11 dígitos)"),
 *     @OA\Property(property="razon_social", type="string", example="Mi Empresa S.A.", description="Razón social de la empresa"),
 *     @OA\Property(property="departamento", type="string", example="Lima", description="Departamento de la empresa"),
 *     @OA\Property(property="provincia", type="string", example="Lima", description="Provincia de la empresa"),
 *     @OA\Property(property="distrito", type="string", example="Miraflores", description="Distrito de la empresa"),
 *     @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123", description="Dirección de la empresa")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateCompanyRequest",
 *     type="object",
 *     required={"ruc", "razon_social", "departamento", "provincia", "distrito", "direccion"},
 *     @OA\Property(property="ruc", type="string", example="12345678901", description="RUC de la empresa (11 dígitos)"),
 *     @OA\Property(property="razon_social", type="string", example="Mi Empresa Actualizada S.A.", description="Razón social actualizada de la empresa"),
 *     @OA\Property(property="departamento", type="string", example="Lima", description="Departamento de la empresa"),
 *     @OA\Property(property="provincia", type="string", example="Lima", description="Provincia de la empresa"),
 *     @OA\Property(property="distrito", type="string", example="San Isidro", description="Distrito de la empresa"),
 *     @OA\Property(property="direccion", type="string", example="Av. Nueva 456", description="Dirección actualizada de la empresa")
 * )
 */
class CompanyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/companies",
     *     tags={"Companies"},
     *     summary="Get list of companies",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of companies per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Company"))
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/companies",
     *     tags={"Companies"},
     *     summary="Create a new company",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCompanyRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Company created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Company")
     *         )
     *     )
     * )
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = Company::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Get a company by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the company",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Company")
     *         )
     *     )
     * )
     */
    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Update a company",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the company",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCompanyRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Company")
     *         )
     *     )
     * )
     */
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $company->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Delete a company",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the company",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted',
        ]);
    }
}
