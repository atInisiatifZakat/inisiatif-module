<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Modules\Inisiatif\Integration\Confirmation\DataTransfers\NewConfirmationData;
use Modules\Inisiatif\Integration\Confirmation\DataTransfers\NewConfirmationOutput;

final class Confirmation
{
    private string $devApiUrl = 'https://donationapi.ondevizi.com/api/v3/donation/confirmation';

    private string $apiUrl = 'https://donationapi.inisiatif.id/api/v3/donation/confirmation';

    public function __construct(
        private readonly Credentials $credentials
    ) {
    }

    public function createConfirmation(NewConfirmationData $data): NewConfirmationOutput
    {
        $params = \array_merge($data->toArray(), [
            'payment_status' => 'PENDING',
        ]);

        Log::debug('Send request create confirmation', $params);
        $response = $this->getHttpClient()->post('/', $params);

        Log::debug('Response create confirmation', $response->json());
        return new NewConfirmationOutput(
            $response->json()
        );
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::asJson()->acceptJson()->withToken($this->credentials->token)->baseUrl(
            $this->credentials->isProduction ? $this->apiUrl : $this->devApiUrl
        );
    }
}
