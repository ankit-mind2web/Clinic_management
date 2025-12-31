<?php

class Datehelper
{
    public static function calculateAge(?string $dob): string
    {
        if (!$dob) {
            return '-';
        }

        try {
            $birthDate = new \DateTime($dob);
            $today     = new \DateTime();

            $diff = $today->diff($birthDate);

            return $diff->y . ' years, ' . $diff->m . ' months, ' . $diff->d . ' days';
        } catch (\Exception $e) {
            return '-';
        }
    }
}
