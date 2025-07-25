<?php
namespace App\Helpers;


class PhoneHelper
{
    /**
     * Format phone number
     *
     * @param string $phone
     * @return string|\Propaganistas\LaravelPhone\PhoneNumber
     */
    static public function format(string $phone, array $country = [])
    {
        return phone($phone, !empty($country) ? $country : config("phone.countries", 'iq'));
    }
}
