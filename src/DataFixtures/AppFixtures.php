<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('ja_JP');

        //on créé les utilisateurs

        $users = []; //le tableau va nous aider à stocker les instances des users

        for($i = 1; $i <= 10; $i++)
        {
            $user = new User();
            $user->setUsername($faker->email);
            $manager->persist($user);
            $users[] = $user;
        }

        //on créé les catégories

        $categories = [];

        for($i = 1; $i <= 2; $i++)
        {
            $category = new Category();
            $category->setName($faker->colorName);
            $category->setSlug($this->slugger->slug($category->getName())->lower());
            $manager->persist($category);
            $categories[] = $category;
        }


        //on créé les produits
        for($i = 1; $i <= 100; $i++)
        {
            $product = new Product();
            $product->setName('iPhone X '.$i);
            $product->setDescription('Un iPhone de '.rand(2000, 2020));
            $product->setPrice(rand(10, 1000)*100);
//            $product->setSlug(str_replace(" ","-",'iPhone X '.$i ));
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setUser($users[rand(0,9)]);
            $product->setCategory($categories[rand(0,1)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
