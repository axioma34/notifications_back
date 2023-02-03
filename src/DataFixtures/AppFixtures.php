<?php

namespace App\DataFixtures;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('user');

        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);

        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $adminPassword = $this->hasher->hashPassword($user, 'admin_password');
        $admin->setPassword($adminPassword);

        $manager->persist($admin);

        // Add notifications
        for ($i = 1; $i < 10; $i++) {
            $notification = new Notification();
            $notification->setHeader('Header '.$i);
            $notification->setText('Text '.$i);
            $manager->persist($notification);
        }


        $manager->flush();
    }
}
