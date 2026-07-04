<?php

if (!function_exists('hitung_ppn')) {

    function hitung_ppn($total_harga)
    {
        return $total_harga * 0.11;
    }
}

if (!function_exists('hitung_biaya_admin')) {

    function hitung_biaya_admin($total_harga)
    {
        if ($total_harga <= 20000000) {
            $persen = 0.006;
        } elseif ($total_harga <= 40000000) {
            $persen = 0.008;
        } else {
            $persen = 0.01;
        }

        return $total_harga * $persen;
    }
}

if (!function_exists('hitung_diskon_voucher')) {

    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $voucher = strtoupper(trim($voucher_code));

        switch ($voucher) {
            case 'FLASH10':
                $diskon = 10;
                break;

            case 'FLASH15':
                $diskon = 15;
                break;

            case 'MEMBER20':
                $diskon = 20;
                break;

            default:
                $diskon = 0;
        }

        return [
            'voucher_code'   => $voucher,
            'persentase'     => $diskon,
            'diskon_voucher' => $total_harga * ($diskon / 100)
        ];
    }
}