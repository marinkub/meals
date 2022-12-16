<?php
//require_once 'vendor/autoload.php';
?>

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


