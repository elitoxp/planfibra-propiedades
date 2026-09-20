<?php
declare(strict_types=1);header('Content-Type: application/json; charset=utf-8');header('X-Content-Type-Options: nosniff');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false,'error'=>'Método no permitido']);exit;}
if(!empty($_POST['website']??'')){echo json_encode(['ok'=>true]);exit;}
function clean(string $k,int $max):string{$v=trim((string)($_POST[$k]??''));$v=preg_replace('/[\x00-\x1F\x7F]/u','',$v)??'';return mb_substr($v,0,$max);}
$name=clean('name',80);$phone=clean('phone',25);$email=clean('email',120);$message=clean('message',600);$pid=clean('property_id',60);
if($name===''||$phone===''||$pid===''){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'Completa nombre, teléfono y propiedad']);exit;}
if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL)){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'Email inválido']);exit;}
$row=['id'=>bin2hex(random_bytes(8)),'created_at'=>date('c'),'property_id'=>$pid,'name'=>$name,'phone'=>$phone,'email'=>$email,'message'=>$message,'ip_hash'=>hash('sha256',($_SERVER['REMOTE_ADDR']??'').'casarey')];
$path=__DIR__.'/../data/leads.jsonl';$ok=file_put_contents($path,json_encode($row,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n",FILE_APPEND|LOCK_EX);
if($ok===false){http_response_code(500);echo json_encode(['ok'=>false,'error'=>'No se pudo guardar']);exit;}echo json_encode(['ok'=>true]);