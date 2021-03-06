<?php
namespace FrontOffice\Form\Shopping;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FrontOffice\Entity\ShippingMethod;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;

class ShippingMethodForm extends AbstractType
{
    private $objectManager;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'shippingMethod',
                EntityType::class,
                [
                'class' => ShippingMethod::class,
                'expanded' => true,
                'choices' => $this->objectManager
                    ->getRepository(ShippingMethod::class)
                    ->findAll(),
                'choice_label' => function (ShippingMethod $method) {
                    return $method->getName();
                },
                'constraints' => [
                    new NotNull(),
                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => null,
            ]
        );
    }
}
