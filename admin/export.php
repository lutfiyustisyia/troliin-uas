<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $produsen = $_POST['produsen'];
   $produsen = filter_var($produsen, FILTER_SANITIZE_STRING);
   $price_before = $_POST['price_before'];
   $price_before = filter_var($price_before, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $page_url = strtolower($name);
   $page_url = '/'.str_replace(' ','-',$page_url);

   $time = date('d-M-Y h:i:sa');

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, produsen, price_before, details, price, image_01, image_02, image_03, page_url, tgl_waktu) VALUES(?,?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $produsen, $price_before, $details, $price, $image_01, $image_02, $image_03, $page_url, $time]);

      if($insert_products){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Export Data</title>
  <link rel="shortcut icon" href="logo.png" type="image/x-icon">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <link rel="stylesheet" href="../css/admin_style.css">

  <!-- clear confirm form resubmission -->
  <script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
  </script>

</head>

<body>

  <?php include '../components/admin_header.php'; ?>

  <section class="add-products">

    <h1 class="heading">export data</h1>
    

  </section>

  <section class="show-products">

    <h1 class="heading">products</h1>

    <div class="box-container">

      <?php
      $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id DESC");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
      <div class="box">
        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
        <div class="name"><?= $fetch_products['name']; ?></div>
        <div class="produsen">by : <?= $fetch_products['produsen']; ?></div>
        <div class="price-before" style="text-decoration: line-through;"> Rp
          <span><?= number_format($fetch_products['price_before']) ; ?></span>,-
        </div>
        <div class="price"> Rp <span><?= number_format($fetch_products['price']) ; ?></span>,-</div>
        <div class="details"><textarea name="details" id="" cols="30"
            rows="5"><?= $fetch_products['details']; ?></textarea></div>
        <div class="flex-btn">
          <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
          <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
            onclick="return confirm('delete this product?');">delete</a>
        </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

    </div>

  </section>








  <script src="../js/admin_script.js"></script>

</body>

</html>