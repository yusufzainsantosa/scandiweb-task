<?php  
namespace Src;

$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if (
    !empty($data->product_sku) &&
    !empty($data->product_name) &&
    !empty($data->product_price) &&
    !empty($data->product_type) &&
    (
      !empty($data->product_size) ||
      !empty($data->product_weight) ||
      (
        !empty($data->product_height) &&
        !empty($data->product_width) &&
        !empty($data->product_length)
      )
    )
) {
  
    // set product property values
    $product->product_sku = $data->product_sku;
    $product->product_name = $data->product_name;
    $product->product_price = $data->product_price;
    $product->product_type = $data->product_type;
    $product->created_at = date('Y-m-d H:i:s');
    if ($data->product_type == 'DVD') {
      $product->product_size = $data->product_size;
    } else if ($data->product_type == 'Book') {
      $product->product_weight = $data->product_weight;
    } else if ($data->product_type == 'Furniture') {
      $product->product_height = $data->product_height;
      $product->product_width = $data->product_width;
      $product->product_length = $data->product_length;      
    }
  
    // create the product
    if($product->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Product was created."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }
}
  
// tell the user data is incomplete
else{  
  $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
  $response['body'] = 'Unable to create product. Data is incomplete.';
  return $response;
}
?>