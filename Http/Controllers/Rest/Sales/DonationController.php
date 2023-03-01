<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Ziswapp\Domain\Transaction\Model\Donation;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Inertia\PageProps\Transaction\ShowDonationProps;
use Ziswapp\Domain\Transaction\Repository\Contract\DonationRepository;

final class DonationController
{
    public function index(Request $request, DonationRepository $donationRepository): JsonResource
    {
        return JsonResource::collection(
            $donationRepository->filter($request)
        );
    }

    public function show(Donation $donation, ShowDonationProps $props): JsonResource
    {
        $data = $props->loadData($donation);

        return JsonResource::make($data);
    }
}
