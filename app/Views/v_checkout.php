<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <?php // Disesuaikan dengan CodeIgniter 4 karena form_hidden() tidak mendukung atribut id. ?>
        <?= form_input([
            'type' => 'hidden',
            'name' => 'total_harga',
            'id' => 'total_harga',
            'value' => ''
        ]) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'nama',
                'id' => 'nama',
                'class' => 'form-control',
                'value' => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'alamat',
                'id' => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'ongkir',
                'id' => 'ongkir',
                'class' => 'form-control',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name' => 'voucher_code',
                'id' => 'voucher_code',
                'class' => 'form-control',
                'placeholder' => 'Contoh: FLASH10'
            ]) ?>
            <small class="text-muted">Tersedia: FLASH10, FLASH15, MEMBER20</small>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>
    </div>
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)):
                    foreach ($items as $index => $item):
                        ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Diskon Voucher</td>
                    <td id="diskon">IDR 0</td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>PPN (11%)</td>
                    <td id="ppn">IDR 0</td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Admin</td>
                    <td id="admin">IDR 0</td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Ongkir</td>
                    <td id="ongkir_text">IDR 0</td>
                </tr>

                <tr class="table-success">
                    <td colspan="2"></td>
                    <td><b>Grand Total</b></td>
                    <td>
                        <b><span id="total"><?= number_to_currency($total, 'IDR') ?></span></b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    $(document).ready(function () {
        let ongkir = 0;
        let subtotal = <?= $total ?>;
        hitungTotal();

        function hitungTotal() {

            let voucher = $("#voucher_code").val().trim().toUpperCase();

            let diskonPersen = 0;

            if (voucher === "FLASH10")
                diskonPersen = 10;
            else if (voucher === "FLASH15")
                diskonPersen = 15;
            else if (voucher === "MEMBER20")
                diskonPersen = 20;

            let diskon = subtotal * diskonPersen / 100;

            let ppn = subtotal * 11 / 100;

            let admin = 0;

            if (subtotal <= 20000000)
                admin = subtotal * 0.006;
            else if (subtotal <= 40000000)
                admin = subtotal * 0.008;
            else
                admin = subtotal * 0.01;

            let total = subtotal - diskon + ppn + admin + ongkir;

            $("#diskon").text(`IDR ${diskon.toLocaleString('id-ID')}`);
            $("#ppn").text(`IDR ${ppn.toLocaleString('id-ID')}`);
            $("#admin").text(`IDR ${admin.toLocaleString('id-ID')}`);
            $("#ongkir_text").text(`IDR ${ongkir.toLocaleString('id-ID')}`);

            $("#ongkir").val(ongkir);
            $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
            $("#total_harga").val(total);
        }

        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3,
            ajax: {
                url: '<?= site_url('ajax/destinations') ?>',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return data;
                },
                cache: true
            }
        });

        $("#kelurahan").on('change', function () {
            let id_kelurahan = $(this).val();

            $("#layanan").empty();
            ongkir = 0;
            hitungTotal();

            $.ajax({
                url: "<?= site_url('ajax/costs') ?>",
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function (data) {
                    data.forEach(function (item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                            })
                        );
                    });
                }
            });
        });
        $("#layanan").on('change', function () {
            ongkir = parseInt($(this).val());
            hitungTotal();
        });
        $("#voucher_code").on("keyup change", function () {
            hitungTotal();
        });

        // Nama
        $("#nama").keydown(function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#alamat").focus();
            }
        });

        // Alamat
        $("#alamat").keydown(function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#kelurahan").focus();
            }
        });

        // kelurahan
        $("#kelurahan").keydown(function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#layanan").focus();
            }
        });

        // layanan
        $("#layanan").keydown(function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                $("#voucher_code").focus();
            }
        });

        // Voucher
        $("#voucher_code").keydown(function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                hitungTotal();
                $("#layanan").focus();
            }
        });
    });
</script>
<?= $this->endSection() ?>