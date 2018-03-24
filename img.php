<?php

// SE ARQUIVO EXISTE E SE TEM PERMISSÃO
$folder = 'imgs';

$file = 'image.jpg';
$name = 'Image.jpg';
$mine = 'image/jpg';

if(is_file($folder.'/'.$file)){

	// RECUPERA HEADERS
	$headers = getallheaders();

	$lastModified 	= filemtime($folder.'/'.$file);
	$etagFile 		= md5_file($folder.'/'.$file);
	$filesize 		= filesize($folder.'/'.$file);

	// EXECUÇÃO INFINITA
	set_time_limit(0);

	// EVITA TRAVAMENTO DE NAVEGAÇÃO ENQUANTO BAIXA
	session_write_close();

	// FORMA: INLINE OU DOWNLOAD
	$disposition = 'attachment';
	if(isset($_GET['inline'])){
		$disposition = 'inline';
	}

	// SEGUNDA VEZ, MANTER EM CACHE
	if($disposition == 'inline' and (!isset($headers['Cache-Control']) or $headers['Cache-Control'] == 'max-age=0')){

		header("HTTP/1.1 304 Not Modified");
		header('Cache-Control: max-age=2592000');

	// PRIMEIRA VEZ OU DOWNLOAD DO ARQUIVO
	}else{
		header('Cache-Control: max-age=0');
	}

	// NORMAL HEADER
	header('Content-Transfer-Encoding: Binary');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModified).' GMT');
	header('Content-Type: '.$mine.';');
	header('Content-Length: '.$filesize);
	header('Etag: '.$etagFile);
	header('Content-disposition: '.$disposition.'; filename="'.utf8_encode($name).'"');

	// DOWNLOAD BY BUFFER
	$fd = fopen ($folder.'/'.$file, 'rb');
	while(!feof($fd)) {
		$buffer = fread($fd, 2048);
		echo $buffer;
	}
	fclose ($fd);

}
