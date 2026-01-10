<?php
/**
 * Setting app
 */
return [
    "app_logo" => "<b>HS CMS</b>",
    "app_logo_mini" => "<b>HS</b>",

	"avatar_default" => '/images/avatar.png',

    "active" => 1,
    "inactive" => 0,

    "public_avatar" => '/images/avatar/',

    "perpage" => 20,

    'paginate' => [
        'page5' => 5,
        'page6' => 6,
        'page8' => 8,
        'page12' => 12,
    ],

	"log_active" => true,
    "format" => [
	    "datetime" => "d/m/Y H:i",
	    "date" => "d/m/Y",
	    "date_js" => 'dd/mm/yyyy',
    ],
    "approved" => [
	    'cancel' => -10,//hủy vé
	    'tmp' => 5,//lưu tạm
	    'save' => 10//lưu
    ],
    "bank_type" => [
        "1" => "Bank",
        "2" => "Credit",
    ],
	//set roles list
	"roles" => [
		'company_admin' => 'company_admin',
		'company_employee' => 'company_employee',
		'company_booking' => 'company_booking',
		'agent_admin' => 'agent_admin',
		'agent_employee' => 'agent_employee',
        'customer' => 'customer'
	],

    'settings_site' => [
        [
            "key" => "company_logo",
            "value" => "",
            "description" => "Đường dẫn lưu logo",
            "type"=>"image",
            "group_data"=>"company_info", // Thông tin công ty
        ],
        [
            "key" => "company_website",
            "value" => "",
            "description" =>"Tên website",
            "type"=>"text",
            "group_data"=>"company_info",// Thông tin công ty
        ],
        [
            "key" => "company_address",
            "value" => "",
            "description" =>"Địa chỉ",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_phone",
            "value" => "",
            "description" =>"Số điện thoại",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_hotline",
            "value" => "",
            "description" =>"Hotline",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_email",
            "value" => "",
            "description" =>"Email",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],
        [
            "key" => "company_link",
            "value" => '',
            "description" =>"Website",
            "group_data"=>"company_info",// Thông tin công ty
            "type"=>"text"
        ],

        [
            "key" => "follow_facebook",
            "value" => "https://www.facebook.com/",
            "description" =>"Địa chỉ facebook",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_twitter",
            "value" => "https://www.twitter.com/",
            "description" =>"Địa chỉ twitter",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_instagram",
            "value" => "https://www.instagram.com/",
            "description" =>"Địa chỉ instagram",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
        [
            "key" => "follow_youtube",
            "value" => "https://www.youtube.com/",
            "description" =>"Địa chỉ youtube",
            "group_data"=>"social_info",// Mạng xã hội
            "type"=>"text"
        ],
          [
            "key" => "google_analytics",
            "value" => "",
            "description" =>"Mã nhúng google analytics",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
           [
            "key" => "google_mgi",
            "value" => "",
            "description" =>"Mã nhúng MGI",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ]
        ,
          [
            "key" => "google_mgi_title",
            "value" => "",
            "description" =>"Mã nhúng MG2 TITLE",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
           [
            "key" => "google_mgi_body",
            "value" => "",
            "description" =>"Mã nhúng MG2 BODY",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
            [
            "key" => "google_mgi_body_2",
            "value" => "",
            "description" =>"Mã nhúng MG2 BODY 2",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
         [
            "key" => "google_mgi_news",
            "value" => "",
            "description" =>"Mã nhúng MG2 UPDATE",
            "group_data"=>"iframe_info",// Mã nhúng
            "type"=>"textarea"
        ],
        [
            "key" => "meta_title",
            "value" => "",
            "description" =>"SEO tiêu đề web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"text"
        ],
        [
            "key" => "meta_keyword",
            "value" => "",
            "description" =>"SEO từ khóa web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ],
        [
            "key" => "meta_description",
            "value" => "",
            "description" =>"SEO mô tả web",
            "group_data"=>"system_info",// Cấu hình SEO
            "type"=>"textarea"
        ]
    ],
]
?>