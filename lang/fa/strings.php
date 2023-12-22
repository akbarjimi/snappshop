<?php

return [
    'transfer_failed' => [
        'insufficient_funds' => [
            'message' => 'موجودی ناکافی',
            'details' => 'حساب موجودی کافی برای انتقال درخواستی ندارد.',
        ]
    ],
    'transfer_succeed' => [
        'message' => 'انتقال وجه با موفقیت انجام شد.',
    ],
    'transactions' => [
        'fee' => [
            'message' => 'کارمزد انتقال کارت به کارت',
        ],
        'notifications' => [
            'debit' => ":name عزیز، مبلغ :amount ریال از کارت :card برداشت شد.",
            'credit' => ":name عزیز، مبلغ :amount ریال به کارت :card واریز شد.",
            'fee' => ":name عزیز، مبلغ :amount ریال از حساب شماره :account کارمزد برداشت شد.",
        ]
    ],
];
