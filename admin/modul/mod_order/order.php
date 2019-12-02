<?php
    session_start();
    if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){ # jika username dan password kosong makan akan di lempar pada halaman login
        echo "
            <link href='style.css' rel='stylesheet' type='text/css'>
            <center>
                Untuk mengakses modul, Anda harus login <br>
                <a href=../../index.php><b>LOGIN</b></a>
            </center>
        ";
    }
    else{ # jika username dan password tidak kosong
        $aksi="modul/mod_order/aksi_order.php";
        
        switch($_GET['act']){
            default:
                $data = [];

                $data['kemarin'] = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
                $data['order_expired'] = mysql_num_rows(mysql_query("SELECT * FROM `orders` WHERE 1 AND orders.status='Unpaid' AND orders.tanggal < '2019-12-01'"));
                $data['update_produk'] = ("
                    UPDATE
                        produk,
                        orders_detail,
                        orders
                    SET
                        produk.stok=produk.stok+orders_detail.jumlah 
                    WHERE produk.id_produk=orders_detail.id_produk 
                        AND orders.id_orders=orders_detail.id_orders
                        AND orders.tanggal < '{$data['kemarin']}' 
                        AND orders.status='Unpaid'
                ");
                $data['delete_order'] = ("
                    DELETE
                        FROM
                            orders,
                            orders_detail
                    WHERE orders.id_orders=orders_detail.id_orders
                        AND orders.status='Unpaid'
                        AND orders.tanggal < '{$data['kemarin']}'
                ");
                if ( $data['order_expired'] > 0 ) { # jika terdapat order yang sudah lebih dari sehari hapus data order
                    mysql_query($data['update_produk']); # kembalikan jumlah produk yang ada di detail order ke tabel produk jumlah stok
                    mysql_query($data['delete_order']); # delete order kadaluarsa/expired
                }
                
                $data['rows_order_html'] = [];
                $tampil = mysql_query("SELECT * FROM orders,member WHERE orders.id_member=member.id_member ORDER BY orders.tanggal DESC ");					
                while( $r=mysql_fetch_assoc($tampil) ){
                    $tanggal        = tgl_indo($r['tanggal']);
                    $status         = $r['status'];
                    $grandtotal_rp  = format_rupiah($r['grandtotal']);
                    
                    if ($status=='Unpaid') {
                        $status_mod = "<font color='red'>{$r['status']}</font>";
                    } else {
                        $status_mod = "<font color='green'>{$r['status']}</font>";
                    }

                    $data['rows_order_html'][] = "
                        <tr>
                            <td align=center>{$r['id_orders']}</td>
                            <td>{$r['nama']}</td>
                            <td>{$tanggal}</td>
                            <td>{$r['jam']}</td>
                            <td>{$status_mod}</td>
                            <td>Rp. {$grandtotal_rp}</td>
                            <td>
                                <a href=?module=order&act=detailorder&id={$r['id_orders']} class='btn btn-success btn-sm' title='Detail'><i class='fa fa-folder'></i></a>
                            </td>
                        </tr>
                    ";
                    // $no++;
                }
                $data['rows_order_html'] = implode('', $data['rows_order_html']);

                echo "
                    <div class='col-xs-12'>
                        <div class='box'>
                            <div class='box-header'>
                                <h3 class='box-title'>Orders</h3>
                            </div>
                            <!-- /.box-header -->
			
                            <div class='box-body'>
                                <table id='example1' class='table table-bordered table-striped'> 
                                    <thead>
                                        <tr>
                                            <th>No.order</th>
                                            <th>Nama Member</th>
                                            <th>Tgl. order</th>
                                            <th>Jam</th>
                                            <th>Status</th>
                                            <th>Grand Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>{$data['rows_order_html']}</tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                ";
                break;

            case "detailorder":
                $data       = [];
                $edit       = mysql_query("SELECT * FROM orders WHERE id_orders='{$_GET['id']}' ");
                $r          = mysql_fetch_assoc($edit);
                $tanggal    = tgl_indo($r['tanggal']);

                $customer       =mysql_query("select * from member where id_member='$r[id_member]'");
                $c              =mysql_fetch_assoc($customer);
                $pilihan_status = array('Unpaid', 'Paid');

                $pilihan_order  = '';
                foreach ($pilihan_status as $status) {
                    $pilihan_order .= "<option value='{$status}'";
                    if ( $status == $r['status'] ) {
                        $pilihan_order .= " selected";
                    }
                    $pilihan_order .= ">$status</option>\r\n";
                }
                
                // tampilkan rincian produk yang di order
                $data['rows_order_detail_html'] = [];
                $sql    = mysql_query("SELECT * FROM orders_detail,produk WHERE orders_detail.id_produk=produk.id_produk AND id_orders='$_GET[id]'");
                while( $s= mysql_fetch_array($sql) ){
                    $produk_attr = [];
                    if ( $s['kondisi'] ) {
                        $produk_attr[]= "Kondisi : {$s['kondisi']}";
                    }
                    if ( $s['warna'] ) {
                        $produk_attr[]= "Warna : {$s['warna']}";
                    }
                    if ( $s['ukuran'] ) {
                        $produk_attr[]= "Ukuran : {$s['ukuran']}";
                    }
                    $produk_attr = implode(',',$produk_attr);
                    $produk_attr = "<small>({$produk_attr})</small>";

                    $data['rows_order_detail_html'][] = "
                        <tr>
                            <td>{$s['nama_produk']} {$produk_attr}</td>
                            <td>{$s['berat']}</td>
                            <td>{$s['jumlah']}</td>
                            <td>Rp. ".format_rupiah($s['harga'])."</td>
                            <td>Rp. ".format_rupiah($s['harga']*$s['jumlah'])."</td>
                        </tr>
                    ";
                }

                $data['rows_order_detail_html'][] = "
                    <tr>
                        <th colspan='3' rowspan='4'></th>
                        <th>Total:</th>
                        <td>Rp. ".format_rupiah($r['total'])."</td>
                    </tr>
                    <tr>
                        <th>Ongkos Kirim:</th>
                        <td>Rp. ".format_rupiah($r['ongkir'])."</td>
                    </tr>
                    <tr>
                        <th>Kode Unik:</th>
                        <td>{$r['kode']}</td>
                    </tr>
                    <tr>
                        <th>Grand Total:</th>
                        <td>Rp. ".format_rupiah($r['grandtotal'])."</td>
                    </tr>
                ";
                
                $data['rows_order_detail_html'] = implode('',$data['rows_order_detail_html']);

                echo "
                    <div class='box'>
                        <div class='box-header'>
                            <h3 class='box-title'>DETAIL ORDERS</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class='box-body'>
                            <!-- info row -->
                            <div class='row invoice-info'>
                                <div class='col-sm-4 invoice-col'>
                                    Kepada :
                                    <address>
                                        {$r['alamat_pengiriman']}
                                    </address>
                                </div>
                                <!-- /.col -->

                                <form method=POST action=$aksi?module=order&act=update>
                                    <input type=hidden name=id value=$r[id_orders]>
                                    <div class='col-sm-4 invoice-col'>
                                        <b>Invoice</b><br><br>
                                        <b>Order ID : </b> {$r['id_orders']}<br>
                                        <b>Tgl. orders : </b> {$tanggal}<br>
                                        <b>Kurir : </b> {$r['kurir']}<br>
                                        <b>Status : </b>  <select name='status'>{$pilihan_order}</select> 
                                        <input type=submit value='Ubah Status'>
                                    </div>
                                    <!-- /.col -->
                                </form>
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class='row'>
                                <div class='col-xs-12 table-responsive'>
                                    <table class='table table-striped'>
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Berat(Gram)</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>{$data['rows_order_detail_html']}</tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class='row no-print'>
                                <div class='col-xs-12'>
                                    <a href=modul/mod_order/cetak.php?id={$r['id_orders']} target='_blank' class='btn btn-primary pull-right'><i class='fa fa-print'></i> Print</a><br>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                ";
                break;  
        }
    }
?>
