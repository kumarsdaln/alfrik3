<?php

namespace App\Services;

use App\Exceptions\GoogleDriveNotConnectedException;
use App\Models\GoogleAccount;
use App\Models\User;
use Google\Client;
use Illuminate\Support\Facades\Auth;

class GoogleClientFactory
{
    public static function calendar(User $user): Client
    {
        return self::make('calendar', $user);
    }

    public static function drive(User $user): Client
    {
        return self::make('drive', $user);
    }

    private static function make(string $type, User $user): Client
    {
        $google = GoogleAccount::where('user_id', $user->id)->first();

        if (!$google || !$google->{$type . '_access_token'}) {
            throw new GoogleDriveNotConnectedException();
        }

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');

        $client->setAccessToken(
            decrypt($google->{$type . '_access_token'})
        );

        $expiresAtColumn = $type . '_token_expires_at';

        // Token expired?
        if (
            !$google->{$expiresAtColumn} ||
            now()->greaterThan($google->{$expiresAtColumn})
        ) {
            if (!$google->{$type . '_refresh_token'}) {
                throw new GoogleDriveNotConnectedException();
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken(
                decrypt($google->{$type . '_refresh_token'})
            );

            if (isset($newToken['error'])) {
                throw new GoogleDriveNotConnectedException();
            }

            $google->update([
                "{$type}_access_token" => encrypt($newToken['access_token']),
                "{$expiresAtColumn}" => now()->addSeconds($newToken['expires_in']),
            ]);

            $client->setAccessToken($newToken);
        }

        return $client;
    }
}
