# NotePro 

### Instructions pour la configuration et le démarrage

1. **Créer le fichier `.env.local`**
    - Configurer le chemin vers la base de données (BDD).

2. **Créer la BDD et mettre à jour le schéma**
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migrations:migrate`

3. **Démarrer le serveur Symfony**

4. **Installer les fixtures `php bin/console doctrine:fixtures:load --no-interaction`**
    - Cela crée des données de test utiles pour vérifier le résultat de vos développements.

5. **Comptes de test disponibles**
   (Note: login par email)
    - **Comptes profs** :
        - éditer le contenu de la table user pour obtenir un email - les profs ont le role 'ROLE_PROFESSOR'
        - mot de passe : `prof`
    - **Comptes étudiants** :
        - éditer le contenu de la table user pour obtenir un email - les étudiants ont le role 'ROLE_STUDENT'
        - mot de passe : `etudiant`
    - **Compte admin** :
        - compte: `admin@lycee-faure.fr`
        - mot de passe : `adminpassword`
