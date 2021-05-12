<?php
return array(
    'WorkApplications' => [
        'STATUS_APPLY' => 1,
        'STATUS_ASSIGNED' => 2,
        'STATUS_CANCEL' => 3,
        'STATUS_FINISHED' => 4,
        'STATUS_LIST' => [
            'status_apply' => 1,
            'status_assigned' => 2,
            'status_cancel' => 3,
            'status_finished' => 4,
        ],
        'CONFIRM_STATUS' => [
            'NO' => 'n',
            'YES' => 'y'
        ]
    ],
    'WorkRecords' => [
        'TRANSFER_REQUEST_STATUS' => [
            'NO' => 1,
            'YES' => 2
        ],
        'FIXED_YN' => [
            'NO' => 'n',
            'YES' => 'y'
        ],
    ],

    'CHECKIN_BEFORE_MINUTE' => 30,
    'ModifyRequests' => [
        'APPROVAL_STATUS_UNAPPROVED' => 1,
        'APPROVAL_STATUS_APPROVED' => 2,
        'APPROVAL_STATUS_REJECTED' => 3,
        'APPROVAL_STATUS_LIST' => [
            1 => '施設承認待ち',
            2 => '施設承認済み',
            3 => '施設承認拒否',
        ],
    ],
    'Companies' => [
        'MF_APPROVE_STATUS_ENEXAMINED' => 1,
        'MF_APPROVE_STATUS_PASSED' => 2,
        'MF_APPROVE_STATUS_REJECTED' => 3,
        'MF_APPROVE_STATUS_LIST' => [
            1 => '審査中',
            2 => '審査通過',
            3 => '審査否決',
        ],
    ],
    'WorkerQualification' => [
        'REFUSAL_REASON_IMAGE' => 1,
        'REFUSAL_REASON_EXPIRED_DATE' => 2,
        'REFUSAL_REASON_DIFFERENT' => 3,
        'REFUSAL_REASON_IDENTIFY' => 4,
        'REFUSAL_REASON_OTHER' => 5,
        'REFUSAL_REASON_LIST' => [
            1 => '画像関する不備',
            2 => '有効期限切れ',
            3 => '違う資格書',
            4 => '本人情報と異なる',
            5 => 'その他',
        ],
        'APPROVAL_STATUS_LIST' => [
            1 => '未承認',
            2 => '承認済',
            3 => '承認拒否',
        ],
    ],
    'Homes' => [
        'TYPE_FUKUSI' => 1,
        'TYPE_HOKEN' => 2,
        'TYPE_RYOUYOU' => 3,
        'TYPE_ITAKU' => 4,
        'TYPE_DAYSERVICE' => 5,
        'TYPE_TEKIYOUGAI' => 6,
        'TYPE_LIST' => [
            1 => '介護老人福祉施設',
            2 => '介護老人保健施設',
            3 => '介護療養型医療施設',
            4 => '自治体委託事業者',
            5 => 'お泊りデイサービス',
            6 => '介護保険適用外サービス',
        ],
    ],
    'TransferRequest' => [
        'STATUS_LIST' => [
            1 => '振込予定',
            2 => '振込完了',
            3 => '振込エラー',
        ],
    ],
    'WorkerIdentification' => [
        'KIND' => [
            1 => 'パスポート',
            2 => '運転免許証',
            3 => '個人番号カード',
            4 => '住民基本台帳カード',
            5 => '住民票'
        ],
        'APPROVAL_STATUS_LIST' => [
            1 => '未承認',
            2 => '承認済',
            3 => '拒否',
        ],
    ],
    'front_header_title' => 'かいごのジーニー',
    'policy' => '*報酬の受け取り*
    報酬確定後、ウォレットに報酬が入ります。
    お好きなタイミングで振込申請ができ、ご登録頂いていた
    口座に振り込まれます。
    原則24時間即日で振り込まれますが、銀行の状況や
    登録された情報に誤りがある場合は、振り込まれない
    場合があります。
    振込手数料は一律無料です。

    *遅刻について*

    業務開始時刻にチェックインできなかった場合、即金での
    ウォレット振込ができません。遅刻の際は、業務時間の
    修正申請及び、業務先からの承認後ウォレットへ入金されます。
    余裕を持って現地に到着するようにしましょう。

    *その他*

    本手続きを完了することで、業務への申込みが完了します。
    なお、業務当日に業務先でQRコードを読み込むことで
    契約締結になりますので、それまでは、業務予定先から
    キャンセル通知が来る可能性がございます。',
    'commissoin_percent' => 15,
    'tax_rate' => 10,
    'overtime_percentage' => 25,
    'nighttime_percentage' => 25,
    'night_extra_percentage' => 25,
    'ovetime_extra_percentages' => 25,
    'folder_work_photo' => env('FOLDER_WORK_PHOTO') ?? 'work_photos',
    'folder_work_pdf' => env('FOLDER_WORK_PDF') ?? 'work_pdfs',
    'employments' => [
        1 => '雇用契約',
        2 => '業務委託契約',
    ],
    'invoice_status' => [
        1 => '未請求',
        2 => '請求済み',
        3 => '支払い済み',
        4 => 'キャンセル',
    ],
    'TIME_CHEKOUT' => 8,
    'folder_worker_qualification' => env('FOLDER_WORKER_QUALIFICATION') ?? 'worker_qualifications',
    'banks' => [
        1 => 'UFJ銀行',
        2 => 'みずほ銀行',
    ],
    'branches' => [
        1 => '○○支店',
        2 => 'さんかく支店',
    ],
    'BANK_KIND' => [
        1 => "普通",
        2 => "当座"
    ]

);
