<?php
header("Content-type:application/octet-stream/");

header("Content-Disposition:attachment; filename=$title.xls");

header("Pragma: no-cache");

header("Expires: 0");
?>
<h3> Laporan Stock Barang Masuk tanggal : <?= date('d F Y'); ?> </h3>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stock Barang</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($semuatroliin as $troliin) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $troliin['nama_barang']; ?></td>
                <td><?= $troliin['stock_barang']; ?></td>
                <td><?= date('d F Y', $troliin['date_created']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>