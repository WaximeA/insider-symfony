<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;


class UserType extends AbstractType
{
// todo
//    private $roles;
//
//    public function __construct(RoleHierarchyInterface $roles)
//    {
//        $this->hierarchy = $roles;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
// todo
//            ->add('roles', ChoiceType::class, array(
//                'choices'  => array(
//                    'ROLE_ADMIN' => true,
//                    'ROLE_EDIT' => true,
//                    'ROLE_SUPER_ADMIN' => false,
//                    )
//            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}