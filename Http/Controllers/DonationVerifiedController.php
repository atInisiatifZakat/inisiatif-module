<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Job\SendDonationVerifiedNotification;
use Ziswapp\Domain\Transaction\Action\DonationVerifiedAction;
use Ziswapp\Inertia\PageProps\Transaction\VerifiedDonationProps;

final class DonationVerifiedController extends Controller
{
    public function show(Donation $donation, VerifiedDonationProps $props): Response
    {
        \abort_if($donation->getAttribute('is_inisiatif_verified'), 404);

        $data = $props->loadData($donation);

        return Inertia::render('donation/verified', $data);
    }

    public function store(Donation $donation, Request $request): RedirectResponse
    {
        \abort_if($donation->getAttribute('is_inisiatif_verified'), 404);

        if ($donation->isNew() === false) {
            throw ValidationException::withMessages([
                'status' => [
                    'Invalid donation status',
                ],
            ]);
        }

        $newDonation = DonationVerifiedAction::handleFromRequest($donation, $request);

        SendDonationVerifiedNotification::dispatch($newDonation);

        return new RedirectResponse('/donation/verify');
    }
}
