<?php
namespace UA\UserBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UA\UserBundle\Entity\User;
 
class Users implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $noms = array('Moustako', 'John', 'Talus');
    $password = array('f65tyuk2', 'lol', 'lol');
 
    foreach ($noms as $i => $nom) {
      // On crée l'utilisateur
      $users[$i] = new User;
 
      // Le nom d'utilisateur et le mot de passe sont identiques
      $users[$i]->setUsername($nom);
      $users[$i]->setPassword($password[$i]);
 
      // Le sel et les rôles sont vides pour l'instant
      $users[$i]->setSalt('');
      $users[$i]->setRoles(array('ROLE_SUPER_ADMIN'));
 
      // On le persiste
      $manager->persist($users[$i]);
    }
 
    // On déclenche l'enregistrement
    $manager->flush();
  }
}