<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;

readonly class InvoiceService
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    )
    {}

    /** Metoda pobiera listę faktur
     * @return array
     */
    public function getInvoices(): array
    {
        return $this->invoiceRepository->getAll();
    }

    /** Metoda pobiera dane faktury $id
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        return $this->invoiceRepository->findById($id);
    }

    /** Metoda odpowiada za zapis utworzonej faktury do bazy danych
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        return $this->invoiceRepository->create($data);
    }

    /** Metoda odpowiada za aktualizację danych faktury w bazie danych
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $this->invoiceRepository->update($id, $data);
    }

    /** Metoda usuwa fakturę z bazy danych
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->invoiceRepository->delete($id);
    }
}
