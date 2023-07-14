<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Transaction\Action\UpdateDonationAction;
use Ziswapp\Inertia\PageProps\Transaction\EditDonationProps;
use Ziswapp\Inertia\PageProps\Transaction\ShowDonationProps;
use Ziswapp\Domain\Transaction\Request\UpdateDonationRequest;

final class DonationController
{
    public function show(Donation $donation, ShowDonationProps $props): Response
    {
        $data = $props->loadData($donation);

        return Inertia::render('Inisiatif::donation/show', $data);
    }

    public function edit(Donation $donation, EditDonationProps $props): Response
    {
        \abort_if($donation->getAttribute('is_inisiatif_verified'), 404);

        $data = $props->loadData($donation);

        return Inertia::render('donation/edit', $data);
    }

    public function update(Donation $donation, UpdateDonationRequest $request): RedirectResponse
    {
        \abort_if($donation->getAttribute('is_inisiatif_verified'), 404);

        UpdateDonationAction::handleFromRequest($donation, $request);

        return new RedirectResponse('/donation/'.$donation->getKey());
    }
}
