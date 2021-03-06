<?php
    $aksi="modul/mod_warna/aksi_warna.php";
    switch($_GET['act']){
        default:
            $htmls = [];
            $no = 1;
            foreach ( read_file('../json/warna.json') as $key => $value) {
                $htmls['tablerows'] .= "
                    <tr>
                        <td>{$no}</td>
                        <td>{$value}</td>
                        <td>
                            <a data-id='{$key}' data-value='{$value}' data-action='{$aksi}' href='javascript:void(0)' class='btn btn-warning btn-xs btn-edit' title='Edit'><i class='fa fa-edit'></i> Edit</a>
                            <!--<a href=$aksi?module=merk&act=hapus&id= class='btn btn-danger btn-xs' title='Hapus' onClick=\"return confirm('Apakah Anda Yakin Untuk Menghapus Data Ini ?')\"><i class='fa fa-trash'></i> Hapus</a>-->
                        </td>
                    </tr>
                ";
                $no++;
            }

            echo "
                <div class='col-xs-12'>
                    <div class='box'>
                        <div class='box-header'>
                            <h3 class='box-title'>Warna Produk</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class='box-body'>
                            <div class='panel panel-default' id='formInput'>
                                <div class='panel-heading'> + Tambah Warna Baru</div>
                                <div class='panel-body'>
                                    <form method=POST action='{$aksi}?module=warna&act=input'>
                                        <div class='form-group'>
                                            <label>Nama Warna : </label>
                                            <input value='' type='text' class='form-control' name='warna' placeholder='Masukkan warna baru ...' required>
                                        </div>
                                        <button type='submit' class='btn btn-primary'>Simpan</button>
                                    </form>
                                    <!-- /form -->
                                </div>
                            </div>

                            <hr>
                            <div class='box-body table-responsive no-padding'>
                                <table id='example1' class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Warna</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>{$htmls["tablerows"]}</tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            ";
            ?>
            <script>
                $(document).ready(function(){
                    $(document).on('click','.btn-edit',function(){
                        let data= {
                            "id" : $(this).data('id'),
                            "value" : $(this).data('value'),
                            "action" : $(this).data('action'),
                        };

                        $('#formInput').find('.panel-heading').text('Edit Warna: '+data.value);
                        $('form').attr({ "action" : `${data.action}?module=warna&act=update&id=${data.id}` });
                        $('form').find('input[name=warna]').val(data.value);
                        $('form').find('button[type=submit]').text('Update');

                        // alert(JSON.stringify(data)); #for debuging
                    });
                });
            </script>
            <?php

            break;
    }
?>
