<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashData('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
History Transaksi Pembelian <strong><?= $username ?></strong>
<hr>
<div class="table-responsive">
    <!-- Table with stripped rows -->
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Waktu Pembelian</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Alamat</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($transactions)) :
                foreach ($transactions as $index => $item) :
            ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['created_at'] ?></td>
                        <td><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
                        <td><?= $item['alamat'] ?></td>
                        <td>
                            <?= ($item['status'] == "1")
                                ? '<span class="badge bg-success">Sudah Selesai</span>'
                                : '<span class="badge bg-warning">Belum Selesai</span>' ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                                Detail
                            </button>
                            <a href="<?= base_url('history/delete/' . $item['id']) ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus riwayat transaksi #<?= $item['id'] ?>?');">
                                Hapus
                            </a>
                        </td>
                    </tr> 
            <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
    <!-- End Table with stripped rows -->
</div>

<?php if (!empty($transactions)) : ?>
    <?php foreach ($transactions as $item) : ?>
        <!-- Detail Modal Begin -->
        <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi #<?= $item['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> 
                        <?php if (!empty($products[$item['id']])) : ?>
                            <?php foreach ($products[$item['id']] as $index2 => $item2) : ?>
                                <?= $index2 + 1 . ")" ?>
                                
                                <?php
                                $imagePath = FCPATH . 'img/' . $item2['foto'];

                                if (!empty($item2['foto']) && file_exists($imagePath)) :
                                ?>
                                    <div class="my-2">
                                        <img src="<?= base_url('img/' . $item2['foto']) ?>" width="100" class="img-thumbnail">
                                    </div>
                                <?php endif; ?>

                                <strong><?= $item2['nama'] ?></strong>
                                <?= number_to_currency($item2['harga'], 'IDR') ?>
                                <br>
                                <?= "(" . $item2['jumlah'] . " pcs)" ?><br>
                                <?= number_to_currency($item2['subtotal_harga'], 'IDR') ?>
                                <hr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <table class="table table-sm mb-0">
                            <tbody>
                                <?php if (!empty($item['voucher_code'])) : ?>
                                    <tr>
                                        <td>Voucher</td>
                                        <td class="text-end"><?= esc($item['voucher_code']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Diskon Voucher</td>
                                        <td class="text-end">-<?= number_to_currency($item['diskon_voucher'] ?? 0, 'IDR') ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>PPN (11%)</td>
                                    <td class="text-end"><?= number_to_currency($item['ppn'] ?? 0, 'IDR') ?></td>
                                </tr>
                                <tr>
                                    <td>Biaya Admin</td>
                                    <td class="text-end"><?= number_to_currency($item['biaya_admin'] ?? 0, 'IDR') ?></td>
                                </tr>
                                <tr>
                                    <td>Ongkir</td>
                                    <td class="text-end"><?= number_to_currency($item['ongkir'], 'IDR') ?></td>
                                </tr>
                                <tr class="table-success">
                                    <td><b>Grand Total</b></td>
                                    <td class="text-end"><b><?= number_to_currency($item['total_harga'], 'IDR') ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Modal End -->
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>