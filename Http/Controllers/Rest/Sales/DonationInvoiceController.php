<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Response;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Transaction\Action\GenerateDonationInvoiceAction;

final class DonationInvoiceController
{
    public function show(Donation $donation, GenerateDonationInvoiceAction $invoiceAction): Response
    {
        return $invoiceAction->handle($donation)->download('KUITANSI - '.$donation->getAttribute('identification_number').'.pdf');
    }
}
