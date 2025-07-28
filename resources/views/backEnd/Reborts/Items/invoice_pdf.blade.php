<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>فاتورة رقم {{ $order->auto_serial }}</title>
    <style>
       body {
      font-size: 0.875rem;
        font-family: 'cairo';
        font-weight: normal;
        direction: rtl;
        text-align: right;
    }

        .header,
        .footer {
            text-align: center;
        }

        .company-info img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        .table th {
            background: #f0f0f0;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }
        img
        {
            height: 50px;
            width: 50px;
        }
    </style>
</head>

<body>

    <div class="header company-info">
        @if ($admin_sittings)
            <img src="{{ public_path('assets/backEnd/images/' . $admin_sittings->photo) }}" alt="شعار الشركة">
            <h2>{{ $admin_sittings->system_name }}</h2>
            <p>{{ $admin_sittings->address }} - هاتف: {{ $admin_sittings->phone }}</p>
        @endif
    </div>

    <hr>

    <h3 style="text-align:center">فاتورة رقم: {{ $order->auto_serial }}</h3>
    <p><strong>تاريخ الفاتورة:</strong> {{ $order->created_at }}</p>
    <p><strong>نوع الفاتورة:</strong> {{ $order->InvoiceType() }}</p>

    <div class="section-title">البيانات:</div>
    <table class="table">
        <tr>
            <th>من</th>
            <th>إلى</th>
        </tr>
        <tr>
            <td>
                {{ $order->customer ? $order->customer->name : $order->supplier->name }}<br>
                {{ $order->customer ? $order->customer->address : $order->supplier->address }}<br>
                رقم الحساب:
                {{ $order->customer ? $order->customer->account_number : $order->supplier->account_number }}<br>
                الهاتف: {{ $order->customer ? $order->customer->phones : $order->supplier->phones }}
            </td>
            <td>
                {{ $admin_sittings->system_name }}<br>
                {{ $admin_sittings->address }}<br>
                الهاتف: {{ $admin_sittings->phone }}<br>
                كود الشركة: {{ $admin_sittings->company_code }}
            </td>
        </tr>
    </table>

    <div class="section-title">تفاصيل الأصناف:</div>
    <table class="table">
        <thead>
            <tr>
                <th>اسم الصنف</th>
                <th>فئة الصنف</th>
                <th>الوحدة</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
                <th>تاريخ الانتهاء</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->order_detailes as $item)
                <tr>
                    <td>{{ $item->item->name }}</td>
                    <td>{{ $item->item->itemCategory->name }}</td>
                    <td>{{ $item->item_unit->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                    <td>{{ $item->expire_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">الإجماليات:</div>
    <table class="table">
        <tr>
            <th>قبل الضريبة والخصم</th>
            <td>{{ number_format($order->total_cost_before_all, 2) }} جنيه</td>
        </tr>
        <tr>
            <th>الضريبة ({{ $order->tax_percent }}%)</th>
            <td>{{ number_format($order->tax_value, 2) }} جنيه</td>
        </tr>
        <tr>
            <th>بعد الضريبة</th>
            <td>{{ number_format($order->total_before_discount, 2) }} جنيه</td>
        </tr>
        <tr>
            <th>الخصم ({{ $order->discount_percent }}%)</th>
            <td>{{ number_format($order->discount_amount, 2) }} جنيه</td>
        </tr>
        <tr>
            <th><strong>الإجمالي النهائي</strong></th>
            <td><strong>{{ number_format($order->total_cost, 2) }} جنيه</strong></td>
        </tr>
    </table>

    <div class="footer" style="margin-top: 30px;">
        <p>تمت الطباعة بتاريخ: {{ now()->format('Y-m-d H:i') }}</p>
        <p>شكراً لتعاملكم معنا</p>
    </div>

</body>

</html>
