<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Models\BitrixToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{

    public function list(Request $request) {
            $token = BitrixToken::query()->first();

            // lấy dữ liệu contact
            $payload = [
                'auth' => $token->access_token,
                'select' =>['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PHOTO', 'WEB_SITE', 'PHONE'],
            ];
            // dd((ApiHelper::callApi('crm.contact.list',$payload)));
            $data_contact = ApiHelper::callApi('crm.contact.list',$payload)['result'];

            //lấy dữ liệu địa chỉ
            $payload_address = [
                'auth' => $token->access_token,
                'select' => ['TYPE_ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'ADDRESS_2'],
            ];
            $data_address = ApiHelper::callApi('crm.address.list',$payload_address)['result'];

            //lấy dữ liệu requisites
            $payload_requisite = [
                'auth' => $token->access_token,
                'select' => ['ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'PRESET_ID', 'NAME'],
            ];
            $data_requisite = ApiHelper::callApi('crm.requisite.list',$payload_requisite)['result'];

            //lấy dữ liệu ngân hàng
            $payload_bank = [
                'auth' => $token->access_token,
                'select' => ['ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'RQ_BANK_NAME', 'RQ_ACC_NAME', 'RQ_ACC_NUM'],
            ];
            $data_bank = ApiHelper::callApi('crm.requisite.bankdetail.list',$payload_bank)['result'];


            // kết hợp danh sách thông tin ngân hàng với requisites dựa trên ENTITY_ID của data ngân hàng
            $collect_requisite = collect($data_requisite)->map(function ($data_requisite) use ($data_bank) {

                $bank = collect($data_bank)->firstWhere('ENTITY_ID', $data_requisite['ID']);

                if ($bank) {
                    $data_requisite['BANK'] = $bank;
                }else {
                    $data_requisite['BANK'] = [];
                }

                return $data_requisite;
            });

            // kết hợp danh sách contact với danh sách địa chỉ, danh sách requisites dựa trên ENTITY_ID
            $data = collect($data_contact)->map(function ($data_contact) use ($data_address, $collect_requisite) {

                $address = collect($data_address)->firstWhere('ENTITY_ID', $data_contact['ID']);

                $requisite = $collect_requisite->firstWhere('ENTITY_ID', $data_contact['ID']);

                if ($address) {
                    $data_contact['ADDRESS'] = $address;
                }else {
                    $data_contact['ADDRESS'] = [];
                }

                if ($requisite) {
                    $data_contact['REQUISITE'] = $requisite;
                }else {
                    $data_contact['REQUISITE'] = [];
                }

                return $data_contact;
            });

            // dd($data);

        return view('list',compact('data'));
    }

    public function create() {
        return view('create');
    }

    public function add(Request $request) {
        $data = $request->all();
        $token = BitrixToken::query()->first();

        $payload = [
            'auth' => $token->access_token,
            'fields' => [
                'NAME' => $data['name'],
                'LAST_NAME' => $data['last_name'],
                'EMAIL' => [
                    ['VALUE' => $data['email'], 'VALUE_TYPE' => 'WORK']
                ],
                'PHONE' => [
                    ['VALUE' => $data['phone'], 'VALUE_TYPE' => 'WORK']
                ],
            ]
        ];

        $result_add_contact = ApiHelper::callApi('crm.contact.add',$payload);
        $contact_id = $result_add_contact['result'];

        //thêm địa chỉ
        $payload_address = [
            'auth' => $token->access_token,
            'fields' => [
                'TYPE_ID' => 11,
                'ENTITY_ID' => $contact_id,
                'ENTITY_TYPE_ID'=> 8,
                'ADDRESS_2' => $data['address'],
            ]
        ];
        $result_add_address = ApiHelper::callApi('crm.address.add',$payload_address);

        $payload_requisite = [
            'auth' => $token->access_token,
            'fields' => [
                'ENTITY_TYPE_ID' => 3,
                'ENTITY_ID' => $contact_id,
                'PRESET_ID' => 3,
                'NAME' => 'Person',
            ]
        ];
        $result_add_requisite = ApiHelper::callApi('crm.requisite.add', $payload_requisite);
        $requisite_id = $result_add_requisite['result'];

        //thêm ngân hàng
        $payload_bank = [
            'auth' => $token->access_token,
            'fields' => [
                'ENTITY_TYPE_ID' => 8,
                'ENTITY_ID' => $requisite_id,
                'NAME' => 'Bank Details',
                'RQ_BANK_NAME' => $data['bank_name'],
                'RQ_ACC_NAME' => $data['bank_owner'],
                'RQ_ACC_NUM' => $data['bank_acc_num'],
                'ACTIVE' => 'Y',
            ]
        ];
        $result_add_bank = ApiHelper::callApi('crm.requisite.bankdetail.add',$payload_bank);

        return redirect()->route('contact.list')->with('success', 'Thêm mới liên hệ thành công');
    }

    public function edit($contact_id, $requisite_id, $bank_id) {
        // dd($contact_id, $requisite_id, $bank_id);

        $token = BitrixToken::query()->first();

        // lấy dữ liệu contact
        $payload = [
            'auth' => $token->access_token,
            'id' => $contact_id,
            'select' =>['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PHOTO', 'WEB_SITE', 'PHONE'],
        ];
        $data_contact = ApiHelper::callApi('crm.contact.get',$payload)['result'];

        //lấy dữ liệu địa chỉ
        $payload_address = [
            'auth' => $token->access_token,
            'select' => ['TYPE_ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'ADDRESS_2'],
            'filter' => ['ENTITY_ID' => $contact_id],
        ];
        $data_address = ApiHelper::callApi('crm.address.list',$payload_address)['result'][0];

        //lấy dữ liệu requisites
        $payload_requisite = [
            'auth' => $token->access_token,
            'id' => $requisite_id,
            'select' => ['ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'PRESET_ID', 'NAME'],
        ];
        $data_requisite = ApiHelper::callApi('crm.requisite.get',$payload_requisite)['result'];

        //lấy dữ liệu ngân hàng
        $payload_bank = [
            'auth' => $token->access_token,
            'select' => ['ID', 'ENTITY_ID', 'ENTITY_TYPE_ID', 'RQ_BANK_NAME', 'RQ_ACC_NAME', 'RQ_ACC_NUM'],
            'id' => $bank_id,
        ];
        $data_bank = ApiHelper::callApi('crm.requisite.bankdetail.get',$payload_bank)['result'];

        $data = $data_contact;
        $data['ADDRESS'] = $data_address;
        $data_requisite['BANK'] = $data_bank;
        $data['REQUISITE'] = $data_requisite;

        return view('edit', compact('data','contact_id','requisite_id','bank_id'));
    }

    public function update(Request $request) {
        try {
            $data = $request->all();
            $token = BitrixToken::query()->first();

            $payload = [
                'auth' => $token->access_token,
                'id' => $data['contact_id'],
                'fields' => [
                    'NAME' => $data['name'],
                    'LAST_NAME' => $data['last_name'],
                    'EMAIL' => [
                        [
                            'ID' => $data['email_id'],
                            'VALUE' => $data['email'],
                            'VALUE_TYPE' => 'WORK'
                        ]
                    ],
                    'PHONE' => [
                        [
                            'ID' => $data['phone_id'],
                            'VALUE' => $data['phone'],
                            'VALUE_TYPE' => 'WORK'
                        ]
                    ],
                ]
            ];

            $update_contact = ApiHelper::callApi('crm.contact.update',$payload);

            //cập nhật địa chỉ
            $payload_address = [
                'auth' => $token->access_token,
                'fields' => [
                    'TYPE_ID' => 11,
                    'ENTITY_ID' => $data['contact_id'],
                    'ENTITY_TYPE_ID'=> 8,
                    'ADDRESS_2' => $data['address'],
                ]
            ];
            $update_address = ApiHelper::callApi('crm.address.update',$payload_address);

            //cập nhật ngân hàng
            $payload_bank = [
                'auth' => $token->access_token,
                'id' => $data['bank_id'],
                'fields' => [
                    'ENTITY_ID' => $data['requisite_id'],
                    'RQ_BANK_NAME' => $data['bank_name'],
                    'RQ_ACC_NAME' => $data['bank_owner'],
                    'RQ_ACC_NUM' => $data['bank_acc_num'],
                    'ACTIVE' => 'Y',
                ]
            ];
            $update_bank = ApiHelper::callApi('crm.requisite.bankdetail.update',$payload_bank);

            return redirect()->route('contact.list')->with('success', 'Cập nhật liên hệ thành công');
        } catch (\Exception $e) {
            return redirect()->route('contact.list')->with('error', 'Cập nhật liên hệ không thành công, Lỗi: '.$e->getMessage());
        }
    }

    public function delete(Request $request) {
        try {
            $data = $request->all();
            $token = BitrixToken::query()->first();

            $payload = [
                'auth' => $token->access_token,
                'id' => $data['contact_id'],
            ];

            $delete_contact = ApiHelper::callApi('crm.contact.delete',$payload);

            //xóa địa chỉ
            $payload_address = [
                'auth' => $token->access_token,
                'fields' => [
                    "TYPE_ID" => 11,
                    "ENTITY_ID" => $data['contact_id'],
                    "ENTITY_TYPE_ID" => 8,
                ]
            ];
            $delete_address = ApiHelper::callApi('crm.address.delete',$payload_address);

            //xóa requisites
            if ($data['requisite_id']) {
                $payload_requisite = [
                    'auth' => $token->access_token,
                    'id' => $data['requisite_id'],
                ];
                $delete_requisite = ApiHelper::callApi('crm.requisite.delete',$payload_requisite);
            }

            //xóa ngân hàng
            if ($data['bank_id']) {
                $payload_bank = [
                    'auth' => $token->access_token,
                    'id' => $data['bank_id'],
                ];
                $delete_bank = ApiHelper::callApi('crm.requisite.bankdetail.delete',$payload_bank);
            }

            return response()->json(['status' => true, 'message' => 'Xoá thành công liên hệ'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Xoá không thành công, Lỗi: '.$e->getMessage()], 200);
        }
    }
}
