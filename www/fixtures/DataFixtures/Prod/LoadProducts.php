<?php

namespace Fixtures\DataFixtures\Prod;

use Admin\Entity\Diy;
use Admin\Entity\Product;
use Admin\Entity\ProService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProducts extends Fixture implements OrderedFixtureInterface
{
    
    public function getOrder()
    {
        return 50;
    }
    
    public function load(ObjectManager $manager)
    {
        // PRODUCT

        //textile
        foreach (range(0, 14) as $i) {
            $item = new Product();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setDescription($this->getRandomDescription());
            $item->setPrice($this->getRandomPrice());
            $item->setEan($this->getRandomEan());
            $item->setImage('image'.rand(0, 3).'.jpg');
            $item->setCategory($this->getTextileCategories());
            $item->setTags($this->getRandomTags());
            $item->setFeatures($this->getRandomFeatures());
            $item->setStock(rand(1, 100));

            $this->addReference('product-'.$i, $item);
            $manager->persist($item);
        }

        //cuisine
        foreach (range(15, 29) as $i) {
            $item = new Product();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setDescription($this->getRandomDescription());
            $item->setPrice($this->getRandomPrice());
            $item->setEan($this->getRandomEan());
            $item->setImage('image'.rand(0, 3).'.jpg');
            $item->setCategory($this->getCuisineCategories());
            $item->setTags($this->getRandomTags());
            $item->setFeatures($this->getRandomFeatures());
            $item->setStock(rand(1, 100));

            $this->addReference('product-'.$i, $item);
            $manager->persist($item);
        }


        //jardin
        foreach (range(30, 37) as $i) {
            $item = new Product();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setDescription($this->getRandomDescription());
            $item->setPrice($this->getRandomPrice());
            $item->setEan($this->getRandomEan());
            $item->setImage('image'.rand(0, 3).'.jpg');
            $item->setCategory($this->getJardinageCategories());
            $item->setTags($this->getRandomTags());
            $item->setFeatures($this->getRandomFeatures());
            $item->setStock(rand(1, 100));

            $this->addReference('product-'.$i, $item);
            $manager->persist($item);
        }

        //electro
        foreach (range(38, 53) as $i) {
            $item = new Product();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setDescription($this->getRandomDescription());
            $item->setPrice($this->getRandomPrice());
            $item->setEan($this->getRandomEan());
            $item->setImage('image'.rand(0, 3).'.jpg');
            $item->setCategory($this->getElectroCategories());
            $item->setTags($this->getRandomTags());
            $item->setFeatures($this->getRandomFeatures());
            $item->setStock(rand(1, 100));
            

            $this->addReference('product-'.$i, $item);
            $manager->persist($item);
        }
        

        //maison
        foreach (range(54, 73) as $i) {
            $item = new Product();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setDescription($this->getRandomDescription());
            $item->setPrice($this->getRandomPrice());
            $item->setEan($this->getRandomEan());
            $item->setImage('image'.rand(0, 3).'.jpg');
            $item->setCategory($this->getMaisonCategories());
            $item->setTags($this->getRandomTags());
            $item->setFeatures($this->getRandomFeatures());
            $item->setStock(rand(1, 100));

            $this->addReference('product-'.$i, $item);
            $manager->persist($item);
        }

        // DIY
        foreach (range(0, 29) as $i) {
            $item = new Diy();
            $item->setIsActive((rand(1, 1000) % 10) < 7);
            $item->setName($this->getRandomName());
            $item->setBody($this->getRandomDescription());
            $item->setIngredients($this->getRandomTags());
            $item->setSummary($this->getRandomDescription());
            $item->setDifficulty(rand(1, 4));
            $item->setTime(rand(1, 4).'m');
            $item->setImage('image-diy-0.png');
            
            $this->addReference('diy-'.$i, $item);
            $manager->persist($item);
        }
        
        // Pro
        $colors = ['bleue', 'jaune', 'verte'];
        foreach (range(0, 2) as $i) {
            $item = new ProService();
            $item->setIsActive(true);
            $item->setName($this->getRandomName());
            $item->setPrice(rand(130, 180));
            $item->setReference($this->getRandomEan(1000, 2000));
            $item->setDescription($this->getRandomDescPro($colors[$i]));
            $item->setShortDescription($this->getRandomMiniDescPro($colors[$i]));
            $item->setImage('pro-'.$i.'.png');
    
            $manager->persist($item);
        }
        
        $manager->flush();
    }
    
    private function getRandomMiniDescPro($color = 'bleue')
    {
        return 'Cette poubelle '.$color.' bleu permet de recycler les produits en plastiques';
    }
    
    private function getRandomDescPro($color = 'bleue')
    {
        return 'Nous mettons à disposition des poubelles '.$color.' pour le recyclage des produits en cartons.<
                    Toutes les semaines une personnes est chargée de venir vider les poubelles.';
    }
    
    private function getRandomTags()
    {
        $tags = array(
           'paper',
           'special',
           'Dummy',
           'Random',
           'bague',
           'tiroir',
           'papier',
           'Nox',
           'apsum',
           'pain',
           'grille',
           'amet',
           'dolor',
           'Ipsum',
           'Lorem',
        );
        
        $numTags = rand(5, 7);
        shuffle($tags);
        
        return array_slice($tags, 0, $numTags - 1);
    }
    
    private function getRandomEan($start = null, $end = null)
    {
        $start = $start ?? 100000;
        $end = $end ?? 999999;
        
        $ean13 = mt_rand($start, $end).mt_rand($start, $end);
        
        $checksum = 0;
        foreach (str_split(strrev($ean13)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        $checksum = ((10 - ($checksum % 10)) % 10);
        
        return $ean13.$checksum;
    }
    
    private function getRandomName()
    {
        $words = array(
            'Lorem', 'Ipsum', 'Sit', 'Amet', 'Adipiscing', 'Elit',
            'Vitae', 'Velit', 'Mauris', 'Dapibus', 'Suscipit', 'Vulputate',
            'Eros', 'Diam', 'Egestas', 'Libero', 'Platea', 'Dictumst',
            'Tempus', 'Commodo', 'Mattis', 'Donec', 'Posuere', 'Eleifend',
        );
        
        $numWords = 1;
        shuffle($words);
        
        return implode(' ', array_slice($words, 0, $numWords));
    }
    
    private function getRandomPrice()
    {
        $cents = array('00', '29', '39', '49', '99');
        
        return (float)rand(2, 79).'.'.$cents[array_rand($cents)];
    }
    
    private function getRandomDescription()
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
    
    private function getRandomFeatures()
    {
        $features = array(
           'Feature Aenean vel mauris quis erat volutpat placerat.',
           'Feature Sed at est non nisl mattis semper.',
           'Feature Morbi vehicula magna sed vestibulum congue.',
           'Feature Quisque id enim in neque condimentum accumsan.',
           'Feature Pellentesque ornare enim vel lacus euismod, a finibus nibh facilisis.',
           'Feature Vestibulum laoreet erat a porttitor pharetra.',
           'Feature Ut tincidunt diam eu risus commodo posuere.',
           'Feature Curabitur vitae nulla eu orci elementum semper in lacinia lacus.',
           'Feature Proin nec nunc condimentum, tristique lorem in, sollicitudin lectus.',
           'Feature Duis lobortis orci vel velit molestie mattis eu a nisi.',
           'Feature Fusce luctus metus et interdum malesuada.',
           'Feature Nam ut nulla placerat, egestas neque in, maximus dui.',
           'Feature Morbi rutrum augue nec purus fringilla bibendum ac id risus.',
        );
        
        $numFeatures = rand(3, 6);
        shuffle($features);
        
        return array_slice($features, 0, $numFeatures - 1);
    }
    
    
    private function getTextileCategories()
    {
        $categories = array();
        $categories[] = $this->getReference('textile');
        return $categories;
    }


    private function getCuisineCategories()
    {
        $categories = array();
        $categories[] = $this->getReference('cuisine');
        return $categories;
    }

    private function getJardinageCategories()
    {
        $categories = array();
        $categories[] = $this->getReference('jardin');
        return $categories;
    }

    private function getElectroCategories()
    {
        $categories = array();
        $categories[] = $this->getReference('electro');
        return $categories;
    }

    private function getMaisonCategories()
    {
        $categories = array();
        $categories[] = $this->getReference('maison');
        return $categories;
    }
}
