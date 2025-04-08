<?php

namespace App\Repositories;

use App\Constants\DatabaseTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceRepository
{
    /** Metoda pobiera dane faktur wraz z paginacją
     * @return array
     */
    public function getAll(): array
    {
        // ilość faktur na stronę ustawiona w custom config
        $perPage = config('pagination.invoices_per_page');

        $paginator = DB::table(DatabaseTables::INVOICES)
            ->paginate($perPage);

        $paginator->getCollection()->transform(function ($invoice) {
            // zamieniamy pełne ple tate-time na datę y-m-d
            if ($invoice->updated_at) {
                $invoice->updated_at = Carbon::parse($invoice->updated_at)->toDateString();
            }
            return $invoice;
        });

        return $paginator->toArray();
    }

    /** Pobieramy dane faktury $id
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return DB::table(DatabaseTables::INVOICES)
            ->where('id', $id)
            ->first()?->toArray() ?? null;
    }

    /** Metoda dodaje fakturę do systemu
     * @param array $data
     * @return bool
     */
    public static function create(array $data): bool
    {
        return DB::table(DatabaseTables::INVOICES)->insertGetId([
            'number' => $data['number'],
            'buyer_nip' => $data['buyer_nip'],
            'seller_nip' => $data['seller_nip'],
            'product_name' => $data['product_name'],
            'net_amount' => $data['net_amount'],
            'issue_date' => $data['issue_date'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** Metoda obsługuje aktualizację danych faktury
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $data['updated_at'] = now();
        DB::table(DatabaseTables::INVOICES)
            ->where('id', $id)
            ->update($data);
    }

    /** Metoda usuwa fakturę z systemu
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return DB::table(DatabaseTables::INVOICES)->where('id', $id)->delete() > 0;
    }
}
