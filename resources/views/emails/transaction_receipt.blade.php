@extends('emails.email_template')
@section('content')
    <p> Hi {{ $trans->fullname }}!</p>
    <p>Here is your transaction receipt.</p>
    <h1 class="center">NGN {{ number_format($trans->amount, 2, '.', ',') }}</h1>
    <div class="grey-background single-line"></div>
    <h3 class="center">PAYMENT DETAILS</h3>
    <div class="grey-background container">
        <table width="100%">
            <tr>
                <td>Reference</td>
                <td>{{ $trans->ref }}</td>
            </tr>
            <tr>
                <td>Transaction Status</td>
                <td>{{ \App\Model\RequestStatus::getReqTitle($trans->approvalStatus) }}</td>
            </tr>
            <tr>
                <td>Transaction Date</td>
                <td>{{ explode(' ', $trans->transDate)[0] }}</td>
            </tr>
            <tr>
                <td>Transaction Time</td>
                <td>{{ explode(' ', $trans->transDate)[1] }}</td>
            </tr>
            <tr>
                <td>Service</td>
                <td>{{ $trans->product_name }}</td>
            </tr>
            <tr>
                <td>Product Name</td>
                <td>{{ $trans->sub_name }}</td>
            </tr>
            @if($trans->dr_acc != '')
                <tr>
                    <td>Sender</td>
                    <td>{{ $trans->dr_acc }}</td>
                </tr>
            @endif
            @if($trans->cr_acc != '')
                <tr>
                    <td>Receiver/Beneficiary</td>
                    <td>{{ $trans->cr_acc }}</td>
                </tr>
            @endif
            @if($trans->acc_no != '')
                <tr>
                    <td>Payment Account</td>
                    <td>{{ $trans->acc_no }}</td>
                </tr>
            @endif
            @if($trans->cardPin != '')
                <tr>
                    <td>Card PIN</td>
                    <td>{{ $trans->cardPin }}</td>
                </tr>
            @endif

            <tr>
                <td>Payment Channel</td>
                <td>{{ $trans->channel_name }}</td>
            </tr>
            <tr>
                <td>Transaction Amount</td>
                <td>NGN {{  number_format($trans->amount, 2, '.', ',') }}</td>
            </tr>
        </table>
    </div>
    <p class="center container">Thanks for your patronage!</p>
    <div class="grey-background single-line"></div>
    <p class="center container">Have questions or need help? Kindly respond this email or visit our <a href="https://airtimedatahub.com/faqs/" class="no-decoration">FAQ</a> page</p>
@endsection
