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
            $today = new \DateTime();
            return (string)$today->diff($birthDate)->y;
        } catch (\Exception $e) {
            return '-';
        }
    }
}
