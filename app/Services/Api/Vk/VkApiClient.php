<?php


namespace App\Services\Api\Vk;


class VkApiClient
{
    const BASE_URL = 'https://api.vk.com/method/';
    const API_VERSION = '5.130';

    private $accessToken;
    private $secretKey;
    private $serviceAccessKey;
    private $appId;
    private $groupId;

    public function __construct()
    {
        $this->secretKey = env('VK_SECRET_KEY');
        $this->serviceAccessKey = env('VK_SERVICE_ACCESS_KEY');
        $this->appId = env('VK_APP_ID');
        $this->groupId = env('VK_GROUP_ID');
        $this->accessToken = env('VK_ACCESS_TOKEN');
    }

    public function sendPostToPublicPage(string $message): string
    {
        /*
         * Метод можно вызвать с ключом доступа пользователя,
         * полученным в Standalone-приложении через Implicit Flow,
         * или с использованием окна подтверждения. Далее там вот так написано:
         * Используйте Implicit Flow для вызова методов API ВКонтакте непосредственно с устройства пользователя
         * (например, из Javascript).
         *
         * Ключ доступа, полученный таким способом, не может быть использован для запросов с сервера.
         */

        $methodName = 'wall.post';

        $parameters = array(
            'owner_id' => $this->groupId,
            'from_group' => true,
            'message' => $message,
            'mute_notifications' => true,
            'access_token' => $this->accessToken,
            'v' => self::API_VERSION
        );

        return $this->sendRequest('POST', $methodName, $parameters);
    }

    public function getWall(): string
    {
        $methodName = 'wall.get';

        $result = $this->sendRequest('GET', $methodName, array(
            'count' => 10,
            'owner_id' => $this->groupId,
            'access_token' => $this->accessToken,
            'v' => self::API_VERSION
        ));

        return $result;
    }

    private function sendRequest(string $requestMethod = 'GET', string $libraryMethod, array $data = array()): string
    {
        $requestUrl = self::BASE_URL . $libraryMethod;
        $curlSettings = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestMethod,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        );

        if($requestMethod === 'GET') {
            $requestUrl .= '?' . http_build_query($data);
        } else {
            $curlSettings[CURLOPT_POSTFIELDS] = json_encode($data);

        }

        $curlSettings[CURLOPT_URL] = $requestUrl;
        $curl = curl_init();
        curl_setopt_array($curl, $curlSettings);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
