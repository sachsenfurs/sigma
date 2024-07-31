<?php

namespace App\Helper\Chat;


class ChatHelper
{
    /**
     * @param string $response
     * @return bool|Collection
     */
    function validateDepartment($value)
    {
        $departments = [
            'artshow',
            'dealersden',
            'events',
        ];

        foreach ($departments as $department) {
            if (str_contains($value, $department)) {
                return true;
            }
        }
        return false;
    }
}