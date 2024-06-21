<?php

namespace App\Helper\Telegram;
use App\Exceptions\Telegram\LoginWidget\HashValidationException;
use App\Exceptions\Telegram\LoginWidget\ResponseOutdatedException;
use App\Exceptions\Telegram\TelegramException;
use App\Settings\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class LoginWidget
{
    /**
     * @param $response
     * @return bool|Collection
     */
    public function validate($response)
    {
        try {
            return $this->validateWithError($response);
        } catch (TelegramException $exception) {
        }

        return false;
    }

    /**
     * @param $response
     * @return Collection
     *
     * @throws HashValidationException
     * @throws ResponseOutdatedException
     */
    public function validateWithError($response): Collection
    {
        $response = $this->convertResponseToCollection($response);

        $response = $this->checkAndGetResponseData($response);

        return $this->checkHash($response);
    }

    /**
     * @param  Collection  $collection
     * @return Collection
     */
    private function checkAndGetResponseData(Collection $collection): Collection
    {
        $requiredAttributes = ['id', 'first_name', 'last_name', 'username', 'photo_url', 'auth_date', 'hash'];

        return $collection->only($requiredAttributes);
    }

    /**
     * @param  Collection  $collection
     * @return Collection
     *
     * @throws HashValidationException
     * @throws ResponseOutdatedException
     */
    private function checkHash(Collection $collection): Collection
    {
        $secret_key = hash('sha256', app(AppSettings::class)->telegram_bot_token, true);

        $data = $collection->except('hash');

        $data_check_string = $data->map(function ($item, $key) {
            return $key.'='.$item;
        })
        ->values()
        ->sort()
        ->implode("\n");

        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        if (strcmp($hash, $collection->get('hash')) !== 0) {
            throw new HashValidationException;
        }

        if (time() - $collection->get('auth_date') > 86400) {
            throw new ResponseOutdatedException;
        }

        return $data;
    }

    /**
     * @param $response
     * @return Collection
     */
    private function convertResponseToCollection($response): Collection
    {
        if ($response instanceof Request) {
            return collect($response->all());
        }

        return Collection::wrap($response);
    }
}
