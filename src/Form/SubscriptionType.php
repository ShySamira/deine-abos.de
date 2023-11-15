<?php  

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\PaymentType;
use App\Entity\Subscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('costs', NumberType::class)
            ->add('startDate', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('period', ChoiceType::class, [
                'choices' => [
                    'Monthly' => 1,
                    'Quarterly' => 3,
                    'Half-yearly' => 6,
                    'Yearly' => 12
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('paymentType', EntityType::class, [
                'class' => PaymentType::class,
                'choice_label' => 'name',
                
            ])
            ->add('submit', SubmitType::class);      //alternative method to add submit-button commented in template
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}