<?php

namespace Fixtures\DataFixtures\Prod;

use Admin\Entity\CmsPage;
use Admin\Entity\TitleContent;
use Core\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCmsPages extends Fixture implements OrderedFixtureInterface
{
    
    public function getOrder()
    {
        return 50;
    }

    public function load(ObjectManager $manager)
    {
        
        $tContents = [];
        $tImages = [];
    
        foreach (range(0, 3) as $i) {
            $tContents[] = (new TitleContent())->setBody(
               "<div><h1>Demo Content".$i."</h1>".$this->getRandomBody(
               )."</div>"
            )->setName($this->getRandomName());
        
            $tImages[] = (new Image())->setImage('title-content-'.$i.'.png');
        }
        
        foreach (range(0, 3) as $i) {
            $cmsPage = new CmsPage();
            $cmsPage->setIsActive((rand(1, 1000) % 10) < 7);
            $cmsPage->setName($this->getRandomName());
            $cmsPage->setDescription($this->getRandomBody());
            $cmsPage->setImage('image'.($i % 10).'.jpg');
            $cmsPage->setImages([$tImages[1]]);
            $cmsPage->setCategory($this->getRandomCategories());
            $cmsPage->setLayout(rand(1,2).'-col');
    
            $this->addReference('cmsPage-'.$i, $cmsPage);
            $manager->persist($cmsPage);
        }

        $cmsPage = new CmsPage();
        $cmsPage->setIsActive(true);
        $cmsPage->setName("Entreprise");
        $cmsPage->setDescription($this->getRandomBody());
        $cmsPage->setImage('entreprise.jpg');
        $cmsPage->setImages($tImages);
        $cmsPage->setCategory($this->getRandomCategories());
        $cmsPage->setOnHome(true);
        $cmsPage->setLayout('2-col');
        
        $cmsPage->setTitleContents($tContents);
        $manager->persist($cmsPage);
        
        $cmsPage = new CmsPage();
        $cmsPage->setIsActive(true);
        $cmsPage->setName("Zéro Déchet");
        $cmsPage->setDescription($this->getRandomBody());
        $cmsPage->setImage('dechet.jpg');
        $cmsPage->setCategory($this->getRandomCategories());
        $cmsPage->setOnHome(true);
        $cmsPage->setTitleContents($tContents);
        $cmsPage->setImages($tImages);
        $cmsPage->setLayout('1-col');
        
        $manager->persist($cmsPage);
        $manager->flush();
    }

    private function getRandomBody()
    {
        $phrases = array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Pellentesque vitae velit ex.',
            'Mauris dapibus, risus quis suscipit vulputate, eros diam egestas libero, eu vulputate eros eros eu risus.',
            'In hac habitasse platea dictumst.',
            'Morbi tempus commodo mattis.',
            'Donec vel elit dui.',
            'Ut suscipit posuere justo at vulputate.',
            'Phasellus id porta orci.',
            'Ut eleifend mauris et risus ultrices egestas.',
            'Aliquam sodales, odio id eleifend tristique, urna nisl sollicitudin urna, id varius orci quam id turpis.',
            'Nulla porta lobortis ligula vel egestas.',
            'Curabitur aliquam euismod dolor non ornare.',
            'Nunc et feugiat lectus.',
            'Nam porta porta augue.',
            'Sed varius a risus eget aliquam.',
            'Nunc viverra elit ac laoreet suscipit.',
            'Pellentesque et sapien pulvinar, consectetur eros ac, vehicula odio.',
        );

        $numPhrases = rand(5, 10);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases - 1));
    }
    
    private function getRandomName()
    {
        $words = array(
           'Lorem', 'Ipsum', 'Sit', 'Amet', 'Adipiscing', 'Elit',
           'Vitae', 'Velit', 'Mauris', 'Dapibus', 'Suscipit', 'Vulputate',
           'Eros', 'Diam', 'Egestas', 'Libero', 'Platea', 'Dictumst',
           'Tempus', 'Commodo', 'Mattis', 'Donec', 'Posuere', 'Eleifend',
        );
        
        $numWords = 2;
        shuffle($words);
        
        return 'CmsPage '.implode(' ', array_slice($words, 0, $numWords));
    }

    private function getRandomCategories()
    {
        $categories = array();
        $numCategories = rand(1, 2);
        $allCategoryIds = range(1, 6);
        $selectedCategoryIds = array_rand($allCategoryIds, $numCategories);

        foreach ((array) $selectedCategoryIds as $categoryId) {
            $categories[] = $this->getReference('modules-category-'.$categoryId);
        }

        return $categories;
    }
}
