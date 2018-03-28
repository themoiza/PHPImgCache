<?php

// SE ARQUIVO EXISTE E SE TEM PERMISSÃO
$folder = 'imgs';

$file = 'image.jpg';
$name = 'Image.jpg';
$mine = 'image/jpg';

if(is_file($pastaDrive.'/'.$file['dfi_nome'])){

	$lastModified = filemtime($pastaDrive.'/'.$file['dfi_nome']);
	$etagFile = md5_file($pastaDrive.'/'.$file['dfi_nome']);

	// EXECUÇÃO INFINITA
	set_time_limit(0);

	// EVITA TRAVAMENTO DE NAVEGAÇÃO ENQUANTO BAIXA
	session_write_close();

	// FORMA: INLINE OU DOWNLOAD
	$disposition = 'inline';
	if(isset($_GET['inline'])){
		$disposition = 'inline';
	}
	if(isset($_GET['download'])){
		$disposition = 'attachment';
	}

	// DOWNLOAD
	if($disposition == 'attachment'){

		header('Cache-Control: no-cache');

	}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){

		header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
		header('Cache-Control: private, max-age=2592000');
		exit;

	}else{

		header('Cache-Control: private, max-age=2592000');

	}

	// NORMAL HEADER
	header('Content-Disposition: '.$disposition.'; filename="'.utf8_encode($file['dfi_nome_real']).'"');
	header('Content-Length:'.$file['dfi_tamanho']);
	header('Content-Transfer-Encoding: Binary');
	header('Content-Type: '.$file['dfi_mime'].';');
	header('Etag: '.$etagFile);
	header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModified).' GMT');

	// DOWNLOAD BY BUFFER
	$fd = fopen ($pastaDrive.'/'.$file['dfi_nome'], 'rb');
	while(!feof($fd)) {
		$buffer = fread($fd, 2048);
		echo $buffer;
	}
	fclose ($fd);
}
