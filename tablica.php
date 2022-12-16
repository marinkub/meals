<html>
<body>
<form action="tablica.php" method="GET">
    <label for="lang">Jezik</label>
    <select name="lang">
        <option value="en">EN</option>
        <option value="hr">HR</option>
    </select>
    <label for="per_page">Prikaz po stranici  </label><input type="number" name="per_page"/>
    <label for="pare">Broj stranice  </label><input type="number" name="page"/>
    <label for="category">Kategorija  </label><select name="category">
        <option value=""></option>
        <option value="category-1">1</option>
        <option value="category-2">2</option>
        <option value="NULL">Bez kategoije</option>
    </select>
    <label for="tag">Oznaka  </label><select name="tag">
        <option value=""></option>
        <option value="tag-1">1</option>
        <option value="tag-2">2</option>
    </select>
    <input type="submit">
</form>
<?php 
require_once 'vendor/autoload.php'; 
require_once 'class.php';

$faker = Faker\Factory::create();

$faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
$faker->seed(10001);

$tags = array();
for ($i = 0; $i < 2; $i++)
{
    $tag = new Tag();
    $tag_id = $i + 1;
    $tag_title = "Tag ". $tag_id;
    $tag_slug = "tag-". $tag_id;
    $tag->set_tag($tag_id, $tag_title, $tag_slug);
    array_push($tags, $tag);
}

$ingredients = array();
for ($i = 0; $i < 10; $i++)
{
    $ingredient = new Ingredient();
    $ing_id = $i + 1;
    $ing_tilte = $faker->unique()->meatName();
    $ing_slug = "ingredient-". $ing_id;
    $ingredient ->set_ingredient($ing_id, $ing_tilte, $ing_slug);
    array_push($ingredients, $ingredient);
}

$categories = array();
for ($i = 0; $i < 2; $i++)
{
    $category = new Category();
    $cat_id = $i + 1;
    $cat_title = "Category ". $cat_id;
    $cat_slug = "category-". $cat_id;
    $category->set_category($cat_id, $cat_title, $cat_slug);
    array_push($categories, $category);
}

$meals = array();
$br_tags = count($tags) - 1;
$br_ingredients = count($ingredients) - 1;
$br_categories = count($categories);
for ($i = 0; $i < 15; $i++)
{
    $meal = new Meal();
    $category = new Category();
    $rand_ingredient = rand(0, $br_ingredients);
    $rand_tag = rand(0, $br_tags);
    $rand_cat = rand(0, $br_categories);
    $id = $i + 1;
    if(isset($categories[$rand_cat]))
    {
    $meal->set_meal($id, $faker->foodName(), $faker->realText(), $categories[$rand_cat], $tags[$rand_tag], $ingredients[$rand_ingredient]);
    }
    if(!isset($categories[$rand_cat]))
    {
    $meal->set_meal($id, $faker->foodName(), $faker->realText(), null, $tags[$rand_tag], $ingredients[$rand_ingredient]);
    }
    array_push($meals, $meal);
}


if($_GET["per_page"] != "" && $_GET["page"] != "")
{
    
    if (!empty($_GET["category"]))
    {
        unset($fliter);
        $fliter = array();
        if($_GET["category"] == "NULL")
        {
            for($i = 0; $i < count($meals); $i++)
            {
                if(is_null($meals[$i]->category))
                {
                        array_push($fliter, $meals[$i]);
                }
            }
        }
        else{
            for($i = 0; $i < count($meals); $i++)
            {
                if(!is_null($meals[$i]->category))
                {
                    if($_GET["category"] == $meals[$i]->category->slug)
                    {
                        array_push($fliter, $meals[$i]);
                    }
                }
            }
        }
        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
        $limit = $_GET["per_page"];
        $offset = ($page - 1) * $limit; 
        $total_items = count($fliter);
        $total_pages = ceil($total_items / $limit);
        $final = array_splice($fliter, $offset, $limit); 
    }
    if (!empty($_GET["tag"]))
    {
        unset($fliter);
        $fliter = array();
        for($i = 0; $i < count($meals); $i++)
        {
            if($_GET["tag"] == $meals[$i]->tags->slug)
            {
                array_push($fliter, $meals[$i]);
            }
        }
        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
        $limit = $_GET["per_page"];
        $offset = ($page - 1) * $limit; 
        $total_items = count($fliter);
        $total_pages = ceil($total_items / $limit);
        $final = array_splice($fliter, $offset, $limit); 
    }
    if (!empty($_GET["category"]) && !empty($_GET["tag"]))
    {
        unset($fliter);
        $fliter = array();
     
        if($_GET["category"] == "NULL")
        {
            for($i = 0; $i < count($meals); $i++)
            {
                if(is_null($meals[$i]->category) && $_GET["tag"] == $meals[$i]->tags->slug)
                {
                    array_push($fliter, $meals[$i]);  
                }
            }
        }
        else{
            for($i = 0; $i < count($meals); $i++)
            {
                if(!is_null($meals[$i]->category))
                {
                    if($_GET["category"] == $meals[$i]->category->slug && $_GET["tag"] == $meals[$i]->tags->slug)
                    {
                        array_push($fliter, $meals[$i]);
                    }
                }
            }
        }

        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
        $limit = $_GET["per_page"];
        $offset = ($page - 1) * $limit; 
        $total_items = count($fliter);
        $total_pages = ceil($total_items / $limit);
        $final = array_splice($fliter, $offset, $limit);
    }
    if (empty($_GET["category"]) && empty($_GET["tag"]))
    {
        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
        $limit = $_GET["per_page"];
        $offset = ($page - 1) * $limit; 
        $total_items = count($meals);
        $total_pages = ceil($total_items / $limit);
        $final = array_splice($meals, $offset, $limit); 
    }

if($_GET["page"] <= $total_pages){
for($x = 1; $x <= $total_pages; $x++): ?>
    <a href='?lang=<?php echo $_GET["lang"] ?>&per_page=<?php echo $_GET["per_page"] ?>&page=<?php echo $x; ?>&category=<?php echo $_GET["category"] ?>&tag=<?php echo $_GET["tag"] ?>'><?php echo $x; ?></a>
<?php endfor; ?>
<table border="1" cellpadding="10">
<?php
if($_GET["lang"] == "en")
{
 echo '<tr><th>ID</th><th>TITLE</th><th>DESCRIPTION</th><th>CATEGORY</th><th>TAGS</th><th>INGREDIENTS</th></tr>';
}
if($_GET["lang"] == "hr")
{
 echo '<tr><th>ID</th><th>NASLOV</th><th>OPIS</th><th>KATEGORIJA</th><th>OZNAKA</th><th>SASTOJCI</th></tr>';
}
     for ($i = 0; $i < count($final); $i++)
    {
        if ($_GET["lang"] == "hr")
        {
            echo '<tr>';
            echo '<td>'.$final[$i]->id .'</td>';
            echo '<td>'.translate($final[$i]->title, "en", "hr") .'</td>';
            echo '<td>'.translate($final[$i]->description, "en", "hr") .'</td>';
            if (is_null($final[$i]->category)){
                echo '<td></td>';
            }
            else{
                echo '<td>'.translate($final[$i]->category->title , "en", "hr") .'</td>';
            }
            echo '<td>'.translate($final[$i]->tags->title, "en", "hr") .'</td>';
            echo '<td>'.translate($final[$i]->ingredients->title, "en", "hr") .'</td>';
            echo '</tr>';
        }
        if ($_GET["lang"] == "en")
        {
            echo '<tr>';
            echo '<td>'.$final[$i]->id .'</td>';
            echo '<td>'.$final[$i]->title .'</td>';
            echo '<td>'.$final[$i]->description .'</td>';
            if (is_null($final[$i]->category)){
                echo '<td></td>';
            }
            else{
                echo '<td>'.$final[$i]->category->title .'</td>';
            }
            echo '<td>'.$final[$i]->tags->title.'</td>';
            echo '<td>'.$final[$i]->ingredients->title.'</td>';
            echo '</tr>';
        }
        
    }
    }else{echo 'Broj stranice koji ste unjeli ne postoji';}
}else{
    echo 'Niste popunili polje za prikaz po stranici i polje za stranicu';
}
?>
</table>


<?php

function translate($q, $sl, $tl){
    $res= file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
    $res=json_decode($res);
    return $res[0][0][0];
}?>
</body>
</html>