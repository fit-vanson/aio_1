<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use Illuminate\Support\Facades\Auth;

class UsersACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return Auth::id();

    }

    /**
     * Get ACL rules list for user
     *
     * @return array
     */
    public function getRules(): array
    {
        if (Auth::id() === 8) {
            return [
                ['disk' => 'local', 'path' => '*', 'access' => 2],                                  // main folder - read
                ['disk' => 'File Manager', 'path' => '*', 'access' => 2],                                  // main folder - read
                ['disk' => 'KeyStore', 'path' => '*', 'access' => 2],                                // main folder - read
                ['disk' => 'Profile', 'path' => '*', 'access' => 2],                                // main folder - read
                ['disk' => 'FTP-'.Auth::user()->name, 'path' => '*', 'access' => 2],                                // main folder - read
            ];
        }
        return [
            ['disk' => Auth::user()->name, 'path' => '*', 'access' => 2]
        ];
    }
}
