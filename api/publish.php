<?php
declare(strict_types=1);header('Content-Type: application/json; charset=utf-8');header('X-Content-Type-Options: nosniff');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false,'error'=>'Método no permitido']);exit;}
if(!empty($_POST['website']??'')){echo json_encode(['ok'=>true]);exit;}
function c(string $k,int $max):string{$v=trim((string)($_POST[$k]??''));$v=preg_replace('/[\x00-\x1F\x7F]/u','',$v)??'';return mb_substr($v,0,$max);}
$required=['name','phone','operation','type','commune','price','title'];foreach($required as $k){if(c($k,200)===''){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'Faltan datos obligatorios']);exit;}}
$email=c('email',120);if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL)){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'Email inválido']);exit;}
$row=['id'=>'PEND-'.strtoupper(bin2hex(random_bytes(5))),'created_at'=>date('c'),'status'=>'pending','publisher_type'=>c('publisher_type',30),'name'=>c('name',80),'phone'=>c('phone',25),'email'=>$email,'operation'=>c('operation',20),'type'=>c('type',30),'commune'=>c('commune',80),'region'=>c('region',80),'price'=>c('price',30),'currency'=>c('currency',8),'title'=>c('title',120),'address'=>c('address',180),'area'=>c('area',20),'bedrooms'=>c('bedrooms',10),'bathrooms'=>c('bathrooms',10),'description'=>c('description',1800),'terms'=>!empty($_POST['terms'])];
if(!$row['terms']){http_response_code(422);echo json_encode(['ok'=>false,'error'=>'Debes aceptar las condiciones']);exit;}
$path=__DIR__.'/../data/pending-listings.jsonl';$ok=file_put_contents($path,json_encode($row,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)."\n",FILE_APPEND|LOCK_EX);
if($ok===false){http_response_code(500);echo json_encode(['ok'=>false,'error'=>'No se pudo guardar']);exit;}echo json_encode(['ok'=>true,'id'=>$row['id']]);