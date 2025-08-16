<?php
require_once("db.php");
require_once("ManagerV2.php");
Manager::setConnection($conn);


class Category extends Model {
    function __construct() {
        parent::__construct("demo_category");
    }

    function products() {
        return $this->hasMany(Product::class, "category"); 
    }
}

class Product extends Model {
    function __construct() {
        parent::__construct("demo_product");
    }

    function category() {
        return $this->belongsTo(Category::class, "category", "id"); 
    }
}


?>