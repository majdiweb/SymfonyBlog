<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Bulletin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BulletinFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $bulletins = [];
        $categories=['general', 'divers', 'urgent'];
        $tagNames =['PHP', 'Symfony', 'Doctrine', 'Twig', 'MVC', 'Info', 'Composer', 'Symfony (CLI)', 'JavaScript', 'C++'];
        for($i=0;$i<50;$i++)
        {
            $bulletin = new Bulletin("Bulletin Fixtures", "Divers");
            $bulletin->setCategory($categories[rand(0, count($categories)-1)]);
            array_push($bulletins, $bulletin);

            $manager->persist($bulletin);
        }
        foreach($tagNames as $tagName)
        {
            $tag = new Tag;
            $tag->setName($tagName);
            foreach($bulletins as $bulletin)
            {
                if(rand(0,100) > 80)
                {
                    $tag->addBulletin($bulletin);
                }
            }
            $manager->persist($tag);
        }
        $manager->flush();
    }
}
