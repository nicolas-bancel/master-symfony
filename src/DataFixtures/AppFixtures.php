<?php

namespace App\DataFixtures;

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

        //on crée les utilisateurs

        $users = []; //le tableau va nous aider à stocker les instances des users

        for($i = 1; $i <= 10; $i++)
        {
            $user = new User();
            $user->setUsername($faker->email);
            $manager->persist($user);
            $users[] = $user;
        }

        //on crée les produits
        for($i = 1; $i <= 100; $i++)
        {
            $product = new Product();
            $product->setName('iPhone X '.$i);
            $product->setDescription('Un iPhone de '.rand(2000, 2020));
            $product->setPrice(rand(10, 1000)*100);
//            $product->setSlug(str_replace(" ","-",'iPhone X '.$i ));
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setUser($users[rand(0,9)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
