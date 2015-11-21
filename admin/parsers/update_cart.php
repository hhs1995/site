<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/site/core/init.php';
$mode = sanitize($_POST['mode']);
$edit_id = sanitize($_POST['edit_id']);
$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['items'],true);
$updated_items = array();
$domain = (($_SERVER['HTTP_HOST']  != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

if($mode=='removeone'){
    foreach($items as $item){
        if($item['id']==$edit_id){
            $item['quantity'] = $item['quantity'] - 1;
        }
        
        if($item['quantity'] > 0){
         $updated_items = $item;   
        }
    }
}

if($mode=='addone'){
    foreach($items as $item){
        if($item['id']==$edit_id){
            $item['quantity'] = $item['quantity'] + 1;
        }
        
        $updated_items = $item;  
    }
}

if(isset($updated_items['id']) || !empty($updated_items) ){
    $json_updated = json_encode($updated_items);
    $db->query("UPDATE cart SET items = '{$json_updated}' WHERE id = '{$cart_id}'");
    $_SESSION['success_flash'] = 'Your shopping cart has been updated!';
}

if(!isset($updated_items['id']) && !isset($updated_items['items']) ){   
    $db->query("DELETE FROM cart WHERE id='{$cart_id}'");
    setcookie(CART_COOKIE,'',1,"/",false,false); //first false should be $domain when deployed
}

?>