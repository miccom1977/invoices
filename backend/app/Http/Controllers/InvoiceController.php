<?php

namespace App\Http\Controllers;

use App\Rules\ValidNip;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService) {}

    /** Metoda pobiera listę faktur wraz z paginacją
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->invoiceService->getInvoices());
    }

    /** Metoda prezentuje dane jednej faktury
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $invoice = $this->invoiceService->getById($id);

        if (!$invoice) {
            return response()->json(['message' => 'Faktura nie znaleziona'], 404);
        }

        return response()->json($invoice);
    }

    /** Metoda odpowiada za dodanie faktury
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:50',
            'buyer_nip' => ['required', new ValidNip()],
            'seller_nip' => ['required', new ValidNip()],
            'product_name' => 'required|string|max:255',
            'net_amount' => 'required|numeric',
            'issue_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Błędne dane',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($this->invoiceService->store($request->only(
            'number',
            'buyer_nip',
            'seller_nip',
            'product_name',
            'net_amount',
            'issue_date'
        ))) {
            return response()->json(['message' => 'Faktura została dodana.'], 201);
        }

        return response()->json(['error' => 'Nie udało się dodać faktury.'], 500);
    }

    /** Metoda przeprowadza aktualizację danych faktury
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|string|max:255',
            'buyer_nip' => ['required', new ValidNip()],
            'seller_nip' => ['required', new ValidNip()],
            'product_name' => 'required|string|max:255',
            'net_amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Błędne dane',
                'errors' => $validator->errors()
            ], 422);
        }

        $this->invoiceService->update($id, $request->only(
            'number',
            'buyer_nip',
            'seller_nip',
            'product_name',
            'net_amount',
            'issue_date'
        ));

        return response()->json(['message' => 'Faktura zaktualizowana']);
    }

    /** Metoda usuwa fakturę z systemu
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->invoiceService->delete($id);

        if ($deleted) {
            return response()->json(['message' => 'Faktura została usunięta']);
        }

        return response()->json(['error' => 'Nie znaleziono faktury'], 404);
    }
}
