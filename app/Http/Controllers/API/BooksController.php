<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**

 *
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints for Books"
 * )
 *
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="person_type", type="string", example="individual"),
 *     @OA\Property(property="document_type", type="string", example="DNI"),
 *     @OA\Property(property="document_number", type="string", example="12345678"),
 *     @OA\Property(property="first_name", type="string", example="Juan"),
 *     @OA\Property(property="last_name", type="string", example="Sánchez"),
 *     @OA\Property(property="email", type="string", example="juan@example.com"),
 *     @OA\Property(property="phone", type="string", example="987654321"),
 *     @OA\Property(property="country", type="string", example="PER"),
 *     @OA\Property(property="item_type", type="string", example="product"),
 *     @OA\Property(property="item_description", type="string", example="Producto de prueba"),
 *     @OA\Property(property="has_payment_proof", type="boolean", example=true),
 *     @OA\Property(property="claims_amount", type="boolean", example=false),
 *     @OA\Property(property="claimed_amount", type="number", example=100),
 *     @OA\Property(property="claim_type", type="string", example="complaint"),
 *     @OA\Property(property="claim_description", type="string", example="Descripción del reclamo"),
 *     @OA\Property(property="request", type="string", example="Solicito respuesta"),
 *     @OA\Property(property="claim_date", type="string", format="date"),
 *     @OA\Property(property="preferred_contact_method", type="string", example="email"),
 *     @OA\Property(property="data_processing_consent", type="boolean", example=true),
 *     @OA\Property(property="signature", type="string", example="Firma digital"),
 *     @OA\Property(property="form_id", type="string", example="123abc"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class BooksController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Get list of books",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $books = Books::all();

        return response()->json([
            'success' => true,
            'data' => $books,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'person_type' => 'required|in:individual,legal_entity',
            'document_type' => 'required|string|max:20',
            'document_number' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_minor' => 'boolean',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'required|string|size:3',
            'item_type' => 'required|in:product,service',
            'item_description' => 'required|string',
            'has_payment_proof' => 'boolean',
            'attached_files.*' => 'nullable|url',
            'claims_amount' => 'boolean',
            'claimed_amount' => 'nullable|numeric|min:0',
            'claim_type' => 'required|in:complaint,grievance',
            'claim_description' => 'required|string',
            'request' => 'required|string',
            'claim_date' => 'nullable|date',
            'preferred_contact_method' => 'required|string|max:50',
            'data_processing_consent' => 'boolean',
            'signature' => 'nullable|string',
            'form_id' => 'nullable|string',
        ]);

        $book = Books::create($validatedData);

        return response()->json([
            'success' => true,
            'data' => $book
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update a book",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Book")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Books $book): JsonResponse
    {
        $validatedData = $request->validate([
            'person_type' => 'sometimes|in:individual,legal_entity',
            'document_type' => 'sometimes|string|max:20',
            'document_number' => 'sometimes|string|max:20',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'is_minor' => 'sometimes|boolean',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'sometimes|string|size:3',
            'item_type' => 'sometimes|in:product,service',
            'item_description' => 'sometimes|string',
            'has_payment_proof' => 'sometimes|boolean',
            'attached_files.*' => 'nullable|url',
            'claims_amount' => 'sometimes|boolean',
            'claimed_amount' => 'nullable|numeric|min:0',
            'claim_type' => 'sometimes|in:complaint,grievance',
            'claim_description' => 'sometimes|string',
            'request' => 'sometimes|string',
            'claim_date' => 'nullable|date',
            'preferred_contact_method' => 'sometimes|string|max:50',
            'data_processing_consent' => 'sometimes|boolean',
            'signature' => 'nullable|string',
            'form_id' => 'nullable|string',
        ]);

        $book->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Books $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted'
        ], 200);
    }
}
