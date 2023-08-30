Projet LaSalle Quiz App. 7/26/2023

Equipe 2:

-Zachary Morneau  DEV1- Homme a tout faire, aide la ou c'est necessaire.
-Felix Gagne      DEV2- Log-in et tout le reste vraiment.
-Simon Guay Gozzi DEV3- Base de donnees et beaucoup plus qu'il devais faire initiallement.
-Olivier Ward     DEV4- Jeu et ses niveaux, mais plus important encore.. le ReadME.txt
-Neil Lopes       DEV5- Sign-in, validation and ajax popups. Front end, made everything pretty.


Enumerations/ Description des fonctionalitees:

- Creation de comptes utilisateurs ( Sera sauvegardee dans la BD pour usage futur )
- Connexion/ deconnexion a un compte utilisateur
- Modification de mot de passe ( Quand on entre un mauvais MDP d'un utilisateur )
- 6 niveaux de jeu ( inclu une logique differente pour chacun )
- Abandon de partie en cour. ( possibilitee d'arreter une partie en cour/ resultat sauvegarde )
- Historique des parties jouees anterieurement. ( Table affichant les resultats sauvegarde dans la BD )
- Base de donnee contenant les utilisateurs crees/ parties et leurs resultats.
- ETC


Fonctionnement du jeu/ Description des niveaux:
nombre de vie = 6 

- Niveau 1: L'utilisateur doit mettre en ordre croissant les 6 lettres generees aleatoirement. S'il reussi, il passe au niveau prochain, sinon il perd une vie.

- Niveau 2: L'utilisateur doit mettre en ordre decroissant les 6 lettres generees aleatoirement. S'il reussi, il passe au prochain niveau, sinon il perd une vie.

- Niveau 3: L'utilisateur doit mettre en ordre croissant les 6 nombres generees aleatoirement. S'il reussi, il passe au prochain niveau, sinon il perd une vie.

- Niveau 4: L'utilisateur doit mettre en ordre decroissant les 6 nombres generees aleatoirement. S'il reussi, il passe au prochain niveau, sinon il perd une vie.

- Niveau 5: L'utilisateur doit choisir la lettre la plus base et la lettre la plus haute dans une liste de 6 lettres generees aleatoirement. S'il reussi il passe au prochain niveau, sinon il perd une vie.

- Niveau 6: L'utilisateur doit choisir le nombre le plus bas et le nombre le plus haut dans une liste de 6 chiffres generees aleatoirement. S'il reussi il passe au prochain niveau, sinon il perd une vie.