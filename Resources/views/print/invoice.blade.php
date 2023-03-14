<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>KUITANSI #{{ $number }}</title>

    <style>
        @page {
            margin: 5px;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        @media all {
            body {
                margin: 10px 0 0 0;
                font-size: 10px;
                font-family: 'Roboto', sans-serif;
            }

            .box {
                margin: auto;
                border-top: 10px solid #67E8F9;
                border-right: 1px solid grey;
                border-left: 1px solid grey;
                border-bottom: 1px solid grey;
            }

            .transaction-status {
                background-color: #ddd;
                padding: 5px;
                display: flex;
                justify-content: center;
            }

            .transaction-status table {
                font-size: 13px;
                width: 100%;
            }

            .transaction-status table td {
                padding: 3px;
            }

            .transaction-status table tr td:first-child {
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
<div class="box">
    <div style="margin: 5px">
        <img style="margin:20px 5rem 0 .5rem" src="{{ $logo }}"
             height="65" alt="Logo Mitra">
        <img style="margin-left: 7rem" src="https://asset.inisiatif.id/logo-izi-full-transparant.png"
             height="65" alt="LAZNAS IZI">
    </div>

    <div style="margin: 5px;">
        <p style="font-weight: bold; font-size: 16px; text-align: center;">BUKTI PEMBAYARAN</p>

        <p>Kepada Bapak/Ibu <strong>{{ $name }}</strong></p>
        <p style="line-height: 22px; text-align: justify;">
            Telah di terima sejumlah uang dari Bapak / Ibu untuk pembayaran dengan detail sebagai berikut :
        </p>
        <div class="transaction-status">
            <table>
                <tr>
                    <td><b>Nomor Transaksi</b></td>
                    <td>:</td>
                    <td>{{ $number }}</td>
                    <td><b>Tanggal Transaksi</b></td>
                    <td>:</td>
                    <td>{{ $transactionAt }}</td>
                </tr>
                <tr>
                    <td><b>Nama Donatur</b></td>
                    <td>:</td>
                    <td>{{ $name }}</td>
                    <td><b>Status</b></td>
                    <td>:</td>
                    <td>{{ $status }}</td>
                </tr>
            </table>
        </div>

        <div style="background-color: #67E8F9; color: #fff; font-weight: bold; display: block; padding: 5px; margin: 10px 0 0;">
            Transaksi
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #ddd;">
            <tr>
                <td style="vertical-align: middle; text-align: left; font-weight: bold; padding: 5px;">
                    Akad
                </td>
                <td style="vertical-align: middle; text-align: left; font-weight: bold; padding: 5px;">
                    Program
                </td>
                <td style="vertical-align: middle; text-align: right; font-weight: bold; padding: 5px;">
                    Jumlah
                </td>
            </tr>
            </thead>
            @foreach($items as $key => $item)
                <tbody style="border-bottom: 1px solid grey;">
                <tr>
                    <td style="vertical-align: top; text-align: left; padding: 5px;">
                        {{ $item['fundingName'] }}
                    </td>
                    <td style="vertical-align: top; text-align: left; padding: 5px;">
                        {{ $item['programName'] ?: '-' }}
                    </td>
                    <td style="vertical-align: top; text-align: right; padding: 5px; width: 5rem;">
                        {{ $item['amount'] }}
                    </td>
                </tr>
                </tbody>
            @endforeach
            <tfoot>
            <tr>
                <td colspan="2" style="vertical-align: top; text-align: right; padding: 5px; font-weight: bold;">
                    Total Pembayaran
                </td>
                <td style="vertical-align: top; text-align: right; padding: 5px;  font-weight: bold; width: 5rem;">
                    {{ $totalAmount }}
                </td>
            </tr>
            </tfoot>
        </table>

        <div style="background-color: #67E8F9; color: #fff; font-weight: bold; display: block; padding: 5px; margin: 10px 0 0;">
            Catatan
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <tbody style="border-bottom: 1px solid grey;">
            <tr>
                <td style="vertical-align: top; text-align: left; padding: 5px;">
                    <i>{{ $note ?: "-" }}</i>
                </td>
            </tr>
            </tbody>
        </table>

        <div style="margin: 10px 0 1em 0; text-align: center;">
            <img src="https://raw.githubusercontent.com/atfromhome/image-assets/main/other/doa-muzaki.png" alt="Doa"
                 width="75%"/>
        </div>

        <p style="margin: 10px 0 1em 0; line-height: 22px; text-align: justify;">
            Demikian kuitansi ini dibuat agar dapat dipergunakan sebagaimana mestinya, terimakasih.
        </p>
    </div>

    <div style="margin: 5px;">
        <p style="font-size: 8px; line-height: 21px; text-align:left;">
            <strong>Keterangan :</strong>
        </p>
        <ol style="font-size: 9px; text-align:justify-all; margin-left: 0; padding-left: 1em;">
            <li>
                Penyetor (Muzaki) menyatakan dana zakat yang disetorkan telah sesuai dengan kriteria/syarat wajib
                zakat, yaitu: (1) Muslim, (2) Milik Sempurna, (3) Cukup Nisab, (4) Cukup Haul, (5) Bersumber dari
                dana
                yang halal.
            </li>
            <li>
                Kami tidak menerima segala bentuk dana yang terkait dengan terorisme dan pencucian uang.
            </li>
            <li>
                Bukti Pembayaran ini diterbitkan oleh LAZNAS Inisiatif Zakat Indonesia di lokasi IZI Point.
            </li>
        </ol>
    </div>
</div>
</body>
</html>
