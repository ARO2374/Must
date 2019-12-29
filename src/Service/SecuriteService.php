<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Service de gestion des marques.
 */
class SecuriteService
{
    /**
     * @var $userRepository
     */
    private $userRepository;

    private $passwordEncoder;

    /**
     * Constructeur.
     * @param $userRepository Le repository de gestion des utilisateurs.
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder){
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Génère un token unique si les identifiants sont corrects.
     * @param $email L'email de l'utilisateur.
     * @param $password Le mot de passe de l'utilisateur.
     */
    public function gererToken(string $email, string $password): string
    {
        // Récupération de l'utilisateur par l'email
        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            // throw Exception ('Email invalide !')
            $this->supprimerToken($email);
            return 'Email invalide !';
        }

        // Vérification du mot de passe
        $credentialsvalid = $this->passwordEncoder->isPasswordValid($user, $password);
        if (!$credentialsvalid) {
            // throw Exception ('Mot de passe invalide !')
            $this->supprimerToken($email);
            return 'Mot de passe invalide !';
        }

        // Génération d'un token unique
        $token = '';
        $tokenEstUnique = false;
        do {
            // Génération d'un token
            $now = getdate();
            $token = base64_encode($email . $now[0]); // ajout du timestamp comme sel

            // Test de l'unicité du token généré
            $tokenEstUnique = $this->userRepository->countByApiToken($token) === 0;
        } while (!$tokenEstUnique);

        // Affectation du token à l'utilisateur et renvoi
        $user->setApiToken($token);
        $this->userRepository->save($user);

        return $token;
    }

    /**
     * Supprime le token de l'utilisateur passé en paramètre.
     * Simule une déconnection.
     * @param $email L'email de l'utilisateur.
     */
    public function supprimerToken(string $email): void
    {
        // Récupération de l'utilisateur par l'email
        $user = $this->userRepository->findOneByEmail($email);
        
        // Réinitialisation du token de l'utilisateur
        $user->setApiToken(null);
        $this->userRepository->save($user);
    }
}