<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Troliin</title>
</head>

<body>
    Tanggal Cetak : <?= date('d F Y'); ?>
    <table width="100%" style="border: 0.1mm solid #000000;" cellpaddin="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Stock Barang </th>
                <th>Tanggal Data Masuk </th>

            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($semuatroliin as $troliin) : ?>
                <tr>
                    <td>&nbsp;<?= $i ?></td>
                    <td>&nbsp;&nbsp;
                        <?= $troliin['nama_barang']; ?>&nbsp;&nbsp;&nbsp;</td>
                         <td>&nbsp;&nbsp;
                        <?= $troliin['stock_barang']; ?></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= date('d F Y', $troliin['date_created']);  ?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
            <?php $i++;
            endforeach; ?>
        </tbody>
    </table>
</body>

</html>