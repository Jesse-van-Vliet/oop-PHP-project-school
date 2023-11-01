<?php

namespace Oopproj;

class Game
{
    private Easy|Medium|Hard $word;

   public function __construct( Easy|Medium|Hard $word)
   {
       $this->word = $word;
   }



}