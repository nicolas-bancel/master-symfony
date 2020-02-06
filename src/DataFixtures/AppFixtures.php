<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('ja_JP');

        //on crée les tags

        for($i = 1; $i <= 10; $i++)
        {
            $tag = new Tag();
            $tag->setName('Tag '.$i);
            $manager->persist($tag);
        }

        //on créé les utilisateurs

        $users = []; //le tableau va nous aider à stocker les instances des users

        for($i = 1; $i <= 10; $i++)
        {
            $email = (1 === $i) ? 'bancelnicolas@gmail.com' : $faker->email;
            $roles = (1 === $i) ? ['ROLE_ADMIN'] : ['ROLE_USER'];

            $user = new User();
            $user->setUsername($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
            $user->setRoles($roles);
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
