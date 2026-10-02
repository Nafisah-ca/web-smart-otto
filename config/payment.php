<?php

return [
    'merchant_name' => 'WARUNG APDAL / Smart Otto',
    'qris' => [
        'name'          => 'QRIS (Semua E-Wallet & M-Banking)',
        'merchant'      => 'WARUNG APDAL',
        'nmid'          => 'ID1026603303631',
        'terminal'      => 'A01',
        'image'         => 'images/qris.jpg',
        'description'   => 'Scan menggunakan GoPay, OVO, DANA, ShopeePay, BCA, Livin Mandiri, BRImo, BNI Mobile, atau aplikasi QRIS lainnya.',
    ],
    'bank_transfers' => [
        [
            'code'          => 'bca',
            'bank_name'     => 'Bank BCA',
            'account_number'=> '8830192837',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
            'badge'         => 'BCA',
            'color'         => '#003882',
        ],
        [
            'code'          => 'mandiri',
            'bank_name'     => 'Bank Mandiri',
            'account_number'=> '1370019283741',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
            'badge'         => 'MANDIRI',
            'color'         => '#003066',
        ],
        [
            'code'          => 'bri',
            'bank_name'     => 'Bank BRI',
            'account_number'=> '012301001234530',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
            'badge'         => 'BRI',
            'color'         => '#00529C',
        ],
        [
            'code'          => 'bni',
            'bank_name'     => 'Bank BNI',
            'account_number'=> '0987654321',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
            'badge'         => 'BNI',
            'color'         => '#F15A24',
        ],
    ],
    'ewallets' => [
        [
            'code'          => 'gopay',
            'name'          => 'GoPay',
            'phone'         => '081298765432',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
        ],
        [
            'code'          => 'ovo',
            'name'          => 'OVO',
            'phone'         => '081298765432',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
        ],
        [
            'code'          => 'dana',
            'name'          => 'DANA',
            'phone'         => '081298765432',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
        ],
        [
            'code'          => 'shopeepay',
            'name'          => 'ShopeePay',
            'phone'         => '081298765432',
            'account_name'  => 'WARUNG APDAL / SMART OTTO',
        ],
    ],
    'cash' => [
        'name'        => 'Tunai / Cash di Bengkel',
        'description' => 'Bayar langsung di kasir atau kepada inspektor kami saat kunjungan inspeksi.',
    ],
    'card' => [
        'name'        => 'Kartu Debit / Kredit (EDC)',
        'description' => 'Tersedia mesin EDC (Visa, Mastercard, GPN, JCB) di lokasi bengkel.',
    ],
];
