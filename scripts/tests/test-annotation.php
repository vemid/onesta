<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Form\Renderer\Json;
use Vemid\ProjectOne\Entity\Entity\User;

require_once __DIR__ . '/../init.php';

$entityManager = $container->get(EntityManagerInterface::class);
/** @var User $user */
$user = $entityManager->find(User::class, 26);
foreach ($user->getUserRoleAssignments() as $role) {
    print_r($role->getId());
}
