<?php   

namespace Frontend\Repositories;

use Frontend\Category;

class CategoryRepository
{
   public function show($id)
   {
       return Category::find($id);
   }
}

