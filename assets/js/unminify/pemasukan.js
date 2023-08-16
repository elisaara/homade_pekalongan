let isCetak = false,
    produk = [],
    transaksi = $("#transaksi").DataTable({
        responsive: true,
        lengthChange: false,
        searching: false,
        scrollX: true
    });

function reloadTable() {
    transaksi.ajax.reload()
}

function generateTransaksi() {
    $.ajax({
        url: generateUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("[name='id']").val()
        },
        success: res => {
            tot = 0;
            $.each(res.data.detail, function(kkk, vvv){
                let barcode = vvv.barcode,
                id_produk = vvv.id_produk,
                nama_produk = vvv.nama_produk,
                qty = parseInt(vvv.qty),
                stok = 0,
                harga = parseInt(vvv.harga),
                harga_total = parseInt(vvv.harga_total),
                jumlah = vvv.harga_total;
                    produk.push({
                        id: id_produk,
                        terjual: qty
                    });
                    transaksi.row.add([
                        barcode,
                        nama_produk,
                        qty,
                        harga,
                        jumlah,
                        id_produk,
                        `<button name="${id_produk}" class="btn btn-sm btn-danger" onclick="remove('${id_produk}')">Hapus</btn>`]).draw();
                    tot = tot+harga_total;
            });
            $("#nota").html(res.data.nota);
            if(res.data.detail.length == 0){
                $("[name='id']").val('');
                $("#nota").html(nota(15));
            }  
            $("#total").html(tot);
            $("#jumlah").val("");
            $("#tambah").attr("disabled", "disabled");
            $("#bayar").removeAttr("disabled");   
            $('#transaksi tr td:nth-child(6)').hide();     
        }
    })
}

function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return 'IN#'+hasil
}

function getNama() {
    $.ajax({
        url: produkGetNamaUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
            $("#nama_produk").html(res.barcode);
            $("#sisa").html(`Sisa ${res.stok}`);
            checkEmpty()
        },
        error: err => {
            console.log(err)
        }
    })
}

function checkStok() {
    $.ajax({
        url: produkGetStokUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("#barcode").val()
        },
        success: res => {
            let barcode = $("#barcode").val(),
                nama_produk = res.nama_produk,
                id_produk = res.id,
                qty = parseInt($("#jumlah").val()),
                stok = parseInt(res.stok),
                harga = parseInt(res.harga),
				jumlah = qty*harga,
                dataBarcode = res.barcode,
                total = parseInt($("#total").html());
            
                let a = transaksi.rows().indexes().filter((a, t) => dataBarcode === transaksi.row(a).data()[0]);
                if (a.length > 0) {
                    let row = transaksi.row(a[0]),
                        data = row.data();
                    
                        data[2] = data[2] + qty;
						data[4] = data[2]*harga;
                        row.data(data).draw();
                        indexProduk = produk.findIndex(a => a.id == barcode);
                        produk[indexProduk].stok = stok - data[2];
                        $("#total").html(total + harga * qty)
                } else {
                    produk.push({
                        id: barcode,
                        terjual: qty
                    });
                    transaksi.row.add([
                        dataBarcode,
                        nama_produk,
                        qty,
                        harga,
                        jumlah,
                        id_produk,
                        `<button name="${barcode}" class="btn btn-sm btn-danger" onclick="remove('${barcode}')">Hapus</btn>`]).draw();
                    $("#total").html(total + harga * qty);
                    $("#jumlah").val("");
                    $("#tambah").attr("disabled", "disabled");
                    $("#bayar").removeAttr("disabled");
                } 
                    $("#simpan").removeAttr("disabled");
            $('#transaksi tr td:nth-child(6)').hide();
            
        }
    })
}

function bayarCetak() {
    isCetak = true;
    $('#byr').val(1);
}

function bayar() {
    isCetak = false
    $('#byr').val(1);
}

function checkEmpty() {
    let barcode = $("#barcode").val(),
        jumlah = $("#jumlah").val();
    if (barcode !== "" && jumlah !== "" && parseInt(jumlah) >= 1) {
        $("#tambah").removeAttr("disabled")    
    } else {
        $("#tambah").attr("disabled", "disabled")
    }
}

function checkUang() {
    let jumlah_uang = $('[name="jumlah_uang"').val(),
        total_bayar = parseInt($(".total_bayar").html());
    if (jumlah_uang !== "" && jumlah_uang >= total_bayar) {
        $("#add").removeAttr("disabled");
        $("#cetak").removeAttr("disabled")
    } else {
        $("#add").attr("disabled", "disabled");
        $("#cetak").attr("disabled", "disabled")
    }
}

function remove(nama) {
    let data = transaksi.row($("[name=" + nama + "]").closest("tr")).data(),
        stok = data[2],
        harga = data[3],
        total = parseInt($("#total").html());
        akhir = total - stok * harga
    $("#total").html(akhir);
    transaksi.row($("[name=" + nama + "]").closest("tr")).remove().draw();
    $("#tambah").attr("disabled", "disabled");
        $("#simpan").removeAttr("disabled");
    if (akhir < 1) {
        $("#bayar").attr("disabled", "disabled");
        $("#simpan").attr("disabled", "disabled");
    }
}

function add() {
    let data = transaksi.rows().data(),
        qty = [];
        prdk = [];
    $.each(data, (index, value) => {
        qty.push(value[2]);
        var obj = {
            'id' : value[5]
        };
        prdk.push(obj);
    });
    $.ajax({
        url: addUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("[name='id']").val(),
            produk: prdk,
            tanggal: $("#tanggal").val(),
            qty: qty,
            total_bayar: $("#total").html(),
            jumlah_uang: $('[name="jumlah_uang"]').val(),
            diskon: $('[name="diskon"]').val(),
            pelanggan: $("#pelanggan").val(),
            nota: $("#nota").html(),
            flag:1,
            bayar:$("#byr").val()
        },
        success: res => {
            if (isCetak) {
                Swal.fire("Sukses", "Sukses Membayar", "success").
                    then(() => window.location.href = `${cetakUrl}${res}`)
            } else {
                Swal.fire("Sukses", "Sukses Membayar", "success").
                    then(() => window.location.href = `${backUrl}`)
            }
        },
        error: err => {
            console.log(err)
        }
    })
}

function simpan() {
    let data = transaksi.rows().data(),
        qty = [];
        prdk = [];
    $.each(data, (index, value) => {
        qty.push(value[2]);
        var obj = {
            'id' : value[5]
        };
        prdk.push(obj);
    });
    $.ajax({
        url: addUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $("[name='id']").val(),
            produk: prdk,
            qty: qty,
            total_bayar: $("#total").html(),
            nota: $("#nota").html(),
            flag:0,
            bayar:0
        },
        success: res => {
                Swal.fire("Sukses", "Proses Simpan dalam Persetujuan", "success").
                    then(() => window.location.href = `${backUrl}`)
        },
        error: err => {
            console.log(err)
        }
    })
}

function kembalian() {
    let total = $("#total").html(),
        jumlah_uang = $('[name="jumlah_uang"').val(),
        diskon = $('[name="diskon"]').val();
    $(".kembalian").html(jumlah_uang - total - diskon);
    checkUang()
}
$("#barcode").select2({
    placeholder: "Nama Produk",
    ajax: {
        url: getBarcodeUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            barcode: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
});
$("#pelanggan").select2({
    placeholder: "Pelanggan",
    ajax: {
        url: pelangganSearchUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            pelanggan: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
});
$("#tanggal").datetimepicker({
    format: "dd-mm-yyyy h:ii:ss"
});
$(".modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});
$(".modal").on("show.bs.modal", () => {
    let now = moment().format("D-MM-Y H:mm:ss"),
        total = $("#total").html(),
        jumlah_uang = $('[name="jumlah_uang"').val();
    $("#tanggal").val(now), $(".total_bayar").html(total), $(".kembalian").html(Math.max(jumlah_uang - total, 0))
});
$("#form").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback"), el.closest(".form-group").append(err)
    },
    submitHandler: () => {
        add();
    }
});
$("#nota").html(nota(15));
generateTransaksi();