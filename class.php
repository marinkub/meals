<?php
class Meal {
    public $id;
    public $title;
    public $description;
    public $category;
    public $tags;
    public $ingredients;

    public function set_meal($id, $title, $description, $category, $tags, $ingredients)
    {
        $this->id = $id; 
        $this->title = $title; 
        $this->description = $description; 
        $this->category = $category; 
        $this->tags = $tags; 
        $this->ingredients = $ingredients; 
    }
}

class Tag {
    public $id;
    public $title;
    public $slug;

    public function set_tag($id, $title, $slug)
    {
        $this->id = $id; 
        $this->title = $title; 
        $this->slug = $slug;
    }
}

class Category {
    public $id;
    public $title;
    public $slug;

    public function set_category($id, $title, $slug)
    {
        $this->id = $id; 
        $this->title = $title; 
        $this->slug = $slug;
    }
}

class Ingredient {
    public $id;
    public $title;
    public $slug;

    public function set_ingredient($id, $title, $slug)
    {
        $this->id = $id; 
        $this->title = $title; 
        $this->slug = $slug;
    }
}
?>