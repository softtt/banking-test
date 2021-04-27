<?php


namespace App\Services\Api;


use App\Services\Api\Exceptions\InvalidSignatureException;
use Illuminate\Http\Request;

class RequestSignatureVerificationService
{
    public function verify(Request $request): void
    {
        $realKey = $this->generateRequestSignature();
        $signature = md5($request->get('signature'));

        if ($realKey !== $signature) {
            throw new InvalidSignatureException('Wrong Signature!');
        }
    }

    private function generateRequestSignature(): string
    {
        return md5($this->getServerKey());
    }

    private function getServerKey(): string
    {
        return config('api.server_key', 'ashgerwnsauipohedyionh[fijfweu3425sdfhdioust049oiegj');
    }
}
