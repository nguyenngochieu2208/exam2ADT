@forelse($data as $key => $item)
    <tr id="record_{{ $item['ID'] }}">
        <td class="align-middle">
            <img src="{{ asset('images/user.jpg') }}" width="75px" class="img-thumbnail rounded">
        </td>

        <td class="align-middle">
            {{ @$item['NAME'] }} {{ @$item['LAST_NAME'] }}
        </td>

        <td class="align-middle">
            {{ @$item['ADDRESS']['ADDRESS_2'] }}
        </td>

        <td class="align-middle">
            {{ @$item['PHONE'][0]['VALUE'] }} ({{ @$item['PHONE'][0]['VALUE_TYPE'] }})
        </td>

        <td class="align-middle">
            {{ @$item['EMAIL'][0]['VALUE'] }} ({{ @$item['EMAIL'][0]['VALUE_TYPE'] }})
        </td>

        <td class="align-middle">
            <p class="my-1">{{ @$item['REQUISITE']['BANK']['RQ_BANK_NAME'] }}</p>
            <p class="my-1">{{ @$item['REQUISITE']['BANK']['RQ_ACC_NAME'] }}</p>
            <p class="my-1">{{ @$item['REQUISITE']['BANK']['RQ_ACC_NUM'] }}</p>
        </td>

        <td class="align-middle">
            <a type="button" href="{{route('contact.edit',['contact_id' => $item['ID'] , 'requisite_id' => @$item['ADDRESS']['TYPE_ID'], 'bank_id' => @$item['REQUISITE']['BANK']['ID'] ])}}" class="btn btn-warning" >
                <i class="bi bi-pencil"></i>
            </a>

            <a type="button" class="btn-del btn btn-danger" data-contact_id="{{ $item['ID'] }}" data-address_id="{{ @$item['ADDRESS']['TYPE_ID'] }}" data-requisite_id="{{@$item['REQUISITE']['ID']}}" data-bank_id="{{@$item['REQUISITE']['BANK']['ID']}}" data-url="{{route('contact.delete')}}">
                <i class="bi bi-trash"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-danger text-center align-middle">Không có dữ liệu</td>
    </tr>
@endforelse
