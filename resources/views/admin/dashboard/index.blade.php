@extends('layouts.app')

@section('content')
<div class="p-4">
    <h4 class="mb-3">
        WELCOME TO UTE LOCKER
    </h4>

    <p>
        This is the graduation thesis of students of Ho Chi Minh City University of Technology and Education
    </p>

    <p>
        Name of the project: <strong>Design and manufacture of smart locker system</strong>
    </p>

    <p>
        The project is carried out by a group of 3 students:
    </p>

    <ol style="list-style-type: decimal !important;">
        <li>
            1. Nguyen Trong Dai
        </li>
        <li>
            2. Le Phan Van Viet
        </li>
        <li>
            3. Tran Trieu Vi
        </li>
    </ol>

    <p>
        Instructor: <strong>Mr. Huynh Quang Duy</strong>
    </p>

    <p>
        Year of implementation: <strong>2023-2024</strong>
    </p>

    <p>
        Some notes when using the system:
    </p>

    <ol>
        <li>
            Currently, because it is a graduation thesis, the group can only use the sandbox of electronic wallets
            so to be able to deposit money into your account you must use the account provided below
            <p class="m-0">- e-Wallet: <strong>VNPAY</strong></p>
            <p class="m-0">- Bank: <strong>NCB</strong></p>
            <p class="m-0">- Account number: <strong>9704198526191432198</strong></p>
            <p class="m-0">- Account name: <strong>NGUYEN VAN A</strong></p>
            <p class="m-0">- Issuance date: <strong>07/15</strong></p>
            <p class="m-0">- OTP: <strong>123456</strong></p>

        </li>
        <li>
            If you use the Gen User or Super Admin account, to bypass the OTP authentication step, you can use
            the default OTP code is <strong>123456</strong>
        </li>
    </ol>
</div>
@endsection
