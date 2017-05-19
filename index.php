
<html>
<head>
<title>Sistem Informasi Geografis</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<style>
  body {
	padding-top: 60px;
  }
</style>
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap-alert.js"></script>

<!-- load googlemaps api dulu -->
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
	var peta;
	var gambar_tanda;
	gambar_tanda = 'assets/img/marker.png';
	
	function peta_awal(){
		// posisi default peta saat diload
	    var lokasibaru = new google.maps.LatLng(-7.7963142791241715,110.38017498925774);
    	var petaoption = {
			zoom: 13,
			center: lokasibaru,
			mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
	    peta = new google.maps.Map(document.getElementById("map_canvas"),petaoption);
	    
	    // ngasih fungsi marker buat generate koordinat latitude & longitude
	    tanda = new google.maps.Marker({
	        position: lokasibaru,
	        map: peta, 
	        icon: gambar_tanda,
	        draggable : true
	    });
	    
	    // ketika markernya didrag, koordinatnya langsung di selipin di textfield
	    google.maps.event.addListener(tanda, 'dragend', function(event){
				document.getElementById('latitude').value = this.getPosition().lat();
				document.getElementById('longitude').value = this.getPosition().lng();
		});
	}

	function setpeta(x,y,id){
		// mengambil koordinat dari database
		var lokasibaru = new google.maps.LatLng(x, y);
		var petaoption = {
			zoom: 15,
			center: lokasibaru,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		peta = new google.maps.Map(document.getElementById("map_canvas"),petaoption);
		 
		 // ngasih fungsi marker buat generate koordinat latitude & longitude
		tanda = new google.maps.Marker({
			position: lokasibaru,
			icon: gambar_tanda,
			draggable : true,
			map: peta
		});
		
		// ketika markernya didrag, koordinatnya langsung di selipin di textfield
		google.maps.event.addListener(tanda, 'dragend', function(event){
				document.getElementById('latitude').value = this.getPosition().lat();
				document.getElementById('longitude').value = this.getPosition().lng();
		});
	}
</script> 
</head>
<body onload="peta_awal()">
<div class="container">
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">SISTEM INFORMASI GEOGRAFIS</a>
          <div class="btn-group pull-right">
           
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<div class="row">
	
	<form action="?action=add" method="POST"> 
	<div class="span4">
	<div class="control-group">
	  <label class="control-label" for="input01"><h3>Kampus</h3></label>
	  <div class="controls">
		<input type="text" class="input-xlarge" id="nama_kampus" name="nama_kampus" rel="popover" data-content="Masukkan nama kampus." data-original-title="kampus">
	  </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"><h3>Longitude</h3></label>
		  <div class="controls">
			<input type="text" class="input-xlarge" id="longitude" name="longitude" >
		  </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"><h3>Latitude</h3></label>
		  <div class="controls">
			<input type="text" class="input-xlarge" id="latitude" name="latitude">
		  </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"></label>
		  <div class="controls">
		   <button type="submit" class="btn btn-success">Tambah kampus</button>
	
		  </div>
	</div>
	</form>
	
	<div class="control-group">
		
		  <div class="controls">
		  <div id="daftar">
		  <div id="outtable">
  <table>
  	<thead>
  		<tr>
  			<th class="short">No</th>
  			<th class="normal">Nama Kampus</th>
  			<th class="delete">Hapus</th>
  		</tr>
  	</thead>
  	<tbody>

		  <?php
		  require ('config.php');
		  // mengambil data dari database
		  $lokasi = mysql_query("select * from `kampus`");
			$i=0;
			while($l=mysql_fetch_array($lokasi)){
				// membuat fungsi javascript untuk nantinya diolah dan ditampilkan dalam peta
				$i++;
				echo "
				
					<tr>
						<td>$i</td>
						<td><a href=\"javascript:setpeta(".$l['lat'].",".$l['long'].",".$l['id'].")\">".$l['nama_kampus']."</a> </td>
						<td><a href='?action=remove&id=".$l['id']."'>Hapus</a></td>
					</tr>									
			
				";
			}		
		  ?>
				</tbody>
			  </table>
			</div>
		  </div>
		</div>
	</div>		
</div>
<div class="span8">
	<div class="control-group">
	 <div id="map_canvas" style="width:100%; height:500px"></div>
	</div>
</div>


</div>
<hr/>
	  <footer>
        <p>&copy; Kelompok GIS 2017</p>
      </footer>
	  <hr/>
<br/>  <br/>  
<?php

if ($_GET['action'] == "add") {
	
	require ('config.php');
	$nama_kampus	= htmlentities(mysql_real_escape_string($_POST['nama_kampus']));
	$longitude		= htmlentities(mysql_real_escape_string($_POST['longitude']));
	$latitude		= htmlentities(mysql_real_escape_string($_POST['latitude']));
	
	// input data ke database
	$input_kampus = mysql_query("insert into `kampus` (`nama_kampus`,`lat`,`long`) values ('$nama_kampus`','$latitude','$longitude')");
	
	if ($input_kampus) {
		?>
		<script language="javascript">
		document.location="?success=1";
		</script>
		<?php
	} else {
		?>
			<script language="javascript">
			document.location="?success=0";
			</script>
		<?php
	}
	
} elseif ($_GET['action'] == "remove") {
	$id = htmlentities(mysql_real_escape_string($_GET['id']));
	// hapus data dari database
	$hapus_kampus = mysql_query("DELETE FROM `kampus` WHERE `id` = '".$id."'");
	
	if ($hapus_kampus) {
		?>
		<script language="javascript">
		document.location="?remove=1";
		</script>
		<?php
	} else {
		?>
			<script language="javascript">
			document.location="?remove=0";
			</script>
		<?php
	}
}
?>
</div>
</body>
</html>

<style type="text/css">
	  #outtable{
	  	padding: 20px;
	  	border:1px solid #e3e3e3;
	  	width:320px;
	  	border-radius: 5px;
	  }
	  .short{
	  	width: 20px;
	  }
	  .normal{
	  	width: 140px;
	  }
	  .delete{
	  	width: 80px;
	  }
      table{
      	border-collapse: collapse;
      	font-family: arial;
		font-size: 14px;
      	color:#5E5B5C;
      }
      thead th{
      	text-align: left;
      	padding: 5px;
      }
      tbody td{
      	border-top: 1px solid #e3e3e3;
      	padding: 7px;
      }
      tbody tr:nth-child(even){
      	background: #F6F5FA;
      }
      tbody tr:hover{
      	background: #EAE9F5
      }
	</style>