<?php
if ($_GET['module']=='home'){
echo"
<h4>Produk Terbaru </h4>
			  <ul class='thumbnails'>";
			  // Tampilkan 4 produk terbaru
  $sql=mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT 6");  
  $kolom = 3;
  $i=0;
  while ($r=mysql_fetch_array($sql)){
    $harga1 = $r[harga];
    $harga     = number_format($harga1,0,",",".");
	echo"
				<li class='span3'>
				  <div class='thumbnail'>
					<a  href='media.php?module=detailproduk&id=$r[id_produk]'><img src='foto_produk/medium_$r[gambar]' alt=''/></a>
					<div class='caption'>
					  <h5>$r[nama_produk]</h5>
					  <p> 
						<strong> Rp. $harga</strong> 
					  </p>
					 
					  <h4 style='text-align:center'><a class='btn' href='aksi.php?module=keranjang&act=tambah&id=$r[id_produk]'>Add to <i class='icon-shopping-cart'></i></a></h4>
					</div>
				  </div>
				</li>";
			}
			echo"
				
			  </ul>";
}
elseif ($_GET['module']=='semuaproduk'){
echo"
<h4>Semua Produk </h4>
			  <ul class='thumbnails'>";
			  // Tampilkan 4 produk terbaru
  // Tampilkan semua produk
  $p      = new Paging2;
  $batas  = 6;
  $posisi = $p->cariPosisi($batas);
  // Tampilkan semua produk
  $sql=mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT $posisi,$batas");
  while($r=mysql_fetch_array($sql)){
    $harga1 = $r[harga];
    $harga     = number_format($harga1,0,",",".");
    if ($r[gambar]!=''){
		}
    // Tampilkan hanya sebagian isi berita
    $isi_produk = nl2br($r[deskripsi]); // membuat paragraf pada isi berita
    $isi = substr($isi_produk,0,300); // ambil sebanyak 300 karakter
    $isi = substr($isi_produk,0,strrpos($isi," ")); // potong per spasi kalimat
	echo"
				<li class='span3'>
				  <div class='thumbnail'>
					<a  href='media.php?module=detailproduk&id=$r[id_produk]'><img src='foto_produk/medium_$r[gambar]' alt=''/></a>
					<div class='caption'>
					  <h5>$r[nama_produk]</h5>
					  <p> 
						<strong> Rp. $harga</strong> 
					  </p>
					 
					  <h4 style='text-align:center'><a class='btn' href=''>Add to <i class='icon-shopping-cart'></i></a></h4>
					</div>
				  </div>
				</li>";
			}
			echo"
				
			  </ul>";
			  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halproduk], $jmlhalaman);
  echo "<br><div class='center_title_bar'>Hal: $linkHalaman</div>";
}
elseif ($_GET['module']=='detailmerk'){
$sq = mysql_query("SELECT nama_merk from merk where id_merk='$_GET[id]'");
  $n = mysql_fetch_array($sq);
echo"
<h4>Merk $n[nama_merk] </h4>
			  <ul class='thumbnails'>";
			  // Tampilkan 4 produk terbaru
 $p      = new Paging3;
  $batas  = 6;
  $posisi = $p->cariPosisi($batas);  
  // Tampilkan daftar produk yang sesuai dengan merk yang dipilih
 	$sql   = "SELECT * FROM produk WHERE id_merk='$_GET[id]' 
            ORDER BY id_produk DESC LIMIT $posisi,$batas";		 
	$hasil = mysql_query($sql);
	$jumlah = mysql_num_rows($hasil);
	// Apabila ditemukan produk dalam merk
	if ($jumlah > 0){
    $kolom = 2;
    $i=0;
   while($r=mysql_fetch_array($hasil)){
    $harga1 = $r[harga];
    $harga     = number_format($harga1,0,",",".");
    // Tampilkan hanya sebagian isi berita
    $isi_produk = nl2br($r[deskripsi]); // membuat paragraf pada isi berita
    $isi = substr($isi_produk,0,120); // ambil sebanyak 120 karakter
    $isi = substr($isi_produk,0,strrpos($isi," ")); // potong per spasi kalimat
    if ($i >= $kolom){
      $i=0;
    }
    $i++;
	echo"
				<li class='span3'>
				  <div class='thumbnail'>
					<a  href='media.php?module=detailproduk&id=$r[id_produk]'><img src='foto_produk/medium_$r[gambar]' alt=''/></a>
					<div class='caption'>
					  <h5>$r[nama_produk]</h5>
					  <p> 
						<strong> Rp. $harga</strong> 
					  </p>
					 
					  <h4 style='text-align:center'><a class='btn' href='aksi.php?module=keranjang&act=tambah&id={$r['id_produk']}'>Add to <i class='icon-shopping-cart'></i></a></h4>
					</div>
				  </div>
				</li>";
			}
			echo"
				
			  </ul>";
			  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk WHERE id_merk='$_GET[id]'"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halmerk], $jmlhalaman);
  echo "<div class='center_title_bar'>Hal: $linkHalaman</div>";
   }
  else{
    echo "<p align=center>Belum ada produk pada merk ini.</p>";
  }
}



/* ==================== START MENU INFORMASI ==================== */
elseif ($_GET['module']=='hubungikami'){
	$row = read_file('json/informasi.json');
	echo "							
		<div class='span9'>
			<h2>Hubungi Kami</h2>
			{$row->hubungi_kami}
			<h4>Lokasi Kami</h4>
			<iframe src='https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15812.53971502891!2d110.3723724!3d-7.7755143!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x1edf5047944f878c!2sTaurus%20Computer%20Solution!5e0!3m2!1sid!2sid!4v1578319747123!5m2!1sid!2sid' width='100%' height='450' frameborder='0' style='border:0;' allowfullscreen=''></iframe>
		</div>
	";
}

elseif ($_GET['module']=='profilkami'){
	$row = read_file('json/informasi.json');
	echo "							
		<div class='span9'>
			<h2>Profil Kami</h2>
			{$row->profil}
		</div>
	";
}

elseif ($_GET['module']=='carabeli'){
	$row = read_file('json/informasi.json');
	echo"							
	<div class='span9'>
		<h2>Cara Pembelian Produk</h2>
		{$row->cara_pembelian}
	</div>";
	
}
/* ==================== END MENU INFORMASI ==================== */

elseif ($_GET['module']=='detailproduk'){
	$detail 	= mysql_query("SELECT * FROM produk,merk WHERE produk.id_merk=merk.id_merk AND id_produk='$_GET[id]'");
	$d   		= mysql_fetch_assoc($detail);
	$harga     	= number_format($d[harga],0,",",".");
	$merk		=$d['id_merk'];
	$harga1		=$d['harga'];

	$produk_attr = [];
	if ( $d['kondisi'] ) {
		$produk_attr[]= "<span class='label label-info'>Kondisi : {$d['kondisi']}</span>";
	}
	if ( $d['warna'] ) {
		$produk_attr[]= "<span class='label label-info'>Warna : {$d['warna']}</span>";
	}
	if ( $d['ukuran'] ) {
		$produk_attr[]= "<span class='label label-info'>Ukuran : {$d['ukuran']}</span>";
	}

	$produk_attr = implode('&nbsp',$produk_attr);

	echo "
		<div class='row'>	  
			<div id='gallery' class='span3'>
				<a href='foto_produk/{$d['gambar']}' title='{$d['nama_produk']}'>
					<img src='foto_produk/medium_{$d['gambar']}' style='width:100%' alt='{$d['nama_produk']}'/>
				</a>
			</div>

			<div class='span6'>
				<h3>{$d['nama_produk']}</h3>
				<hr class='soft'/>
				<form class='form-horizontal qtyFrm'>
					<div class='control-group'>
						<label class='control-label'><span>Rp. {$harga}</span></label>
						<div class='controls'>
							<a href='aksi.php?module=keranjang&act=tambah&id={$d['id_produk']}' class='btn btn-large btn-primary pull-left'>Beli<i class=' icon-shopping-cart'></i></a>
						</div>
					</div>
					{$produk_attr}
				</form>
				<hr class='soft'/><br>

				<h4>{$d['stok']} Items In Stock</h4>
				<br class='clr'/>

				<a href='#' name='detail'></a>
				<hr class='soft'/>
			</div>
			
			<div class='span9'>
				<ul id='productDetail' class='nav nav-tabs'>
					<li class='active'><a href='#home' data-toggle='tab'>Detail Produk</a></li>
				</ul>
				<div id='myTabContent' class='tab-content'>
					<div class='tab-pane fade active in' id='home'>
						<h4>Keterangan Produk</h4>
						<p>{$d['deskripsi']}</p>
					</div>
				</div>
			</div>
			<!-- /.span9 -->

		</div>
	";
}

/* ==================== START MENU MEMBER ==================== */
elseif ($_GET['module']=='daftarmember'){
	/* load api raja ongkir */
	require_once 'vendor/autoload.php';
	
	$htmls= [];
	
	$rows_provinsi = RajaOngkir\RajaOngkir::Provinsi()->all();
	$htmls['option_provinsi'][] = "<option value='' selected disabled> -- Pilih Provinsi -- </option>";
	foreach ($rows_provinsi as $key => $value) {
		$htmls['option_provinsi'][] = "<option value='{$value['province_id']}'>{$value['province']}</option>";
	}
	$htmls['option_provinsi'] 	= implode('',$htmls['option_provinsi']);
	$htmls['option_kota'] 	= "<option value='' selected disabled> -- Pilih Provinsi Terlebih Dahulu -- </option>";

	echo"
		<div class='span9'>
			<h4> Form Daftar Member</h4>
			<form id='form1' action=daftar-aksi.html method=POST class='form-horizontal'>
				<table class='table table-bordered'>
					<tr>
						<th> Detail Data Pribadi Anda</th>
					</tr>
					<tr> 
						<td>
							<div class='control-group'>
								<label class='control-label' for='inputFname'>Nama Lengkap <sup>*</sup></label>
								<div class='controls'>
									<input type='text' class='input-block-level mod-width-fit-content' name='nama' id='inputFname' placeholder='Masukkan Nama Lengkap' required>
								</div>
							</div>
					
							<div class='control-group'>
								<label class='control-label' for='inputEmail'>Email <sup>*</sup></label>
								<div class='controls'>
									<input type='email' class='input-block-level mod-width-fit-content' name='email' placeholder='email@gmail.com' required>
								</div>
							</div>	  

							<div class='control-group'>
								<label class='control-label'>Password <sup>*</sup></label>
								<div class='controls'>
									<input type='password' class='input-block-level mod-width-fit-content' name='password' placeholder='**********' required>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label' for='inputFname'>Nomor Telepon <sup>*</sup></label>
								<div class='controls'>
									<input type='text' class='input-block-level mod-width-fit-content input-number-only' min='0' name='no_telp'  placeholder='08123456789' required>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label'>Provinsi <sup>*</sup></label>
								<div class='controls'>
									<select class='input-block-level mod-width-fit-content' name='provinsi' required>
										{$htmls['option_provinsi']}
									</select>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label'>Kota/Kabupaten <sup>*</sup></label>
								<div class='controls'>
									<select class='input-block-level mod-width-fit-content' name='kota' required>
										{$htmls['option_kota']}
									</select>
								</div>
							</div>
							
							<div class='control-group'>
								<label class='control-label' for='inputFname'>Kode Pos <sup>*</sup></label>
								<div class='controls'>
									<input type='text' class='input-block-level mod-width-fit-content input-number-only'  name='kode_pos'  placeholder='Kode Pos' required>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label' for='inputFname'>Alamat Lengkap <sup>*</sup></label>
								<div class='controls'>
									<textarea class='input-block-level' name='alamat' placeholder='Isi nama jalan, nomor rumah, nama gedung, dsb' required></textarea>
								</div>
							</div>
							<div class='control-group'>
								<div class='controls'>
									<input type='submit' name='submitAccount' value='Register' class='exclusive shopBtn btn btn-primary'>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	";

}

elseif ($_GET['module']=='daftaraksi'){
	$sql = mysql_query("SELECT * FROM member WHERE email='{$_POST['email']}' OR no_telp ='{$_POST['no_telp']}'");

	if ( mysql_num_rows($sql) > 0 ){ # jika user sudah ada
		echo "							
		<div class='span9'>
			<h3> Form Daftar Member</h3>	
			<hr class='soft'/>
			<p align=center>Maaf! Email atau nomor telepon yang Anda masukkan sudah terdaftar, Silahkan ganti yang lain<br />
			<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a>
		</div>";

	}else { # jika user belum ada
		$pass = md5($_POST['password']);
		$q = md5(date(Ymdhis));
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';
		mysql_query("INSERT
					INTO member(
							nama,
							email,
							password,
							no_telp,
							alamat_member,
							tgl_daftar,
							session,
							status,
							provinsi,
							kota,
							kode_pos
					) 
					VALUES(
						'{$_POST['nama']}',
						'{$_POST['email']}',
						'{$pass}',
						'{$_POST['no_telp']}',
						'{$_POST['alamat']}',
						'{$tgl_sekarang}',
						'{$q}',
						'tertunda',
						'{$_POST['provinsi']}',
						'{$_POST['kota']}',
						'{$_POST['kode_pos']}'
					)
		");

		/* ========== START SEND EMAIL ========== */
		// include("phpmailer/classes/class.phpmailer.php");
		// $mail = new PHPMailer; 
		// $mail->IsSMTP();
		// $mail->SMTPSecure = 'ssl'; 
		// $mail->Host = "smtp.gmail.com";
		// $mail->SMTPDebug = 0;
		// $mail->Port = 465;
		// $mail->SMTPAuth = true;
		// $mail->Username = "3s0c9m7@gmail.com";
		// $mail->Password = $mail->simple_crypt( 'Q28vcmJtWmxESE1UQjBCazFQL2w3QT09', 'd' );
		// $mail->SetFrom("info@tauruscomputer.com","TAURUS COMPUTER");
		// $mail->Subject = "Verifikasi Email";
		// $mail->AddAddress("{$_POST['email']}","nama email tujuan");
		// $mail->MsgHTML("
		//     Silakan verifikasi email kamu dengan mengklik tautan berikut :<br>
		//     <a href='{$_SERVER['HTTP_HOST']}/cek_login.php?q={$q}'>Klik Disini untuk verifikasi email</a>
		// ");
		
		// $mail->Send();

		$to = $_POST['email'];
		$subject = 'TAURUS COMPUTER';
		$message = "
			<html>
				<head>
				<title>TAURUS COMPUTER</title>
				</head>
				<body>
					Silakan verifikasi email kamu dengan mengklik tautan berikut :<br>
					<a href='{$_SERVER['HTTP_HOST']}/cek_login.php?q={$q}'>Klik Disini untuk verifikasi email</a>
					<p><strong>NOTE! :</strong> jika link tidak bisa di klik silahkan copy url ini [{$_SERVER['HTTP_HOST']}/cek_login.php?q={$q}]</p>
				</body>
			</html>	
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: tauruscomputer@gmail.com' . "\r\n";
		// $headers .= 'Cc: myboss@example.com' . "\r\n";

		// send email
		mail($to,$subject,$message,$headers);

		/* ========== END SEND EMAIL ========== */

		echo "							
		<div class='span9'>
			<h3> Form Daftar Member</h3>	
			<hr class='soft'/>
			<p align=center>
				<b>Terimakasih telah mendaftar Sebagai Member. <br />
				Silahkan buka email untuk aktivasi akun Anda</b>
			</p> 
		</div>";
	}
			
	
}

elseif ($_GET['module']=='loginmember'){
	echo "							
	<div class='span9'>
		<h3> Form Login Member</h3>	
		<form action='cek_login.php' method='POST' class='form-horizontal'>
			<table class='table table-bordered'>
				<tr>
					<th>Silahkan Isi Form Di Bawah Ini</th>
				</tr>
				<tr> 
					<td>
						<div class='control-group'>
							<label class='control-label' for='inputEmail'>Email <sup>*</sup></label>
							<div class='controls'>
								<input class='input-block-level mod-width-fit-content' type='email' name='email' placeholder='Masukan email anda' required>
							</div>
						</div>	  
						<div class='control-group'>
							<label class='control-label'>Password <sup>*</sup></label>
							<div class='controls'>
								<input class='input-block-level mod-width-fit-content' type='password' name='password' placeholder='Password' required>
							</div>
						</div>
			
						<div class='control-group'>
							<div class='controls'>
								<input type='submit' name='submitAccount' value='Login' class='exclusive shopBtn btn btn-primary'>
								<p>*Jika Anda belum punya akun member silahkan daftar <a class='text-info' href='daftar-member.html'> Disini </a> </p>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>";
	
}

elseif ($_GET['module']=='editmember'){
	$edit=mysql_query("SELECT * FROM member WHERE id_member='$_SESSION[member_id]'");
	$r=mysql_fetch_assoc($edit);

	/* load api raja ongkir */
	require_once 'vendor/autoload.php';
	
	$htmls= [];
	
	$rows_provinsi = RajaOngkir\RajaOngkir::Provinsi()->all();
	$htmls['option_provinsi'][] = "<option value='' > -- Pilih Provinsi -- </option>";
	foreach ($rows_provinsi as $key => $value) {
	    $selected = ($value['province_id']==$r['provinsi'])? 'selected' : NULL ;
		$htmls['option_provinsi'][] = "<option value='{$value['province_id']}' {$selected}>{$value['province']}</option>";
	}
	$htmls['option_provinsi'] 	= implode('',$htmls['option_provinsi']);
	
	$rows_kota = RajaOngkir\RajaOngkir::Kota()->byProvinsi($r['provinsi'])->get();
	foreach ($rows_kota as $key => $value) {
	    $selected = ($value['city_id']==$r['kota'])? 'selected' : NULL ;
		$htmls['option_kota'][] = "<option value='{$value['city_id']}' {$selected}>{$value['city_name']}</option>";
	}
	$htmls['option_kota'] 	= implode('',$htmls['option_kota']);
	
	echo "
		<div class='span9'>
			<h4> Form Edit Member</h4>
			<form id='form2' action=edit_profil.php method=POST class='form-horizontal'>
				<input type=hidden name=id value='$r[id_member]'>
				<table class='table table-bordered'>
					<tr>
						<th> Detail Data Pribadi Anda : </th>
					</tr>

					<tr>
						<td>
							<div class='control-group'>
								<label class='control-label' for='inputFname'>Nama Lengkap <sup>*</sup></label>
								<div class='controls'>
									<input value='{$r['nama']}' type='text' class='input-block-level mod-width-fit-content' name='nama' id='inputFname' placeholder='Masukkan Nama Lengkap' required>
								</div>
							</div>
					
							<div class='control-group'>
								<label class='control-label' for='inputEmail'>Email <sup>*</sup></label>
								<div class='controls'>
									<input value='{$r['email']}' type='email' class='input-block-level mod-width-fit-content' name='email' placeholder='email@gmail.com' required>
								</div>
							</div>	  

							<div class='control-group'>
								<label class='control-label'>Password </label>
								<div class='controls'>
									<input type='password' class='input-block-level mod-width-fit-content' name='password' placeholder='**********' > <small class='text-info'>(Jika tidak diganti kosongkan saja)</small>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label' for='inputFname'>Nomor Telepon <sup>*</sup></label>
								<div class='controls'>
									<input value='{$r['no_telp']}' type='text' class='input-block-level mod-width-fit-content input-number-only' min='0' name='no_telp'  placeholder='08123456789' required>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label'>Provinsi <sup>*</sup></label>
								<div class='controls'>
									<select class='input-block-level mod-width-fit-content' name='provinsi' required>
										{$htmls['option_provinsi']}
									</select>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label'>Kota/Kabupaten <sup>*</sup></label>
								<div class='controls'>
									<select class='input-block-level mod-width-fit-content' name='kota' required>
										{$htmls['option_kota']}
									</select>
								</div>
							</div>
							
							<div class='control-group'>
								<label class='control-label' for='inputFname'>Kode Pos <sup>*</sup></label>
								<div class='controls'>
									<input value='{$r['kode_pos']}' type='text' class='input-block-level mod-width-fit-content input-number-only'  name='kode_pos'  placeholder='Kode Pos' required>
								</div>
							</div>

							<div class='control-group'>
								<label class='control-label' for='inputFname'>Alamat Lengkap <sup>*</sup></label>
								<div class='controls'>
									<textarea class='input-block-level' name='alamat' placeholder='Isi nama jalan, nomor rumah, nama gedung, dsb' required>{$r['alamat_member']}</textarea>
								</div>
							</div>
							<div class='control-group'>
								<div class='controls'>
									<input type='submit' name='submitAccount' value='Update' class='exclusive shopBtn btn btn-primary'>
								</div>
							</div>
                    	</td>
            		</tr>
            	</table>
        	</form>
		</div>";
}
/* ==================== END MENU MEMBER ==================== */

/*==================== Start Halaman Keranjang Belanja ====================*/
elseif ($_GET['module']=='keranjangbelanja'){
	$sid = session_id();
	$sql = mysql_query("SELECT * FROM keranjang, produk WHERE id_session='$sid' AND keranjang.id_produk=produk.id_produk");
	$ketemu=mysql_num_rows($sql);

	if($ketemu < 1){ # jika keranjang masih kosong
		echo "<script>window.alert('Keranjang Belanjan Anda Masih Kosong');window.location=('index.php')</script>";
	}
	
	else{ # jika keranjang tidak kosong
		$htmls= [];
		$no=1;
		while($r=mysql_fetch_array($sql)){
			$subtotalberat 	= $r['berat'] * $r['jumlah']; // total berat per item produk 
			$totalberat  	= $totalberat + $subtotalberat; // grand total berat all produk yang dibeli
			$harga1 		= $r['harga'];
			$subtotal    	= $harga1 * $r['jumlah'];
			$total       	= $total + $subtotal;  
			$subtotal_rp 	= format_rupiah($subtotal);
			$total_rp    	= format_rupiah($total);
			$harga       	= format_rupiah($harga1);

			$produk_attr = [];
			if ( $r['kondisi'] ) {
				$produk_attr[]= "<span class='label label-info'>Kondisi : {$r['kondisi']}</span>";
			}
			if ( $r['warna'] ) {
				$produk_attr[]= "<span class='label label-info'>Warna : {$r['warna']}</span>";
			}
			if ( $r['ukuran'] ) {
				$produk_attr[]= "<span class='label label-info'>Ukuran : {$r['ukuran']}</span>";
			}

			$produk_attr = implode('&nbsp',$produk_attr);

			$htmls['rows_barang'] .= "
				<tr>
					<td>
						<input type='hidden' name='id[$no]' value='{$r['id_keranjang']}'>
						<img src='foto_produk/small_{$r['gambar']}' alt='Image 01' />
					</td>
					<td>
						{$r['nama_produk']}
						<div style='display: inline-flex;width:100%;'>{$produk_attr}</div>
					</td>
					<td>
						<input style='width:5rem;' type=number name='jml[$no]' value='{$r['jumlah']}' size=1 min='1' onChange='this.form.submit()'>
					</td>
					<td>Rp.&nbsp;{$harga}</td>
					<td>Rp.&nbsp;{$subtotal_rp}</td>
					<td><a href='aksi.php?module=keranjang&act=hapus&id={$r['id_keranjang']}'>Hapus</a> </td>
				</tr>
			";
			$no++; 
		}

		$berat_gram	= $totalberat;


		echo"
			<div class='span9'>
				<h4> Keranjang Belanja</h4>
				<form method=post action=aksi.php?module=keranjang&act=update>
					<table class='table table-bordered'>
						<thead>
							<tr>
								<th>Gambar</th>
								<th>Nama Produk</th>
								<th>Jumlah</th>
								<th>Harga</th>
								<th>Sub Total</th>
								<th>Hapus</th>
							</tr>
						</thead>
						<tbody>
							{$htmls['rows_barang']}
							<tr>
								<td colspan='4' class='alignR'>Total:	</td>
								<td colspan='2' class='labelX label-primaryX'> Rp. {$total_rp}</td>
							</tr>
						</tbody>
					</table>
					<br/>
		
					<a href='semua-produk.html' class='shopBtn btn-large'><span class='icon-arrow-left'></span> Lanjutkan Belanja </a>
					<a href='selesai-belanja.html' class='shopBtn btn-large pull-right'>Checkout <span class='icon-arrow-right'></span></a>
				</form>
			</div>
		";
	}
}
/*==================== End Halaman Keranjang Belanja ====================*/

elseif ($_GET['module']=='selesaibelanja'){
	$edit	=mysql_query("SELECT * FROM member WHERE id_member='$_SESSION[member_id]'");
    $e		=mysql_fetch_array($edit);
	$sid 	= session_id();

	if (empty($_SESSION['namalengkap']) AND empty($_SESSION['passuser'])){
		echo "<script>window.alert('Anda belum Login, Silahkan Login Terlebih dahulu');
        window.location=('media.php?module=loginmember')</script>";
	}
	else {

		$data 			= [];
		$htmls 			= [];		

		/* start collect data order */
		function acak($panjang)
		{
			$karakter= '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$string = '';
			for ($i = 0; $i < $panjang; $i++) {
				$pos = rand(0, strlen($karakter)-1);
				$string .= $karakter{$pos};
			}
			return $string;
		}
		
		function ngacak($panj)
		{
			$karakter= '123456789';
			$string = '';
			for ($i = 0; $i < $panj; $i++) {
				$pos = rand(0, strlen($karakter)-1);
				$string .= $karakter{$pos};
			}
			return $string;
		}

		$data['sid'] 		= session_id(); /* get session_id() */
		$query = mysql_query("SELECT * FROM keranjang,produk WHERE keranjang.id_produk=produk.id_produk AND keranjang.id_session='{$data['sid']}'");
		if ( mysql_num_rows($query) > 0) {
			/* load rajaongkir rest api */
			require_once 'vendor/autoload.php';
			$kota = RajaOngkir\RajaOngkir::Kota()->find($_SESSION['kota']);
			
			$data['id_orders'] 	= acak(6); /* create id orders */
			$data['kode_unik'] 	= ngacak(3); /* create kode unik */
			
			$data['tbody_data_order'] = []; 		
			$data['fp_item'] = []; 		
			$data['total_berat'] = 0;
			$data['total_harga'] = 0;
			$no=1;

			while($p=mysql_fetch_assoc($query)){
				$data['fp_item'][] = "{$p['jumlah']} Item {$p['nama_produk']}"; 
				$data['total_berat'] += ($p['berat']*$p['jumlah']);
				$data['total_harga'] += ($p['harga']*$p['jumlah']);
	
				$produk_attr = [];
				if ( $p['kondisi'] ) {
					$produk_attr[]= "<span class='label label-info'>Kondisi : {$p['kondisi']}</span>";
				}
				if ( $p['warna'] ) {
					$produk_attr[]= "<span class='label label-info'>Warna : {$p['warna']}</span>";
				}
				if ( $p['ukuran'] ) {
					$produk_attr[]= "<span class='label label-info'>Ukuran : {$p['ukuran']}</span>";
				}
				$produk_attr = implode('&nbsp',$produk_attr);
	
				$data['tbody_data_order'][] = "
					<tr>
						<td>{$no}</td>
						<td>{$p['nama_produk']}<div style='display: inline-flex;width:100%;'>{$produk_attr}</div></td>
						<td>{$p['jumlah']}</td>
						<td>".($p['berat']*$p['jumlah'])."</td>
						<td>Rp.&nbsp;".format_rupiah($p['harga'])."</td>
						<td>Rp.&nbsp;".format_rupiah($p['harga']*$p['jumlah'])."</td>
					</tr>
				";
				$no++; 
			}
			$data['fp_item'] = implode(', ',$data['fp_item']);
			$data['tbody_data_order'] 	= implode('',$data['tbody_data_order']);
			$data['total_harga_rupiah'] = format_rupiah($data['total_harga']);
			$data['grand_total'] 		= $data['total_harga']+$data['kode_unik'];
			/* end collect data order */
	
			/* start ongkos kirim */
			$data['jne'] 	= RajaOngkir\RajaOngkir::Cost([
				'origin' 		=> 501, // id kota asal
				'destination' 	=> $_SESSION['kota'], // id kota tujuan
				'weight' 		=> $data['total_berat'], // berat satuan gram
				'courier' 		=> 'jne', // kode kurir pengantar ( jne / tiki / pos )
			])->get();
			$data['tiki'] 	= RajaOngkir\RajaOngkir::Cost([
				'origin' 		=> 501, // id kota asal
				'destination' 	=> $_SESSION['kota'], // id kota tujuan
				'weight' 		=> $data['total_berat'], // berat satuan gram
				'courier' 		=> 'tiki', // kode kurir pengantar ( jne / tiki / pos )
			])->get();
			$data['pos'] 	= RajaOngkir\RajaOngkir::Cost([
				'origin' 		=> 501, // id kota asal
				'destination' 	=> $_SESSION['kota'], // id kota tujuan
				'weight' 		=> $data['total_berat'], // berat satuan gram
				'courier' 		=> 'pos', // kode kurir pengantar ( jne / tiki / pos )
			])->get();
			$data['kurir'] = array_merge($data['jne'], $data['tiki'], $data['pos']);
			
			$htmls['option_kurir'] = [];
			foreach ($data['kurir'] as $key => $value) {
				$kurir= strtoupper($value['code']);
				foreach ($value['costs'] as $key_ => $value_) {
					if ( ($key==0) && ($key_==0) ) {
						$data['ongkos_kirim'] = $value_['cost'][0]['value'];
						$data['ongkos_kirim_rupiah'] = format_rupiah($value_['cost'][0]['value']);
					}
					$service= $value_['service'];
					$cost_value= format_rupiah($value_['cost'][0]['value']);
					$cost_etd= "({$value_['cost'][0]['etd']}";
					$cost_etd.= ($kurir=='POS') ? ')' : ' HARI)' ;
	
					$htmls['option_kurir'][] = "<option value='{$value_['cost'][0]['value']}'>{$kurir} {$service} Rp. {$cost_value} {$cost_etd}</option>";
				}
			}
			$htmls['option_kurir'] = implode('',$htmls['option_kurir']);
			/* end ongkos kirim */
			$data['grand_total'] += $data['ongkos_kirim'];
			$data['grand_total_rupiah'] = format_rupiah($data['grand_total']);
			
			/* START XENDIT PAYMENT */			
			$data['external_id'] = $data['id_orders'];
			$data['amount'] = $data['grand_total'];
			$data['payer_email'] = $_SESSION['email'];
			$data['description'] = "Pembayaran untuk {$data['fp_item']}";
			/* END XENDIT PAYMENT */

			// echo '<pre>';
			// print_r($data);
			// echo '</pre>';
			// $response = $xenditPHPClient->createInvoice($external_id, $amount, $payer_email, $description);
	
			echo"							
				<div class='span9'>
					<h3> Form Checkout</h3>	
					<table class='table table-bordered table-condensed'>
						<tr>
							<th> Data Order Anda : </th>
						</tr>
						<tr>
							<td>
								<table>
									<tr>
										<th>No</th>
										<th>Nama Produk</th>
										<th>Jumlah</th>
										<th>Berat (Gram)</th>
										<th>Harga</th>
										<th>Sub Total</th>
									</tr>
									{$data['tbody_data_order']}
									<tr>
										<td colspan='5' class='alignR'>Total:	</td>
										<td id='totalHarga' data-value='{$data['total_harga']}'>Rp.&nbsp;{$data['total_harga_rupiah']}</td>
									</tr>
									
									<tr>
										<td colspan='5' class='alignR'>Total Berat:	</td>
										<td id='totalBerat' data-value='{$data['total_berat']}'>{$data['total_berat']} (Gram)</td>
									</tr>
									<tr>
										<td colspan='5' class='alignR'>Total Ongkos Kirim:	</td>
										<td id='ongkosKirim' data-value='{$data['ongkos_kirim']}'>Rp.&nbsp;{$data['ongkos_kirim_rupiah']}</td>
									</tr>
									<tr>
										<td colspan='5' class='alignR'>Kode Unik:	</td>
										<td id='kodeUnik' data-value='{$data['kode_unik']}'>{$data['kode_unik']}</td>
									</tr>
									<tr>
										<td colspan='5' class='alignR'>Grand Total:	</td>
										<td id='grandTotal' data-value='{$data['grand_total']}'>Rp.&nbsp;{$data['grand_total_rupiah']}</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table class='table table-bordered'>
						<tr>
							<th> Alamat Penerima : <a class='btn btn-info btn-mini pull-right send-to-other-address'>Kirim ke alamat lain</a></th>
						</tr>
						<tr>
							<td id='alamatPengiriman'>
								<b>{$_SESSION['namalengkap']}</b><br>
								{$_SESSION['no_telp']} ({$_SESSION['email']})<br>
								{$_SESSION['alamat_member']}, {$kota['type']} {$kota['city_name']}, {$kota['province']} {$_SESSION['kode_pos']}
							</td>
						</tr>
						<tr>
							<td id='optionKurir'>
								<div class='control-group'>
									<label class='control-label' for='inputLname'>Pilih Pengiriman<sup>*</sup> : </label>
									<div class='controls'>
										<select class='input-block-level mod-width-fit-content' id='biaya' name='paket' required>{$htmls['option_kurir']}</select>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td id='paymentMethod'>
								<input type='hidden' name='kurir' value=''>
								<input type='hidden' id='idSession' name='id_session' value='{$data['sid']}'>
								<input type='hidden' id='idOrders' name='id_orders' value='{$data['id_orders']}'>
								<input type='hidden' id='idMember' name='id_orders' value='{$_SESSION['member_id']}'>

								<input type='hidden' id='xenditExternalId' name='external_id' value='{$data['external_id']}'>
								<input type='hidden' id='xenditPayerEmail' name='payer_email' value='{$data['payer_email']}'>
								<input type='hidden' id='xenditDescription' name='description' value='{$data['description']}'>
	
								<p>Silahkan lanjutkan proses pembayaran mengklik tombol di bawah ini<br />
								<form id='formFasapay'>
									<button type='button' class='btn btn-primary'>Bayar Sekarang</button>	
								</form>
							</td>
						</tr>
					</table>
				</div>
		
			";
			// echo '<pre>';
			// print_r();
			// echo '</pre>';
		} else {
			echo "<script>window.alert('Maaf keranjang masih kosong');
				window.location.assign(window.location.origin)</script>";
		}
	}
}
elseif ($_GET['module']=='datatransaksi'){
	/*
	* status data transaksi
	* Ada 5 status dalam transaksi ini diantaranya : 'belumbBayar','dikemas','dikirim','selesai','dibatalkan'
	*/
	session_start();
	$data = [];

	/* start generate data belum bayar */
	$tampil = mysql_query("SELECT *, orders.status AS status_mod FROM orders,member WHERE orders.id_member=member.id_member AND orders.id_member='$_SESSION[member_id]' AND orders.status='Unpaid' ORDER BY tanggal DESC ");
	while($r=mysql_fetch_assoc($tampil)){
		$r['alamat_pengiriman'] = strip_tags($r['alamat_pengiriman']);
		$r['tanggal'] 			= tgl_indo($r['tanggal']);
		if ( $r['status_mod'] ) {
			$r['status_mod'] 	= "<font color='red'>{$r['status_mod']}</font>"; 
		} else {
			$r['status_mod'] 	= "<font color='green'>{$r['status_mod']}</font>"; 
		}

		$data['belumBayar'][] = "
			<tr>
				<td align=center>{$r['id_orders']}</td>
				<td>{$r['tanggal']}</td>
				<td>{$r['jam']}</td>
				<td class='hidden'>{$r['status_mod']}</td>
				<td><a href=media.php?module=detailtransaksi&id={$r['id_orders']}>Detail</a></td>
			</tr>
		";
	}
	$data['belumBayar'] = implode('',$data['belumBayar']);
	$data['belumBayar'] = empty($data['belumBayar']) ? '<tr><td colspan="4" style="padding: 20px 20px 0px 20px;text-align: center;"><div class="alert alert-success">Belum ada pesanan</div></td></tr>' : $data['belumBayar'];
	/* end generate data belum bayar */

	/* start generate data dikemas */
	$tampil = mysql_query("SELECT *, orders.status AS status_mod FROM orders,member WHERE orders.id_member=member.id_member AND orders.id_member='$_SESSION[member_id]' AND orders.status='PAID' AND orders.status_order='dikemas' AND orders.no_resi IS NULL ORDER BY tanggal DESC ");
	while($r=mysql_fetch_assoc($tampil)){
		$r['alamat_pengiriman'] = strip_tags($r['alamat_pengiriman']);
		$r['tanggal'] 			= tgl_indo($r['tanggal']);
		if ( $r['status_mod'] ) {
			$r['status_mod'] 	= "<font color='red'>{$r['status_mod']}</font>"; 
		} else {
			$r['status_mod'] 	= "<font color='green'>{$r['status_mod']}</font>"; 
		}

		$data['dikemas'][] = "
			<tr>
				<td align=center>{$r['id_orders']}</td>
				<td>{$r['tanggal']}</td>
				<td>{$r['jam']}</td>
				<td class='hidden'>{$r['status_mod']}</td>
				<td><a href=media.php?module=detailtransaksi&id={$r['id_orders']}>Detail</a></td>
			</tr>
		";
	}
	$data['dikemas'] = implode('',$data['dikemas']);
	$data['dikemas'] = empty($data['dikemas']) ? '<tr><td colspan="4" style="padding: 20px 20px 0px 20px;text-align: center;"><div class="alert alert-success">Belum ada pesanan</div></td></tr>' : $data['dikemas'];
	/* end generate data dikemas */

	/* start generate data Dikirim */
	$tampil = mysql_query("SELECT *, orders.status AS status_mod FROM orders,member WHERE orders.id_member=member.id_member AND orders.id_member='$_SESSION[member_id]' AND orders.status='PAID' AND orders.status_order='dikirim' AND orders.no_resi IS NOT NULL ORDER BY tanggal DESC ");
	while($r=mysql_fetch_assoc($tampil)){
		$r['alamat_pengiriman'] = strip_tags($r['alamat_pengiriman']);
		$r['tanggal'] 			= tgl_indo($r['tanggal']);
		if ( $r['status_mod'] ) {
			$r['status_mod'] 	= "<font color='red'>{$r['status_mod']}</font>"; 
		} else {
			$r['status_mod'] 	= "<font color='green'>{$r['status_mod']}</font>"; 
		}

		$data['dikirim'][] = "
			<tr>
				<td align=center>{$r['id_orders']}</td>
				<td align=center>{$r['no_resi']}</td>
				<td align=center>{$r['kurir']}</td>
				<td>{$r['tanggal']}</td>
				<td>{$r['jam']}</td>
				<td class='hidden'>{$r['status_mod']}</td>
				<td>
					<a href='media.php?module=detailtransaksi&id={$r['id_orders']}' class='btn btn-inverse btn-mini'>Detail</a>
					<a href='media.php?module=konfirmasi-pesanan&id={$r['id_orders']}' class='btn btn-inverse btn-mini'>Konfirmasi</a>
				</td>
			</tr>
		";
	}
	$data['dikirim'] = implode('',$data['dikirim']);
	$data['dikirim'] = empty($data['dikirim']) ? '<tr><td colspan="6" style="padding: 20px 20px 0px 20px;text-align: center;"><div class="alert alert-success">Belum ada pesanan</div></td></tr>' : $data['dikirim'];
	/* end generate data Dikirim */

	/* start generate data Selesai */
	$tampil = mysql_query("SELECT *, orders.status AS status_mod FROM orders,member WHERE orders.id_member=member.id_member AND orders.id_member='$_SESSION[member_id]' AND orders.status='PAID' AND orders.status_order='selesai' AND orders.no_resi IS NOT NULL ORDER BY tanggal DESC ");
	while($r=mysql_fetch_assoc($tampil)){
		$r['alamat_pengiriman'] = strip_tags($r['alamat_pengiriman']);
		$r['tanggal'] 			= tgl_indo($r['tanggal']);
		if ( $r['status_mod'] ) {
			$r['status_mod'] 	= "<font color='red'>{$r['status_mod']}</font>"; 
		} else {
			$r['status_mod'] 	= "<font color='green'>{$r['status_mod']}</font>"; 
		}

		$data['selesai'][] = "
			<tr>
				<td align=center>{$r['id_orders']}</td>
				<td align=center>{$r['no_resi']}</td>
				<td align=center>{$r['kurir']}</td>
				<td>{$r['tanggal']}</td>
				<td>{$r['jam']}</td>
				<td class='hidden'>{$r['status_mod']}</td>
				<td>
					<a href='media.php?module=detailtransaksi&id={$r['id_orders']}' class='btn btn-inverse btn-mini'>Detail</a>
				</td>
			</tr>
		";
	}
	$data['selesai'] = implode('',$data['selesai']);
	$data['selesai'] = empty($data['selesai']) ? '<tr><td colspan="6" style="padding: 20px 20px 0px 20px;text-align: center;"><div class="alert alert-success">Belum ada pesanan</div></td></tr>' : $data['selesai'];
	/* end generate data Selesai */

	/* start generate data dibatalkan */
	$tampil = mysql_query("SELECT *, orders.status AS status_mod FROM orders,member WHERE orders.id_member=member.id_member AND orders.id_member='$_SESSION[member_id]' AND orders.status='EXPIRED' AND orders.status_order='dibatalkan' ORDER BY tanggal DESC ");
	while($r=mysql_fetch_assoc($tampil)){
		$r['alamat_pengiriman'] = strip_tags($r['alamat_pengiriman']);
		$r['tanggal'] 			= tgl_indo($r['tanggal']);
		if ( $r['status_mod'] ) {
			$r['status_mod'] 	= "<font color='red'>{$r['status_mod']}</font>"; 
		} else {
			$r['status_mod'] 	= "<font color='green'>{$r['status_mod']}</font>"; 
		}

		$data['dibatalkan'][] = "
			<tr>
				<td align=center>{$r['id_orders']}</td>
				<td>{$r['tanggal']}</td>
				<td>{$r['jam']}</td>
				<td class='hidden'>{$r['status_mod']}</td>
				<td><a href=media.php?module=detailtransaksi&id={$r['id_orders']}>Detail</a></td>
			</tr>
		";
	}
	$data['dibatalkan'] = implode('',$data['dibatalkan']);
	$data['dibatalkan'] = empty($data['dibatalkan']) ? '<tr><td colspan="4" style="padding: 20px 20px 0px 20px;text-align: center;"><div class="alert alert-success">Belum ada pesanan</div></td></tr>' : $data['dibatalkan'];
	/* end generate data dibatalkan */

	$htmls = '
		<h3> Riwayat Data Order Anda</h3>	
		<hr class="soft"/>
		<div class="tabbable">
			<!-- Only required for left/right tabs -->
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab">Belum Bayar</a></li>
				<li><a href="#tab2" data-toggle="tab">Dikemas</a></li>
				<li><a href="#tab3" data-toggle="tab">Dikirim</a></li>
				<li><a href="#tab4" data-toggle="tab">Selesai</a></li>
				<li><a href="#tab5" data-toggle="tab">Dibatalkan</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<p style="padding:20px;border:1px solid #ddd;border-radius:5px">
						<b>!info</b><br>
						untuk melakukan pembayaran silahkan pilih menu detail pada kolom aksi dibawah ini :
					</p>
					<table class="table table-bordered table-condensed">
						<thead>
							<tr bgcolor=#D3DCE3>
								<th>No.order</th>
								<th>Tgl. order</th>
								<th>Jam</th>
								<th class="hidden">Status</th>
								<th>Aksi</th>
							</tr>
						<tbody>
							'.$data['belumBayar'].'
						</tbody>
					</table>
				</div>

				<div class="tab-pane" id="tab2">
					<p style="padding:20px;border:1px solid #ddd;border-radius:5px">
						<b>!info</b><br>
						pesanan anda dalam proses pengemasan mohon menunggu, terimakasih.
					</p>
					<table class="table table-bordered table-condensed">
						<thead>
							<tr bgcolor=#D3DCE3>
								<th>No.order</th>
								<th>Tgl. order</th>
								<th>Jam</th>
								<th class="hidden">Status</th>
								<th>Aksi</th>
							</tr>
						<tbody>
							'.$data['dikemas'].'
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab3">
					<p style="padding:20px;border:1px solid #ddd;border-radius:5px">
						<b>!info</b><br>
						anda sudah bisa melacak pesanan dengan cara :<br>
						1. copy nomor resi pada kolom No Resi dibawah ini.<br>
						2. klik jasa pengiriman sesuai dengan kolom Kurir.<br>
						<a href="https://www.posindonesia.co.id/id/tracking" class="btn btn-inverse btn-mini" target="_blank">Lacak POS</a>
						<a href="https://www.tiki.id/id/tracking" class="btn btn-inverse btn-mini" target="_blank">Lacak TIKI</a>
						<a href="https://cekresi.com/" class="btn btn-inverse btn-mini" target="_blank">Lacak JNE</a><br>
						3. jika pesanan sudah diterima mohon untuk konfirmasi dengan cara memilih menu Konfirmasi pada kolom aksi di bawah ini.
					</p>
					<table class="table table-bordered table-condensed">
						<thead>
							<tr bgcolor=#D3DCE3>
								<th>No.order</th>
								<th>No Resi</th>
								<th>Kurir</th>
								<th>Tgl. order</th>
								<th>Jam</th>
								<th class="hidden">Status</th>
								<th>Aksi</th>
							</tr>
						<tbody>
							'.$data['dikirim'].'
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab4">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr bgcolor=#D3DCE3>
								<th>No.order</th>
								<th>No Resi</th>
								<th>Kurir</th>
								<th>Tgl. order</th>
								<th>Jam</th>
								<th class="hidden">Status</th>
								<th>Aksi</th>
							</tr>
						<tbody>
							'.$data['selesai'].'
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab5">
					<p style="padding:20px;border:1px solid #ddd;border-radius:5px">
						<b>!info</b><br>
						pesanan dibatalkan karena tidak melakukan pembayaran dalam waktu 1 jam, untuk lebih jelasnya bisa memilih menu Detail pada kolom aksi di bawah ini.
					</p>
					<table class="table table-bordered table-condensed">
						<thead>
							<tr bgcolor=#D3DCE3>
								<th>No.order</th>
								<th>Tgl. order</th>
								<th>Jam</th>
								<th class="hidden">Status</th>
								<th>Aksi</th>
							</tr>
						<tbody>
							'.$data['dibatalkan'].'
						</tbody>
					</table>
				</div>
			</div>
		</div>
	';
	echo $htmls;
	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';

}
elseif ($_GET['module']=='detailtransaksi'){
session_start();
$edit = mysql_query("SELECT * FROM orders WHERE id_orders='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
	$status=$r['status'];
    $tanggal=tgl_indo($r['tanggal']);
	$customer=mysql_query("select * from member where id_member='$r[id_member]'");
  $c=mysql_fetch_array($customer);
//   print_r($r);
  if ( $r['status']== 'Unpaid' ) {
	  $r['status'] .= "<a style='
	  color: darkgreen;
	  margin-left: 1rem;
	  background: aqua;
	  padding: 10px;
	  border-radius: 50%;
  ' href='{$r['invoice_url']}'>Bayar Sekarang</a>";
  }
  $trMod= "";
  if ( $r['status']== 'PAID' ) {
	include_once("libs/XenditPHPClient.php");

	// $options['secret_api_key'] = 'xnd_development_2l1SxCJvrhJAHbXdL1Rixrxia7Qd0ls6lUyZMnkm5FWgVD7aqYREGfbsrmFTgru1';
	$options['secret_api_key'] = 'xnd_production_sPdNCsqzXjCK9RIbjimBZyoMBHtB8hu95USuMkCEsIV6Djc5DUJpDWF3DPmCo';
  
	$xenditPHPClient = new XenditClient\XenditPHPClient($options);
  
	$invoice_id = $r['external_id'];
  
	$response = $xenditPHPClient->getInvoice($invoice_id);
	// echo '<pre>';
	// print_r($response['payment_channel']);
	// print_r($response['payment_channel']);
	// echo '</pre>';
    $newDate = date("d F Y & H:i:s", strtotime($response['paid_at']));
	$trMod .= "
		<tr>
			<td>Metode Pembayaran</td>
			<td>: {$response['payment_method']}</td>
		</tr>
		<tr>
			<td>Kode Bank</td>
			<td>: {$response['bank_code']}</td>
		</tr>
		<tr>
			<td>Tanggal Pembayaran</td>
			<td>: {$newDate}</td>
		</tr>
	";
	//   $r['status'] .= "<br>";
	//   $r['status'] .= $response;
  }
echo"							
<div class='span9'>
<h3> Riwayat Data Order Anda</h3>	
	<hr class='soft'/>
	<div class='well'>
	
	<table id='example1' class='table table-bordered table-striped'>
          <tr><td>No. Order</td>        <td> : $r[id_orders]</td></tr>
          <tr><td>Tgl. & Jam Order</td> <td> : $tanggal & $r[jam]</td></tr>
		  <tr><td>Status Order      </td><td>: {$r['status']}</td></tr>
		  {$trMod}
		 <tr><td>Alamat Pengiriman</td>        <td> : $r[alamat_pengiriman]</td></tr>
          </table>";
		
		// tampilkan rincian produk yang di order
  $sql2=mysql_query("SELECT * FROM orders_detail,produk 
                                 WHERE orders_detail.id_produk=produk.id_produk 
                                 AND id_orders='$_GET[id]'");
  
  echo "<table class='table table-bordered table-condensed'>
	
        <tr><td>Nama Produk</td><td>Berat(kg)</td><td>Jumlah</td><td>Harga Satuan</td><td>Sub Total</td></tr>";
  
  while($s=mysql_fetch_array($sql2)){
  $subtotalberat = $s[berat] * $s[jumlah]; // total berat per item produk 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all produk yang dibeli

    $harga1 = $s[harga];
	
   
   $subtotal    = $harga1 * $s[jumlah];
   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($harga1);

    echo "<tr><td>$s[nama_produk]</td><td align=center>$s[berat]</td><td align=center>$s[jumlah]</td><td>Rp. $harga</td><td>Rp. $subtotal_rp</td></tr>";
  }

$ongkoskirim = $r[ongkir];
$kode=$r[kode];
$grandtotal    = $total + $ongkoskirim; 
$grandtotal1    = $grandtotal + $kode;
$ongkoskirim_rp = format_rupiah($ongkoskirim);
$ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
$grandtotal_rp  = format_rupiah($grandtotal); 
$grandtotal1_rp  = format_rupiah($grandtotal1); 
    

echo "<tr><td colspan=4 align=right>Total              Rp. : </td><td align=right><b>$total_rp</b></td></tr>     
      <tr><td colspan=4 align=right>Total Berat            : </td><td align=right><b>$totalberat</b> Gram</td></tr>      
      <tr><td colspan=4 align=right>Total Ongkos Kirim Rp. : </td><td align=right><b>$ongkoskirim_rp</b></td></tr>
      <tr><td colspan=4 align=right>Kode Unik : </td><td align=right><b>$kode</b></td></tr>      
      <tr><td colspan=4 align=right>Grand Total        Rp. : </td><td align=right><b>$grandtotal1_rp</b></td></tr>
      </table>";
echo"	
</div>
							</div>";

}
elseif ( $_GET['module']=='konfirmasi-pesanan' ) {
	if ( $_SESSION['member_id'] ) {
		$data 								= [];
		$data['id_orders'] 					= $_GET['id'];
		$data['query_update_tabel_orders'] 	= "UPDATE `orders` SET `status_order`='selesai' WHERE id_orders='{$data['id_orders']}' ";
		$data['exec_update_tabel_orders'] 	= mysql_query($data['query_update_tabel_orders']);

		if ( $data['exec_update_tabel_orders'] ) { # update berhasil
			echo "<script>window.alert('terimakasih telah menyelesaikan pesanan, histori transaksi akan kami simpan pada halaman riwayat order menu Selesai');window.location=('media.php?module=datatransaksi')</script>";
		} else { # update gagal
			echo "<script>window.alert('konfirmasi pesanan gagal dilakukan.');window.location=('media.php?module=datatransaksi')</script>";
		}
		
	} else {
		echo "<script>window.alert('Anda belum Login, Silahkan Login Terlebih dahulu');
        window.location=('media.php?module=loginmember')</script>";
	}
}
elseif ($_GET['module']=='konfirmasipembayaran'){
	$htmls = [];
	$edit 		= mysql_query("SELECT * FROM orders WHERE id_orders='{$_GET[id]}'");
    $r    		= mysql_fetch_assoc($edit);
	$member 	= $r['id_member'];
    $tanggal	= tgl_indo($r['tanggal']);
	$customer	= mysql_query("select * from member where id_member='{$r['id_member']}'");
	$c			= mysql_fetch_assoc($customer);

	echo "							
		<div class='span9'>
			<div class='well well-small'>
				<h1>Data Order Anda <small class='pull-right'>  </small></h1>
				<hr class='soften'/>

				<table>
					<tr>
						<td>Nama Lengkap</td>
						<td> : <b>{$c['nama']} $member</b></td>
					</tr>
					<tr>
						<td>Alamat Pengiriman</td>
						<td> : {$r['alamat_pengiriman']}</td>
					</tr>
					<tr>
						<td>Telpon</td>
						<td> : {$c['no_telp']}</td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td> : {$c['email']}</td>
					</tr>
					<tr>
						<td>Bank Pembayaran</td>
						<td> : {$v['nama_bank']}</td>
					</tr>
				</table>
				<!-- /table data pemesan -->

				<hr/>
				<div>
					<span> Nomor Order : <b>{$_GET['id']}</b></span>
				</div>
				<hr>";

				$daftarproduk=mysql_query("SELECT * FROM orders_detail,produk WHERE orders_detail.id_produk=produk.id_produk AND id_orders='$_GET[id]'");
	  
				echo "  
	
				<table class='table table-bordered table-condensed'>
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Produk</th>
							<th>Jumlah</th>
							<th>Berat&nbsp(Gram)</th>
							<th>Harga</th>
							<th>Sub Total</th>
						</tr>
					</thead>
					<tbody>";

					// $pesan ="Terimakasih telah melakukan pemesanan online di gskonveksi.besaba.com <br /><br />  

					// Nama: $r[nama] <br />
					// Alamat: $r[alamat] <br/>
					// Telpon: $r[no_telp] <br /><hr />
					
					// Nomor Order: $id_orders <br />
					// Data order Anda adalah sebagai berikut: <br /><br />";

					$no=1;
					$total 				= 0;
					$total_berat 		= 0;
					$total_ongkos_kirim = $r['ongkir'];

					while ($d=mysql_fetch_assoc($daftarproduk)){
						// $subtotalberat 	= $d['berat'] * $d['jumlah']; // total berat per item produk 
						// $totalberat  	= $totalberat + $subtotalberat; // grand total berat all produk yang dibeli

						$berat 			= $d['berat'];
						$harga 			= $d['harga'];
						$subtotal    	= $harga * $d['jumlah'];

						$total       	+= $subtotal;
						$total_berat 	+= $berat;
						
						$rp = [
							'harga' 	=> format_rupiah($harga),
							'sub_total' => format_rupiah($subtotal)
						];    

						$produk_attr = [];
						if ( $d['kondisi'] ) {
							$produk_attr[]= "<span class='label label-info'>Kondisi : {$d['kondisi']}</span>";
						}
						if ( $d['warna'] ) {
							$produk_attr[]= "<span class='label label-info'>Warna : {$d['warna']}</span>";
						}
						if ( $d['ukuran'] ) {
							$produk_attr[]= "<span class='label label-info'>Ukuran : {$d['ukuran']}</span>";
						}
						$produk_attr = implode('&nbsp',$produk_attr);

						echo "
							<tr>
								<td>{$no}</td>
								<td>
									{$d['nama_produk']}
									<div style='display: inline-flex;width:100%;'>{$produk_attr}</div>
								</td>
								<td>{$d['jumlah']}</td>
								<td>{$berat}</td>
								<td>Rp.&nbsp;{$rp['harga']}</td>
								<td>Rp.&nbsp;{$rp['sub_total']}</td>
							</tr>
						";
									
						// $pesan .="$d[jumlah] $d[nama_produk] -> Rp. $harga -> Subtotal: Rp. $subtotal_rp <br />";
						$no++; 
					}

					$kode_unik 		= $r['kode'];
					$grand_total    = $total + $total_ongkos_kirim + $kode_unik;
					$result 		= [
						'total' => format_rupiah($total),
						'total_berat' => $total_berat,
						'total_ongkos_kirim' => format_rupiah($total_ongkos_kirim),
						'kode_unik' => $kode_unik,
						'grand_total' => format_rupiah($grand_total),
					];

				echo"				
					<tr>
						<td colspan='5' class='alignR'>Total:	</td>
						<td>Rp.&nbsp;{$result['total']}</td>
					</tr>
					
					<tr>
						<td colspan='5' class='alignR'>Total Berat:	</td>
						<td >{$result['total_berat']} (Gram)</td>
					</tr>
					<tr>
						<td colspan='5' class='alignR'>Total Ongkos Kirim:	</td>
						<td >Rp.&nbsp;{$result['total_ongkos_kirim']}</td>
					</tr>
					<tr>
						<td colspan='5' class='alignR'>Kode Unik:	</td>
						<td >{$result['kode_unik']}</td>
					</tr>
					<tr>
						<td colspan='5' class='alignR'>Grand Total:	</td>
						<td class='label label-primary'>Rp.&nbsp;{$result['grand_total']}</td>
					</tr>
					</tbody>
				</table><br/>
				
				<p>Silahkan lanjutkan proses pembayaran melalui Akun Fasapay Anda dengan mengklik tombol di bawah ini<br />
				<form id='form1' name='form1' target='_blank' method='post' action='https://sci.fasapay.com/'>
					<input type='hidden' name='fp_acc' value='FP498022'>
					<input type='hidden' name='fp_acc_from' value='' />
					<input type='hidden' name='fp_store' value='Taurus Computer Solution'>
					<input type='hidden' name='fp_item' value='Pembelian Produk Taurus Computer Solution'>
					<input type='hidden' name='fp_amnt' value='{$grand_total}'>
					<input type='hidden' name='fp_currency' value='IDR'>
					<input type='hidden' name='fp_comments' value='Pembayaran menggunakan store variable'>
					<input type='hidden' name='fp_merchant_ref' value='BL000001' />
					<!-- baggage fields -->
					<input type='hidden' name='track_id' value='558421222'>
					<input type='hidden' name='fp_fail_url' value='localhost/tes.php'>
					<input type='hidden' name='fp_fail_method' value='localhost/tes.php'>
					<input type='hidden' name='order_id' value='BJ2993800'>
					<input name='' type='submit' value='Bayar Dengan Fasapay' />
				</form>
			</div>
		</div>";
	

}
elseif ($_GET['module']=='hasilcari'){
echo"
<h4>Produk Terbaru </h4>
			  <ul class='thumbnails'>";
			  // Tampilkan 4 produk terbaru
  $kata = trim($_POST['kata']);
  // mencegah XSS
  $kata = htmlentities(htmlspecialchars($kata), ENT_QUOTES);
  // pisahkan kata per kalimat lalu hitung jumlah kata
  $pisah_kata = explode(" ",$kata);
  $jml_katakan = (integer)count($pisah_kata);
  $jml_kata = $jml_katakan-1;

  $cari = "SELECT * FROM produk WHERE " ;
    for ($i=0; $i<=$jml_kata; $i++){
      $cari .= "deskripsi LIKE '%$pisah_kata[$i]%' OR nama_produk LIKE '%$pisah_kata[$i]%' OR harga LIKE '%$pisah_kata[$i]%'";
      if ($i < $jml_kata ){
        $cari .= " OR ";
      }
    }
  $cari .= " ORDER BY id_produk DESC LIMIT 4";
  $hasil  = mysql_query($cari);
  $ketemu = mysql_num_rows($hasil);

  if ($ketemu > 0){
    echo "<p align=center>Ditemukan <b>$ketemu</b> produk dengan kata <font style='background-color:#00FFFF'><b>$kata</b></font> : </p>"; 
    while($r=mysql_fetch_array($hasil)){
	$harga1 = $r[harga];
    $harga     = number_format($harga1,0,",",".");
//		echo "<table><tr><td><span class=judul><a href=produk-$t[id_produk]-$t[produk_seo].html>$t[nama_produk]</a></span><br />";
      // Tampilkan hanya sebagian isi produk
      $isi_produk = htmlentities(strip_tags($r['deskripsi'])); // mengabaikan tag html
      $isi = substr($isi_produk,0,235); // ambil sebanyak 250 karakter
      $isi = substr($isi_produk,0,strrpos($isi," ")); // potong per spasi kalimat
	echo"
				<li class='span3'>
				  <div class='thumbnail'>
					<a  href='media.php?module=detailproduk&id=$r[id_produk]'><img src='foto_produk/medium_$r[gambar]' alt=''/></a>
					<div class='caption'>
					  <h5>$r[nama_produk]</h5>
					  <p> 
						<strong> Rp. $harga</strong> 
					  </p>
					 
					  <h4 style='text-align:center'><a class='btn' href='aksi.php?module=keranjang&act=tambah&id=$r[id_produk]'>Add to <i class='icon-shopping-cart'></i></a></h4>
					</div>
				  </div>
				</li>";
			}
			echo"
				
			  </ul>
			  ";
			  }
		  else{
    echo "<p align=center>Tidak ditemukan produk dengan kata <b>$kata</b></p>";
  }
}
?>