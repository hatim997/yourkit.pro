<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Proforma Invoice format</title>
    <meta name="author" content="Work4" />
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="">
        <div
            style="max-width: 800px;margin: auto;padding: 16px;margin-top: 10px;border: 1px solid #eee;font-size: 16px;line-height: 24px;font-family: 'Inter', sans-serif;color: #555;background-color: #F9FAFC;border-radius: 12px;">
            <table style="font-size: 12px; line-height: 20px; width: 100%;">
                <thead>
                    <tr>
                        <td style="padding: 0 0px 18px;">
                            <table
                                style="background-color: #FFF; padding: 20px 16px; border: 1px solid #D7DAE0;width: 100%; border-radius: 12px;font-size: 12px; line-height: 20px; table-layout: fixed;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div style="padding-right: 10px;width: 200px;">
                                                <img src="{{ public_path('assets/frontend/images/logo.png') }}"
                                                    alt="" class="img-fluid"
                                                    style="width: 100%;height: 100%;object-fit: contain;">
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div style="text-align: end;padding-left: 10px;">
                                                <h1
                                                    style="color: #1A1C21;font-size: 15px;font-style: normal;font-weight: 600;line-height: normal;margin-bottom: 2px;">
                                                    Construction T-shirt</h1>
                                                <p style="line-height: 1.4;">hello@gmail.com</p>
                                                <p style="line-height: 1.4;">+91 8877002233</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table
                                style="background-color: #FFF; padding: 20px 16px; border: 1px solid #D7DAE0;width: 100%; border-radius: 12px;font-size: 12px; line-height: 20px; table-layout: fixed;">
                                <tbody>
                                    <tr>
                                        <td
                                            style="vertical-align: top; width: 30%; padding-right: 20px;padding-bottom: 35px;">
                                            <p style="font-weight: 700; color: #1A1C21;">Client Name</p>
                                            <p style="color: #5E6470;">{{ $order->user->name }}</p>
                                            <a href="javascript:void(0)"
                                                style="line-height: 1.4;color: #555;font-size: 12px;">{{ $order->user->email }}</a>
                                            <p style="line-height: 1.4;">{{ $order->user->phone }}</p>
                                        </td>
                                        <td
                                            style="vertical-align: top; width: 35%; padding-right: 20px;padding-bottom: 35px;">
                                            <p style="font-weight: 700; color: #1A1C21;">Pick-up</p>
                                            <p style="color: #5E6470;">1 Hight street, London, E1 7QL Uk</p>

                                            <p style="font-weight: 700; color: #1A1C21;">Drop-off</p>
                                            <p style="color: #5E6470;">1 Hight street, London, E1 7QL Uk</p>
                                        </td>
                                        <td style="vertical-align: top;padding-bottom: 35px;">
                                            <table style="table-layout: fixed;width:-webkit-fill-available;">
                                                <tr>
                                                    <th style="text-align: left; color: #1A1C21;">Invoice number</th>
                                                    <td style="text-align: right;">#{{ $order->orderID }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left; color: #1A1C21;">Delivery date</th>
                                                    <td style="text-align: right;">06/01/2025</td>
                                                </tr>
                                                <!-- <tr>
                                                        <th style="text-align: left; color: #1A1C21;">Distance</th>
                                                        <td style="text-align: right;">1.568 miles</td>
                                                    </tr> -->
                                                <tr>
                                                    <th style="text-align: left; color: #1A1C21;">Pick-up time</th>
                                                    <td style="text-align: right;">12:20 PM</td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left; color: #1A1C21;">Delivered time</th>
                                                    <td style="text-align: right;">04:00 PM</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 13px;">
                                            <p style="color: #5E6470;">Service </p>
                                            <p style="font-weight: 700; color: #1A1C21;">Delivery Service</p>
                                        </td>
                                        <td style="padding-bottom: 13px;"></td>
                                        <td style="text-align: end; padding-bottom: 13px;">
                                            <p style="color: #5E6470;">Invoice date</p>
                                            <p style="font-weight: 700; color: #1A1C21;">
                                                {{ $order->created_at->format('M d, Y') }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <table style="width: 100%;border-spacing: 0;">
                                                <thead>
                                                    <tr style="text-transform: uppercase;background: #f9fafc;">
                                                        <td style="padding: 8px 6px; border-block:1px solid #D7DAE0;">
                                                            <b>Item Details</b></td>
                                                        <td
                                                            style="padding: 8px 6px; border-block:1px solid #D7DAE0; width: 40px;">
                                                            <b>Qty</b></td>
                                                        <td
                                                            style="padding: 8px 6px; border-block:1px solid #D7DAE0; text-align: end; width: 100px;">
                                                            <b>Rate</b></td>
                                                        <td
                                                            style="padding: 8px 6px; border-block:1px solid #D7DAE0; text-align: end; width: 120px;">
                                                            <b>Amount</b></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->details as $cartId => $orderDetails)
                                                        @php
                                                            $bundleId = $orderDetails->first()->bundle_id;
                                                            $bundle = \App\Models\Bundle::find($bundleId);
                                                        @endphp

                                                        <tr>
                                                            <td style="padding: 0px 6px;padding-block: 8px;">
                                                                <p style="font-weight: 700; color: #1A1C21;">
                                                                    {{ $bundle->name }}</p>
                                                                <p style="color: #5E6470;">
                                                                    @foreach ($bundle->products as $product)
                                                                    <span>{{ $product->name .' * '.$product->pivot->quantity.', ' }}</span>
                                                                    @endforeach
                                                                </p>
                                                            </td>
                                                            <td style="padding: 0px 6px;padding-block: 8px;">
                                                                <p style="font-weight: 700; color: #1A1C21;">1</p>
                                                            </td>
                                                            <td
                                                                style="padding: 0px 6px;padding-block: 8px; text-align: end;">
                                                                <p style="font-weight: 700; color: #1A1C21;">{{ $currency_symbol . number_format($bundle->price, 2) }}</p>
                                                            </td>
                                                            <td
                                                                style="padding: 0px 6px;padding-block: 8px; text-align: end;">
                                                                <p style="font-weight: 700; color: #1A1C21;">{{ $currency_symbol . number_format($bundle->price, 2) }}</p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td style="padding: 12px 0; border-top:1px solid #D7DAE0;"></td>
                                                        <td style="border-top:1px solid #D7DAE0;padding: 0px 6px;"
                                                            colspan="3">
                                                            <table
                                                                style="width: 100%;border-spacing: 0;font-size: 11px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <th
                                                                            style="padding-top: 12px;text-align: end;padding-right: 10px;">
                                                                            <p style="color: #1A1C21;font-size: 12px;">
                                                                                Subtotal</p>
                                                                            <p></p>
                                                                        </th>
                                                                        <td
                                                                            style="padding-top: 12px;text-align: end; color: #1A1C21;">
                                                                            <p style="font-size: 12px;">{{ $currency_symbol . number_format($order->amount, 2) }}
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    @foreach ($order->tax as $tax)
                                                                    <tr>
                                                                        <th
                                                                            style="padding: 12px 0;text-align: end;padding-right: 10px;">
                                                                            <p style="color: #1A1C21;font-size: 12px;">
                                                                                {{ $tax->tax_type }} ({{ $tax->tax_percentage }}%)</p>
                                                                            <p></p>
                                                                        </th>
                                                                        <td
                                                                            style="padding: 12px 0;text-align: end; color: #1A1C21;">
                                                                            <p style="font-size: 12px;">{{ $currency_symbol . number_format($tax->taxable_amount, 2) }}
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th
                                                                            style="padding: 12px 0;text-align: end;padding-right: 10px;color: #1A1C21;border-top:1px solid #D7DAE0;">
                                                                            Total Price</th>
                                                                        <th
                                                                            style="padding: 12px 0;text-align: end; color: #1A1C21;border-top:1px solid #D7DAE0;">
                                                                            {{ $currency_symbol . number_format($order->final_amount, 2) }}</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                                            <td>
                                                                <p style="color: #1A1C21;">(1) VAT non applicable</p>
                                                                <p style="color: #1A1C21;">(2) Price includes the remuneration for
                                                                    MealShift Services</p>
                                                            </td>
                                                        </tr> -->
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="padding: 0px 6px;padding-top: 20px;color: #1A1C21;">
                            <p style="display: flex; gap: 0 13px;"><span style="font-weight: 700;">Note / Terms</span>
                            </p>
                            <p>Terms and Conditions</p>
                            <p>*Please note that a 50% deposit is required for order confirmation with the balance
                                payable upon receipt." Payment methods: Cash, e-Transfer, Check, Visa, MasterCard</p>
                            <p>Terms and conditions: Printed and embroidered items are not exchangeable or refundable.
                                We do not accept returns of items deemed defective after 7 days.</p>
                            <p style="margin-top: 10px;">THANK YOU FOR YOUR TRUST!</p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
